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
    <style>
    .flex-container {
        display: flex;
        flex-direction: row;
        margin: 0;
        }
/* Stile della barra di navigazione */
    .topnav {
        display: flex; /* Layout flessibile */
        align-items: center; /* Allinea verticalmente */
        justify-content: space-between; /* Spaziatura tra link e immagine */
        background-color: black;
        padding: 10px 20px;
    }

    /* Stile dei link nella nav */
    .row1 {
        display: flex;
        gap: 10px;
    }

    .row1 a {
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        border-right: solid white 1px;
    }

    .row1 a:hover {
        color: black;
        background-color: grey;
    }

    /* Stile immagine utente */
    .row2 img {
        width: 50px;
        height: 50px;
        border-radius: 50%; /* Immagine rotonda */
        object-fit: cover;
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        right: 0;
        top: 60px;
        background-color: white;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        padding: 10px 0;
        z-index: 10;
        width: 180px;
    }

    /* Stile delle voci del menu */
    .dropdown-menu a {
        display: block;
        padding: 10px 20px;
        color: black;
        text-decoration: none;
        transition: background-color 0.3s;
    }

    .dropdown-menu a:hover {
        background-color: #f0f0f0;
    }
</style>

<body>
    <div class="topnav">
        <div class="row1">
            <a href="index.php">NOME SITO</a>
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
                <a href="login.php">Login</a>
                <a href="registrati.php">Registrati</a>
            <?php endif; ?>
            </div>
        </div>
    </div>
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