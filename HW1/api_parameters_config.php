<?php 
    
    include 'dbconfig.php';
    
    // Google API configuration
    define('GOOGLE_CLIENT_ID', '970054912300-4n778v70oko9i4f0f7p67t3bbsac5lof.apps.googleusercontent.com'); 
    define('GOOGLE_CLIENT_SECRET', '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'); 
    define('GOOGLE_OAUTH_SCOPE', 'https://www.googleapis.com/auth/calendar'); 
    define('REDIRECT_URI', 'http://localhost/HW1/google_calendar_event_sync.php');
    
    
    if(!session_id()) session_start(); 
    
    //
    $googleOauthURL = 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode(GOOGLE_OAUTH_SCOPE) . '&redirect_uri=' 
    . REDIRECT_URI . '&response_type=code&client_id=' . GOOGLE_CLIENT_ID . '&access_type=online';
    
    $db = new mysqli($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']); 
 
    if ($db->connect_error) { 
        die("Connessione fallita: " . $db->connect_error); 
    }
 
?>