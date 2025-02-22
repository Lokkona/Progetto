<?php
session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$uname = $isLoggedIn ? $_SESSION['uname'] : '';
?>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial scale=1.0">
    <link rel="stylesheet" href="index.css">

<body>
    <div class="header">NOME SITO</div>
    <div class="topnav">
        <div class="row1">
            <a href="index.php">HOME</a>
            <a href="inserisci_dati_form.php">AGGIUNGI PRESTAZIONE</a>
            <a href="storico.php">STORICO</a>
            <a href="statistiche_html.php">STATISTICHE</a>
        </div>
        <div class="row2">
            <img src="immagine-utente.jpg" alt="Immagine Utente" onclick="toggleMenu()">
            <div class="dropdown-menu" id="dropdownMenu">
            <?php if ($isLoggedIn): ?>
                <p>Ciao, <?php echo htmlspecialchars($uname); ?>!</p>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="Login.html">Login</a>
                <a href="registrati.php">Registrati</a>
            <?php endif; ?>
            </div>
        </div>

        </div>
        <div class="welcome-section">
        <div class="welcome-text">
            <h1>Benvenuto nel tuo Tracker di Statistiche Sportive</h1>
            <?php if ($isLoggedIn): ?>
                <p class="user-greeting">Bentornato, <?php echo htmlspecialchars($uname); ?>!</p>
            <?php endif; ?>
            <p>Tieni traccia delle tue prestazioni sportive, analizza i tuoi progressi e migliora il tuo gioco.</p>
        </div>
        </div>

        <div class="features-grid">
        <div class="feature-card">
            <h3>Inserisci Prestazione</h3>
            <p>Registra i dati delle tue partite di basket, calcio o tennis. Mantieni uno storico completo delle tue performance.</p>
            <a href="inserisci_dati_form.php">Aggiungi Prestazione</a>
        </div>

        <div class="feature-card">
            <h3>Visualizza Storico</h3>
            <p>Consulta tutte le tue prestazioni passate. Analizza i tuoi risultati e monitora i tuoi progressi nel tempo.</p>
            <a href="storico.php">Vedi Storico</a>
        </div>

        <div class="feature-card">
            <h3>Statistiche</h3>
            <p>Analizza le tue statistiche con grafici dettagliati. Confronta diverse metriche e scopri i tuoi punti di forza.</p>
            <a href="statistiche_html.php">Vedi Statistiche</a>
        </div>
        </div>

        <div class="footer"></div>
    
    <script>
    function toggleMenu(){
            const menu = document.getElementById("dropdownMenu");
            menu.style.display = (menu.style.display === "block") ? "none" : "block";
            document.addEventListener("click", function closeMenu(event) {
                if (!event.target.closest('.row2')) {
                    menu.style.display = "none";
                    document.removeEventListener("click", closeMenu);
                }
            });
        }
    </script>
</body>
</html>