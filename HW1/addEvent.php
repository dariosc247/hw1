<?php

    /*
        Inserisce l'evento nel database.
    */

    include 'api_parameters_config.php';
    
    $postData = $statusMsg = $valErr = ''; 
    $status = 'danger'; 
    
    if(isset($_POST['submit'])){ 
        
        #prendo le informazioni dell'evento
        $_SESSION['postData'] = $_POST;
        $titolo = !empty($_POST['titolo'])?trim($_POST['titolo']):'';
        $descrizione = !empty($_POST['descrizione'])?trim($_POST['descrizione']):'';
        $data = !empty($_POST['data'])?trim($_POST['data']):''; 
        $orario_da = !empty($_POST['orario_da'])?trim($_POST['orario_da']):''; 
        $orario_fino = !empty($_POST['orario_fino'])?trim($_POST['orario_fino']):''; 
        $utente = !empty($_SESSION['id'])?trim($_SESSION['id']):''; 
        
        if(empty($titolo)){ 
            $valErr .= 'Inserisci il titolo!<br/>'; 
        } 
        if(empty($data)){ 
            $valErr .= 'Inserisci la data!<br/>'; 
        } 
        
        #se non ci sono errori da parte dell'utente
        if(empty($valErr)){  
            $sqlQ = "INSERT INTO PRENOTAZIONE (TITOLO,DESCRIZIONE,DATA,ORARIO_DA,ORARIO_FINO,CREATED,UTENTE) VALUES (?,?,?,?,?,NOW(),?)";
            $stmt = $db->prepare($sqlQ); 
            $stmt->bind_param("ssssss", $db_title, $db_description, $db_date, $db_time_from, $db_time_to, $db_user); 
            $db_title = $titolo; 
            $db_description = $descrizione;  
            $db_date = $data; 
            $db_time_from = $orario_da; 
            $db_time_to = $orario_fino;
            $db_user = $utente; 
            $insert = $stmt->execute(); 
            
            if($insert){ 
                $event_id = $stmt->insert_id; 
                
                unset($_SESSION['postData']); 
                
                #memorizzo l'ID dell'evento nella sessione
                $_SESSION['last_event_id'] = $event_id; 
                
                header("Location: $googleOauthURL"); 
                exit();
            }else{ 
                $statusMsg = 'Qualcosa è andato storto, riprovare successivamente.'; 
            } 
        }else{ 
            $statusMsg = '<p>Compila tutti i campi:</p>'.trim($valErr, '<br/>'); 
        } 
    }else{ 
        $statusMsg = 'Invio del form fallito!'; 
    } 
    
    $_SESSION['status_response'] = array('status' => $status, 'status_msg' => $statusMsg); 
    
    header("Location: restaurant.php"); 
    exit(); 
?>