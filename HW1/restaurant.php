<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
    <title>A Tavula 'i Nonna</title>
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "restaurant.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src = "menu.js" defer = "true"></script>
    <script src = "review.js" defer = "true"></script>
</head>

<body>
    <header class = "header">
        <nav>
            <ul>
                <li><a href="#about">Chi siamo</a></li>
                <li><a href="#menu">Menu</a></li>
                <?php if((int)$_SESSION["gestore"] == 0) { ?>
                <li><a href = "#reservation">Prenota</a></li> <?php } else { ?>
                <li id = "view-reservations"><a href = "viewreservations.php">Prenotazioni</li> <?php } ?>
                <li><a href="#reviews">Recensioni</a></li>
                <li><a href="#contact">Contatti</a></li>
                <li><a href = "logout.php"><button id = "logout-btn">Esci</button></a></li>
            </ul>
        </nav>
    </header>
    <section id="hero">
        <div class="hero-content">
            <h1 class = "paragraph">Benvenuto ad <em>A Tavula 'i Nonna</em>,<br><?php print_r($_SESSION["username"])?></h1>
            <p>Un'esperienza culinaria siciliana unica!</p>
            <a href = "#reservation" class = "btn">Prenota un tavolo</a>
        </div>
    </section>
    <section id="about">
        <div class="container">
            <h2 class = "paragraph">Chi siamo</h2>
            <p>
                Benvenuti nel nostro ristorante siciliano, dove il gusto autentico dell'isola incontra la passione per la cucina tradizionale.
                Siamo orgogliosi di offrire piatti preparati con ingredienti freschi e selezionati, per regalare un'esperienza culinaria unica che celebra le tradizioni siciliane.
                Da noi potrete assaporare i sapori intensi della nostra terra, immergendovi in una genuina esperienza gastronomica.
                Venite a scoprire il calore dell'accoglienza siciliana e lasciatevi tentare dai nostri piatti tipici, preparati con cura e amore per la cucina.
                Siamo pronti ad offrirvi un viaggio gustativo indimenticabile nel cuore della Sicilia.
            </p>
        </div>
    </section>
    <section id="menu">
        <div class="container">
            <h2 class = "paragraph">Il nostro menu</h2>
            <p>Scopri le nostre specialità culinarie</p>
            <div class="menu-items">
                <div class="menu-item"></div>
            </div>
        </div>
    </section>

    <?php
    include_once 'api_parameters_config.php'; 
    
    $postData = ''; 
    if(!empty($_SESSION['postData'])){ 
        $postData = $_SESSION['postData']; 
        unset($_SESSION['postData']); 
    } 
    
    $status = $statusMsg = ''; 
    if(!empty($_SESSION['status_response'])){ 
        $status_response = $_SESSION['status_response']; 
        $status = $status_response['status']; 
        $statusMsg = $status_response['status_msg']; 
    } 
    ?>

    <?php if((int)$_SESSION["gestore"] == 0) { ?>
        <section id="reservation">
            <div class="container">
                <h2 class = "paragraph">Prenota</h2>
                <p>Prenota un tavolo per una serata indimenticabile!</p>
                <div class="col-md-12">
                    <form method="post" action="addEvent.php" class="form">
                        <div class="form-group">
                            <label>Titolo</label>
                            <input type="text" class="form-control" name="titolo" value="<?php echo !empty($postData['titolo'])?$postData['titolo']:''; ?>" required="">
                        </div>
                        <div class="form-group">
                            <label>Descrizione</label>
                            <textarea name="descrizione" class="form-control"><?php echo !empty($postData['descrizione'])?$postData['descrizione']:''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label>Data</label>
                            <input type="date" name="data" class="form-control" value="<?php echo !empty($postData['data'])?$postData['data']:''; ?>" required="">
                        </div>
                        <div class="form-group time">
                            <label>Dalle</label>
                            <input type="time" name="orario_da" class="form-control" value="<?php echo !empty($postData['orario_da'])?$postData['orario_da']:''; ?>">
                            <label>Alle</label>
                            <input type="time" name="orario_fino" class="form-control" value="<?php echo !empty($postData['orario_fino'])?$postData['orario_fino']:''; ?>">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="form-control btn-primary" name="submit" value="Prenota"/>
                        </div>
                    </form>
                </div>
            </div>
            <?php if(!empty($statusMsg)){ ?>
                <div class="alert alert-<?php echo $status; ?>"><?php echo $statusMsg; ?></div>
            <?php } ?>
        </section>
    <?php } ?>

    <section class="container" id = "reviews">
        <h2 class = "paragraph">Recensioni</h2>
        <p>Inviaci una recensione per aiutarci a migliorare!</p>
        <br>
        <form id = "review-form">
            <div class = "rating">
                <span class = "rating-label">Voto:</span>
                <i class = "rating__star fa fa-star-o"></i>
                <i class = "rating__star fa fa-star-o"></i>
                <i class = "rating__star fa fa-star-o"></i>
                <i class = "rating__star fa fa-star-o"></i>
                <i class = "rating__star fa fa-star-o"></i>
            </div>
            <br>
            <div class="form-group">
                <label for="review-content" id = "comment">Commento:</label>
                <textarea id="review-content" name="review-content" rows="10"></textarea>
            </div>
            <p class = "result-p"></p>
            <input id = "invio" type = "submit" value = "Invia recensione"></input>
        </form>
    </section>
    <div id = "view-reviews">
        <a id = "reviews-button">Visualizza tutte le recensioni</a>
        <br>
        <br>
        <br>
        <table id = "table">
            <th id = "th1" class = "hidden">UTENTE</th>
            <th id = "th2" class = "hidden">VOTO</th>
            <th id = "th3" class = "hidden">COMMENTO</th>
            <tbody id = "content-table"></tbody>
        </table>
    </div>
    <section id="contact">
        <div class="container-c">
            <div class = "container-child">
                <h3 class = "paragraph">Indirizzo</h3>
                <address class = "number">Corso Sicilia 123, Catania</address>
            </div>
            <div class = "container-child">
                <h3 class = "paragraph">Telefono</h3>
                <number class = "number">+123 456 7890</number>
            </div>
            <div class = "container-child">
                <h3 class = "paragraph">Email</h3>
                <mail class = "number">info@atavulainonna.com</mail>
            </div>
        </div>
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