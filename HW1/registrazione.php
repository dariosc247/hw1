<?php
    include 'dbconfig.php';

    if(isset($_POST["username"]) && isset($_POST["password"])) {
        $error = array();

        $connection = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']) or die(mysqli_error($connection));

        if(!preg_match('/^[a-zA-Z0-9]+$/', $_POST['username'])) {
            $error[] = "L'username non rispetta i parametri specificati!";
        }
        else {
            $username = mysqli_real_escape_string($connection, $_POST['username']);

            $query = "SELECT * FROM UTENTE WHERE username = '".$username."'";
            $result = mysqli_query($connection, $query);
            
            if(mysqli_num_rows($result) > 0) {
                $error[] = "Username già in uso!";
            }
        }

        if(strlen($_POST["password"]) < 8) {
            $error[] = "La password deve contenere almeno 8 caratteri!";
        }

        if(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', $_POST["password"]) || !preg_match('/[!@#$%^&*()_+\-=[\]{};\'":\\|,.<>\/?]+/', $_POST["password"])) {
            $error[] = "La password deve contenere almeno una lettera minuscola, una lettera maiuscola, un numero e un carattere speciale!";
        }

        if(count($error) == 0) {
            $username = mysqli_real_escape_string($connection, $_POST['username']);
            $password = mysqli_real_escape_string($connection, $_POST['password']);

            if(strcmp($username, 'gestore') == 0) {
                $error[] = "Username riservato!";
                return false;
            }

            $query = "INSERT INTO UTENTE(USERNAME, PASSWORD, GESTORE) VALUES ('$username', '$password', '0')";

            if(mysqli_query($connection, $query)) {
                $last_id = mysqli_insert_id($connection);

                //mysqli_close($connection);
                //header("Location: login.php");
                //exit();
            }
            else {
                $error[] = "Errore: impossibile connettersi al database!";
            }
        }

        mysqli_close($connection);
    }
    else if(empty($_POST["username"]) || empty($_POST["password"])) {
        $error[] = "Necessario riempire tutti i campi!";
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registrazione</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "registrazione.css">
    <script src = "registrazione.js" defer = "true"></script>
</head>
<body>

    <h1 id = "title">Registrati</h1>
    <form class = "form" method = "POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <p id = "username-error" class = "error-message"></p>
        <br>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <p id = "password-error" class = "error-message"></p>
        <br>
        <br>
        <input type="submit" value="Registrati">
        <div class="signup">Hai un account? <a href="login.php">Accedi</a>
    </form>

    <?php
        if(count($error) == 0) {
            echo "<h2 class = 'welcome'>";
            echo "Registrazione avvenuta con successo: clicca su Accedi per accedere!";
            echo "</h2>";
        }
    ?>

</body>
</html>