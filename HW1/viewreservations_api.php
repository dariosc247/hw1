<?php
    include 'dbconfig.php';

    $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
    
    $query = "SELECT P.TITOLO, P.DESCRIZIONE, P.DATA, P.ORARIO_DA, P.ORARIO_FINO, U.USERNAME FROM PRENOTAZIONE P JOIN UTENTE U ON P.UTENTE = U.ID";
    $result = mysqli_query($connection, $query) or die(mysqli_error($connection));

    $prenotazioni = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $prenotazioni[] = $row;
    }

    mysqli_free_result($result);
    mysqli_close($connection);

    echo json_encode($prenotazioni);
?>