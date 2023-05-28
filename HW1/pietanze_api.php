<?php
    include 'dbconfig.php';
    
    $category = $_GET['category'];
    if(!isset($category)) {
        header("Location: restaurant.php");
        exit;
    }

    $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
    
    $query = "SELECT * FROM PIETANZA WHERE CATEGORIA = '".$category."'";
    $res2 = mysqli_query($connection, $query) or die(mysqli_error($connection));

    $pietanze = array();
    while ($row = mysqli_fetch_assoc($res2)) {
        $pietanze[] = $row;
    }

    mysqli_free_result($res2);
    mysqli_close($connection);

    echo json_encode($pietanze);
?>