<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="restaurant.css">
    <script src = "viewreservations.js" defer = "true"></script>
    <title>Prenotazioni</title>
</head>
<body>

    <header class = "header">
        <nav>
            <ul>
                <li><a href = "restaurant.php">Torna alla Home</a></li>
                <li><a href = "logout.php"><button id = "logout-btn">Esci</button></a></li>
            </ul>
        </nav>
    </header>
    <section class ="container">
        <table id = "table">
                <th id = "th1" class = "hidden">TITOLO</th>
                <th id = "th2" class = "hidden">DESCRIZIONE</th>
                <th id = "th3" class = "hidden">DATA</th>
                <th id = "th4" class = "hidden">DALLE</th>
                <th id = "th5" class = "hidden">ALLE</th>
                <th id = "th6" class = "hidden">UTENTE</th>
                <tbody id = "content-table"></tbody>
        </table>
    </section>

    <footer class = "footer">
        <div class="container">
            <p>&copy; 2023 A Tavula 'i Nonna. Tutti i diritti riservati.</p>
            <div class="social-links">
                <a href="#" class="social-link"><img src = "./images/logo_facebook.png" alt="Facebook"></a>
                <a href="#" class="social-link"><img src = "./images/logo_twitter.png" alt="Twitter"></a>
                <a href="#" class="social-link"><img src = "./images/logo_instagram.png" alt="Instagram"></a>
                <a href="#" class="social-link"><img src = "./images/logo_tiktok.png" alt="TikTok"></a>
            </div>
        </div>
    </footer>
</body>
</html>