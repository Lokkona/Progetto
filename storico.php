
<?php
session_start();
//Redirect se l'utente non è loggato
$isLoggedIn = isset($_SESSION['user_id']);
$uname = $isLoggedIn ? $_SESSION['uname'] : '';
if(!$isLoggedIn){
    header("Location: Login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Storico Prestazioni</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        select { margin-bottom: 10px; padding: 5px; }
        .flex-container {
        display: flex;
        flex-direction: row;
        margin: 0;
        }
        body {
        background: url('sfondo.webp') no-repeat center center fixed;
        background-size: cover;
        }
/* Stile della barra di navigazione */
    .topnav {
        display: flex; /* Layout flessibile */
        align-items: center; /* Allinea verticalmente */
        justify-content: space-between; /* Spaziatura tra link e immagine */
        background-color: black;
        padding: 10px 20px;
        font-family:'Lato', sans-serif;
    }

    /* Stile dei link nella nav */
    .row1 {
        display: flex;
        gap: 10px;
        font-family:'Lato', sans-serif;
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
    .style{
        font-family:'Lato', sans-serif;
        background-color:black;
        color:whitesmoke;
    }
    .style1{
        color:black;
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
                <a href="Login.html">Login</a>
                <a href="registrati.php">Registrati</a>
            <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="style">
    <h2>Storico Prestazioni Sportive</h2>
    <label for="sportFilter">Filtra per: Sport</label>
    <select id="sportFilter">
        <option value="tutti">Tutti</option>
        <option value="calcio">Calcio</option>
        <option value="basket">Basket</option>
        <option value="tennis">Tennis</option>
    </select>
    <label for="dateFilter">Data</label>
    <input type="date" id="dateFilter">
    <table id="prestazioniTable">
        <thead class="style1">
            <tr>
                <th>Data Evento</th>
                <th>Sport</th>
                <th>Momento Inserimento</th>
                <th>Dettagli</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
    </div>
    <script>
         document.addEventListener("DOMContentLoaded", function () {
            const sportFilter = document.getElementById("sportFilter"); // Select per il filtro
            const dateFilter = document.getElementById("dateFilter");
            const tableBody = document.querySelector("#prestazioniTable tbody");
            let prestazioniData = []; // Memorizza i dati delle prestazioni

            function fetchData() {
                fetch("recupero_dati.php")
                .then(response => response.json())
                .then(data => {
                    prestazioniData = data; // Salviamo i dati originali
                    updateTable(); // Popoliamo la tabella con tutti i dati
                })
                .catch(error => console.error("Errore nel recupero dati:", error));
            }

            function updateTable() {
                const selectedSport = sportFilter.value; // Sport selezionato
                const selectedDate = dateFilter.value;
                tableBody.innerHTML = ""; // Pulisce la tabella

                prestazioniData.forEach(row => {
                    const rowDate = row.data; // Assumendo che row.data sia nel formato YYYY-MM-DD
                if ((selectedSport === "tutti" || row.sport === selectedSport) &&
                    (selectedDate === "" || rowDate === selectedDate)) {
                    let dettagli = "";


                    // Genera la descrizione dettagliata in base allo sport
                    if (row.dettagli) {
                        if (row.sport === "calcio") {
                                dettagli = `Minuti giocati: ${row.dettagli.minuti}| Gol: ${row.dettagli.gol}| Tiri : ${row.dettagli.tiri}| Tiri in porta: ${row.dettagli.tiri_porta}| Assist: ${row.dettagli.assist}| Passaggi tentati: ${row.dettagli.passaggi_tentati}| Passaggi riusciti: ${row.dettagli.passaggi_riusciti}| Intercetti: ${row.dettagli.intercetti}| Contrasti: ${row.dettagli.contrasti}| Palle recuperate: ${row.dettagli.palle_recuperate}| Dribbling tentati: ${row.dettagli.dribbling_tentati}| Dribbling riusciti: ${row.dettagli.dribbling_riusciti}`;
                        } else if (row.sport === "basket") {
                                dettagli = `Minuti giocati: ${row.dettagli.minuti}| Punti : ${row.dettagli.punti}| Tiri tentati : ${row.dettagli.tiri_tentati}| Tiri realizzati : ${row.dettagli.tiri_realizzati}| Tiri da 3 tentati: ${row.dettagli.tiri3_tentati}| Tiri da 3 realizzati: ${row.dettagli.tiri3_realizzati}| Tiri liberi tentati: ${row.dettagli.tiri_liberi_tentati}| Tiri liberi realizzati: ${row.dettagli.tiri_liberi_realizzati}| Rimbalzi totali: ${row.dettagli.rimbalzi_totali}| Rimbalzi offensivi: ${row.dettagli.rimbalzi_offensivi}| Rimbalzi difensivi: ${row.dettagli.rimbalzi_difensivi}| Assist: ${row.dettagli.assist}| Stoppate: ${row.dettagli.stoppate}| Palle rubate: ${row.dettagli.palle_rubate}| Palle perse: ${row.dettagli.palle_perse}`;
                        } else if(row.sport === "tennis") {
                                dettagli = `Minuti giocati: ${row.dettagli.tempo}| Punti giocati : ${row.dettagli.punti_giocati}| Punti vinti : ${row.dettagli.punti_vinti}| Prima giocate : ${row.dettagli.prima_giocate}| Prima campo : ${row.dettagli.prima_campo}| Prima vinte : ${row.dettagli.prima_vinte}| Seconda campo : ${row.dettagli.seconda_campo}| Seconda vinte : ${row.dettagli.seconda_vinte}| Doppi falli : ${row.dettagli.doppi_falli}| Risposte giocate : ${row.dettagli.risposta_giocati}| Risposte vinte : ${row.dettagli.risposta_vinti}| Break punti : ${row.dettagli.break_punti}| Break convertiti : ${row.dettagli.break_convertiti}| Errori : ${row.dettagli.errori}`;
                        }
                    }
                    let tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${rowDate}</td>
                        <td>${row.sport}</td>
                        <td>${row.created_at}</td>
                        <td>${dettagli}</td>
                    `;
                    tableBody.appendChild(tr);
                }
            });
        }
        sportFilter.addEventListener("change", updateTable); 
        dateFilter.addEventListener("input", updateTable);
        fetchData();
    });

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