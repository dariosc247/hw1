<?php 
    /*
        Questo script è impostato come URI di reindirizzamento nelle impostazioni dell'API di Google.
        Ciò significa che dopo l'autenticazione con l'account Google, l'utente verrà reindirizzato a questo script
        che gestisce il processo di creazione dell'evento di Google Calendar con PHP.
    */

    include_once 'GoogleCalendarApi.class.php';
    include 'api_parameters_config.php';
        
    $statusMsg = ''; 
    $status = 'danger';
     
    if(isset($_GET['code'])) { 
        $GoogleCalendarApi = new GoogleCalendarApi(); #istanzio una nuova classe 

        $event_id = $_SESSION['last_event_id']; 
    
        if(!empty($event_id)){ 
            $sqlQ = "SELECT * FROM PRENOTAZIONE WHERE ID = ?"; 
            $stmt = $db->prepare($sqlQ);
            $stmt->bind_param("i", $db_event_id); 
            $db_event_id = $event_id; 
            $stmt->execute(); 
            $result = $stmt->get_result(); 
            $eventData = $result->fetch_assoc(); 
            
            if(!empty($eventData)){ 
                $calendar_event = array( 
                    'titolo' => $eventData['TITOLO'],
                    'descrizione' => $eventData['DESCRIZIONE'],
                ); 
                
                $event_datetime = array( 
                    'data' => $eventData['DATA'], 
                    'orario_da' => $eventData['ORARIO_DA'], 
                    'orario_fino' => $eventData['ORARIO_FINO'] 
                ); 
                
                #prendo l'Access Token
                $access_token_sess = $_SESSION['google_access_token']; 
                if(!empty($access_token_sess)) { 
                    $access_token = $access_token_sess; 
                }
                else { 
                    $data = $GoogleCalendarApi->GetAccessToken(GOOGLE_CLIENT_ID, REDIRECT_URI, GOOGLE_CLIENT_SECRET, $_GET['code']); 
                    $access_token = $data['access_token']; 
                    $_SESSION['google_access_token'] = $access_token; 
                } 
                
                if(!empty($access_token)){ 
                    try {
                        $user_timezone = $GoogleCalendarApi->GetUserCalendarTimezone($access_token); 
                    
                        $google_event_id = $GoogleCalendarApi->CreateCalendarEvent($access_token, 'primary', $calendar_event, 0, $event_datetime, $user_timezone); 
                        
                        //echo json_encode([ 'event_id' => $event_id ]); 
                        
                        if($google_event_id){ 
                            $sqlQ = "UPDATE PRENOTAZIONE SET GOOGLE_CALENDAR_EVENT_ID=? WHERE ID=?"; 
                            $stmt = $db->prepare($sqlQ); 
                            $stmt->bind_param("si", $db_google_event_id, $db_event_id); 
                            $db_google_event_id = $google_event_id; 
                            $db_event_id = $event_id; 
                            $update = $stmt->execute(); 
                            
                            unset($_SESSION['last_event_id']); 
                            unset($_SESSION['google_access_token']); 
                            
                            $status = 'success'; 
                            $statusMsg = '<p class = "result-p">Evento #'.$event_id.' aggiunto correttamente a Google Calendar!</p>'; 
                            $statusMsg .= '<p class = "result-p"><a href="https://calendar.google.com/calendar/" target="_blank">Apri Calendario</a>'; 
                        } 
                    } catch(Exception $e) { 
                        //header('Bad Request', true, 400); 
                        //echo json_encode(array( 'error' => 1, 'message' => $e->getMessage() )); 
                        $statusMsg = $e->getMessage(); 
                    } 
                }else{ 
                    $statusMsg = 'Failed to fetch access token!'; 
                } 
            }else{ 
                $statusMsg = 'Event data not found!'; 
            } 
        }else{ 
            $statusMsg = 'Event reference not found!'; 
        } 
        
        $_SESSION['status_response'] = array('status' => $status, 'status_msg' => $statusMsg); 
        
        header("Location: restaurant.php#reservation");
        exit(); 
    } 
?>