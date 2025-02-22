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
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Statistiche Medie</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <style>
         .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .stats-container {
            margin-top: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        .chart-container {
            margin-top: 20px;
            height: 400px;
        }
        select, input {
            padding: 8px;
            margin: 10px 0;
        }
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
                <a href="Login.html">Login</a>
                <a href="registrati.php">Registrati</a>
            <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="container">
        <h1> Le tue statistiche sportive</h1>

        <select id="sportSelect">
            <option value="">Seleziona uno sport</option>
            <option value="calcio">Calcio</option>
            <option value="basket">Basket</option>
            <option value="tennis">Tennis</option>
        </select>
        <input type="date" id="dataInizio">
        <input type="date" id="dataFine">
        <button onclick="caricaStatistiche()">Aggiorna Statistiche</button>
        <div class="stats-container" id="statsContainer"></div>
        <div class="chart-container">
            <canvas id="statsChart"></canvas>
        </div>
    </div>
    <script>
    let chart=null;

    async function caricaStatistiche(){
        const sport= document.getElementById('sportSelect').value;
        const dataInizio= document.getElementById('dataInizio').value;
        const dataFine= document.getElementById('dataFine').value;
        try{
            const response= await fetch('statistiche.php',{
                method: 'POST',
                headers: {
                    'Content-Type':'application/json',
                },
                body: JSON.stringify({
                    sport: sport,
                    dataInizio: dataInizio,
                    dataFine: dataFine
                })

            });
            const data= await response.json();
            if(data.error){
                if(data.error=="Utente non autenticato"){
                    window.location.href='login.html';
                    return;
                }
                alert(data.error);
                return;
            }
            visualizzaStatistiche(data, sport);
            creaGrafico(data, sport);
        } catch(error){
            console.error('Errore:', error);
            alert('Si è verificato un errore nel caricamento delle statistiche');
        }
    }
    function visualizzaStatistiche(data, sport){
        const container=document.getElementById('statsContainer');
        container.innerHTML='';
        
        const stats= calcolaMedie(data, sport);
        
        Object.entries(stats).forEach(([key, value])=>{
            const div=document.createElement('div');
            div.innerHTML=`<strong>${formatKey(key)}:</strong> ${value.toFixed(2)}`;
            container.appendChild(div);
        });
    }
    function calcolaMedie(data, sport){
        const stats={};
        data.forEach(prestazione=>{
            const dettagli= prestazione.dettagli;
            if(!dettagli) return;

            Object.entries(dettagli).forEach(([key, value])=>{
                if(!stats[key]) stats[key]=[];
                stats[key].push(Number(value));
            });
        });
        Object.keys(stats).forEach(key=>{
            stats[key]=stats[key].reduce((a, b)=> a+b, 0)/ stats[key].length;
        });
        return stats;
    }
    function creaGrafico(data, sport){
        if(chart){
            chart.destroy();
        }
        const ctx=document.getElementById('statsChart').getContext('2d');
        const dates=data.map(p=>p.data);
        const datasets=[];

        if(sport==='calcio'){
            datasets.push({
                label:'Gol',
                data: data.map(p=>p.dettagli?.gol || 0),
                borderColor: 'rgb(75, 192, 192)',
            });
        }else if(sport==='basket'){
            datasets.push({
                label:'Punti',
                data: data.map(p=>p.dettagli?.punti || 0),
                borderColor: 'rgb(255, 99, 132)',
            });
        } else if(sport=== 'tennis'){
            datasets.push({
                label: 'Punti Vinti',
                data: data.map(p=>p.dettagli?.punti_vinti || 0),
                borderColor: 'rgb(54, 162, 235)',
            });
        }
        chart= new Chart(ctx, {
            type:'line',
            data: {
                labels: dates,
                datasets: datasets
            },
            options:{
                responsive: true,
                scales: {
                    y: {
                        beginAtZero:true
                    }
                }
            }
        });
    }
    function formatKey(key){
        return key.replace(/_/g, '').replace(/\b\w/g, l=> l.toUpperCase());
    }
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