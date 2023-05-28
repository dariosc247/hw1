<?php

    /*
        Google fornisce una libreria client PHP per fare chiamate all'API di Calendar, ma questa contiene tanti servizi aggiuntivi
        (ciò vuol dire un gran numero di file, anche di grandi dimensioni). Per semplificare un po' il tutto,
        ho trovato questa libreria personalizzata online per gestire le chiamate di Google Calendar API con PHP.
        Aiuta ad autenticarsi con l'account di Google.
        Utilizza l'API di Google Calendar v3 per gestire il processo di aggiunta dell'evento nel calendario.
    */

    class GoogleCalendarApi {

        const OAUTH2_TOKEN_URI = 'https://accounts.google.com/o/oauth2/token';  #endpoint per il token di accesso
        const CALENDAR_TIMEZONE_URI = 'https://www.googleapis.com/calendar/v3/users/me/settings/timezone'; 
        const CALENDAR_LIST = 'https://www.googleapis.com/calendar/v3/users/me/calendarList'; 
        const CALENDAR_EVENT = 'https://www.googleapis.com/calendar/v3/calendars/'; 
        
        /*
            Costruttore della classe: viene chiamato automaticamente quando si crea un nuovo oggetto della classe.
            Accetta un parametro opzionale $params che viene inizializzato come un array vuoto per default.
            Controlla se l'array $params contiene dei valori e, in caso affermativo, chiama il metodo initialize($params)
            per inizializzare gli attributi dell'oggetto.
        */
        function __construct($params = array()) { 
            if (count($params) > 0){ 
                $this->initialize($params);         
            }
        }

        /*
            Controlla se l'array $params contiene dei valori e, se sì, itera su di essi.
            Durante ogni iterazione, controlla se l'attributo corrispondente esiste nell'oggetto:
            se l'attributo esiste, il valore corrispondente viene assegnato all'attributo.
        */
        function initialize($params = array()) { 
            if (count($params) > 0){ 
                foreach ($params as $key => $val){ 
                    if (isset($this->$key)){ 
                        $this->$key = $val; 
                    } 
                }         
            } 
        }
        
        /*
            Recupera il token di accesso dall'API di Google OAuth 2 utilizzando il codice di autenticazione.
        */
        public function GetAccessToken($client_id, $redirect_uri, $client_secret, $code) { #info necessarie per il token di accesso
            
            $curlPost = 'client_id=' . $client_id . '&redirect_uri=' . $redirect_uri . '&client_secret=' . $client_secret . '&code='. $code . '&grant_type=authorization_code'; 
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, self::OAUTH2_TOKEN_URI);         
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);         
            curl_setopt($ch, CURLOPT_POST, 1);         
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
            $data = json_decode(curl_exec($ch), true); #estrapolo i dati dal json e li metto in un array associativo
            $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE); #ottengo il codice di stato HTTP della risposta
            
            if ($http_code != 200) { 
                $error_msg = 'Failed to receieve access token'; 
                if (curl_errno($ch)) { 
                    $error_msg = curl_error($ch); 
                } 
                throw new Exception('Error '.$http_code.': '.$error_msg); 
            } 
                
            return $data; #array associativo che contiene i dati forniti dal server 
        }
    
        /*
            Effettua una richiesta HTTP per recuperare il fuso orario dell'utente (usando il token fornito) dalle impostazioni del calendario di Google.
        */
        public function GetUserCalendarTimezone($access_token) {
            $ch = curl_init();         
            curl_setopt($ch, CURLOPT_URL, self::CALENDAR_TIMEZONE_URI); #URL a cui fare la richiesta       
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); #si vuole ricevere il risultato come stringa
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token)); #aggiunge un'intestazione di autorizzazione
            #all'interno della richiesta CURL, consentendo di includere il token di accesso necessario per autenticarsi
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            $data = json_decode(curl_exec($ch), true); 
            $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE); 
            
            if ($http_code != 200) { 
                $error_msg = 'Failed to fetch timezone'; 
                if (curl_errno($ch)) { 
                    $error_msg = curl_error($ch); 
                } 
                throw new Exception('Error '.$http_code.': '.$error_msg); 
            } 
    
            return $data['value']; #valore del fuso orario ottenuto dalla risposta del server
        }

        /*
            Effettua una richiesta HTTP per ottenere l'elenco di calendari dell'utente con token $access_token.
        */
        public function GetCalendarsList($access_token) { 
            $url_parameters = array(); 
    
            $url_parameters['fields'] = 'items(id,summary,timeZone)'; 
            $url_parameters['minAccessRole'] = 'owner'; #specifica il ruolo minimo di accesso richiesto per i calendari
            #(in questo caso viene impostato su "owner" per ottenere solo i calendari di cui l'utente è il proprietario)
    
            $url_calendars = self::CALENDAR_LIST.'?'. http_build_query($url_parameters); #codifica i parametri dell'URL e li concatena a CALENDAR_LIST
            
            $ch = curl_init();         
            curl_setopt($ch, CURLOPT_URL, $url_calendars);         
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);     
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token));     
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);     
            $data = json_decode(curl_exec($ch), true); 
            $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE); 
            
            if ($http_code != 200) { 
                $error_msg = 'Failed to get calendars list'; 
                if (curl_errno($ch)) { 
                    $error_msg = curl_error($ch); 
                } 
                throw new Exception('Error '.$http_code.': '.$error_msg); 
            } 
    
            return $data['items']; #array dei calendari ottenuti dalla risposta del server
        } 

        /*
            Crea un evento e lo aggiunge al calendario degli eventi.
        */
        public function CreateCalendarEvent($access_token, $calendar_id, $event_data, $all_day, $event_datetime, $event_timezone) { 
            $apiURL = self::CALENDAR_EVENT . $calendar_id . '/events'; 
            
            $curlPost = array(); 
            
            if(!empty($event_data['titolo'])){ 
                $curlPost['summary'] = $event_data['titolo']; 
            } 
            
            if(!empty($event_data['descrizione'])){ 
                $curlPost['description'] = $event_data['descrizione']; 
            } 
            
            $event_date = !empty($event_datetime['data'])?$event_datetime['data']:date("Y-m-d"); 
            $start_time = !empty($event_datetime['orario_da'])?$event_datetime['orario_da']:date("H:i:s"); 
            $end_time = !empty($event_datetime['orario_fino'])?$event_datetime['orario_fino']:date("H:i:s"); 
    
            if($all_day == 1){ 
                $curlPost['start'] = array('data' => $event_date); 
                $curlPost['end'] = array('data' => $event_date); 
            }else{ 
                $timezone_offset = $this->getTimezoneOffset($event_timezone); 
                $timezone_offset = !empty($timezone_offset)?$timezone_offset:'07:00'; 
                $dateTime_start = $event_date.'T'.$start_time.$timezone_offset; 
                $dateTime_end = $event_date.'T'.$end_time.$timezone_offset; 
                
                $curlPost['start'] = array('dateTime' => $dateTime_start, 'timeZone' => $event_timezone); 
                $curlPost['end'] = array('dateTime' => $dateTime_end, 'timeZone' => $event_timezone); 
            } 
            $ch = curl_init();         
            curl_setopt($ch, CURLOPT_URL, $apiURL);         
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);         
            curl_setopt($ch, CURLOPT_POST, 1);         
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer '. $access_token, 'Content-Type: application/json'));     
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curlPost));     
            $data = json_decode(curl_exec($ch), true); 
            $http_code = curl_getinfo($ch,CURLINFO_HTTP_CODE);         
            
            if ($http_code != 200) { 
                $error_msg = 'Failed to create event'; 
                if (curl_errno($ch)) { 
                    $error_msg = curl_error($ch); 
                } 
                throw new Exception('Error '.$http_code.': '.$error_msg); 
            } 
    
            return $data['id']; 
        } 
        
        private function getTimezoneOffset($timezone = 'America/Los_Angeles'){ 
            $current   = timezone_open($timezone); 
            $utcTime  = new \DateTime('now', new \DateTimeZone('UTC')); 
            $offsetInSecs =  timezone_offset_get($current, $utcTime); 
            $hoursAndSec = gmdate('H:i', abs($offsetInSecs)); 
            return stripos($offsetInSecs, '-') === false ? "+{$hoursAndSec}" : "-{$hoursAndSec}"; 
        } 
    } 
?>