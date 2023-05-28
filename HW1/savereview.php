<?php
    include 'dbconfig.php';

    session_start();
    $userid = $_SESSION['id'];

    $message = "Errore durante l'invio della recensione!";

    if(isset($_POST['voto']) && isset($_POST['commento'])) {

        $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($connection));

        $voto = (int)$_POST['voto'];
        $commento = mysqli_real_escape_string($connection, $_POST['commento']);
    
        $query = "INSERT INTO RECENSIONE (UTENTE, VOTO, COMMENTO) VALUES (".$userid.", ".$voto.", '".$commento."')";
        $res = mysqli_query($connection, $query) or die (mysqli_error($connection));
        if($res) {
            $message = "Recensione inviata con successo!";
        }
    
        mysqli_close($connection);    
    }

    echo json_encode(array('result' => $message, 'userid' => $userid));
?>