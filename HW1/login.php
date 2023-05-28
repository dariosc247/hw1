<?php
    include 'dbconfig.php';

    session_start();

    // Verifica se la variabile di sessione dell'utente esiste
    if (isset($_SESSION["username"])) {
        // Utente già autenticato, reindirizza alla pagina desiderata
        header("Location: restaurant.php");
        exit;
    }

    if(isset($_POST["username"]) && isset($_POST["password"]) /*se username e password sono stati inviati al server*/) {
        //connessione al DB
        $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($connection));

        $username = mysqli_real_escape_string($connection, $_POST['username']); //per evitare l'injection
        $password = mysqli_real_escape_string($connection, $_POST['password']);

        $query = "SELECT * FROM utente WHERE username = '".$username."'";

        $num = mysqli_query($connection, $query) or die(mysqli_error($connection));

        if(mysqli_num_rows($num) > 0) {
            $res = mysqli_fetch_assoc($num); //metto tutto in un array associativo
            if(!strcmp($_POST['password'], $res['PASSWORD'])) {
                $_SESSION["username"] = $res['USERNAME'];
                $_SESSION["id"] = $res['ID'];
                $_SESSION["gestore"] = $res['GESTORE'];
                header("Location: restaurant.php");
                mysqli_free_result($num);
                mysqli_close($connection);
                exit;
            }
            else {
                $error = "Password errata!";
                echo "<h2 class = 'error-message'>";
                print_r($error);
                echo "</h2>";
            }
        }
        else {
            $error = "Utente non trovato!";
            echo "<h2 class = 'error-message'>";
            print_r($error);
            echo "</h2>";
        }
    }
    else if(empty($_POST["username"]) || empty($_POST["password"])) {
        $error = "Necessario riempire tutti i campi!";
    }
?>

<html>

<head>
    <title>Login</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "login.css">
</head>

<body>

    <h1 id = "title">Accedi</h1>
    <form class = "form" method = "POST">
        <label for = "username">Username:</label>
        <input type = "text" id = "username" name = "username" required>
        <p id = "username-error" class = "error-message"></p>
        <br>
        <br>
        <label for = "password">Password:</label>
        <input type = "password" id = "password" name = "password" required>
        <p id = "password-error" class = "error-message"></p>
        <br>
        <br>
        <input type = "submit" value = "Login">
        <div class = "signin">Non hai un account? <a href="registrazione.php">Registrati ora!</a>
    </form>

</body>

</html>