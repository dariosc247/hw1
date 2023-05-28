<?php
    include 'dbconfig.php';

    $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($connection));

    $query = "SELECT U.USERNAME, R.VOTO, R.COMMENTO FROM RECENSIONE R JOIN UTENTE U ON R.UTENTE = U.ID ORDER BY R.VOTO DESC";
    $result = mysqli_query($connection, $query);

    $recensioni = array();

    while($row = mysqli_fetch_assoc($result)) {
        $recensioni[] = $row;
    }
    
    mysqli_close($connection);

    echo json_encode($recensioni);
?>