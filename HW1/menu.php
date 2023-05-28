<?php
    include 'dbconfig.php';

    $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
    
    $queryCategorie = "SELECT * FROM CATEGORIA ORDER BY ID";
    $res1 = mysqli_query($connection, $queryCategorie) or die(mysqli_error($connection));

    $categorie = array();
    while ($row = mysqli_fetch_assoc($res1)) {
        $categorie[] = $row;
    }

    mysqli_free_result($res1);
    mysqli_close($connection);
    
    echo json_encode($categorie);    
?>