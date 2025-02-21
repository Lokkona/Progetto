<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inserisci_dati</title>
    <style>
        body{
            width=100%;
        }
        .hide{
            display: none;
        }
        .error {
            color: red;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid red;
            background-color: #ffe6e6;
        }

        .success {
            color: green;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid green;
            background-color: #e6ffe6;
        }
        .topnav{
            overflow:hidden;
            background-color:black;
            width: 100%;
            height: 70px;
            padding: 20px;
            display: block;
            margin: 0px;
        }
        .topnav a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            border-right: solid white 1px;
        }
        .topnav a:hover{
            color: black;
            background-color: grey;
        }      
    </style>
</head>
<body>
    <div class="topnav">
        <a href="homepage.html">NOME SITO</a>
        <a href="inserisci_dati_form.php">AGGIUNGI PRESTAZIONE</a>
        <a href="storico.html">STORICO</a>
        <a href="statistiche.html">STATISTICHE</a>
    </div>
    <h1>Inserici i tuoi dati prestazione</h1>
    <?php
    if (isset($_SESSION['success'])) {
        echo $_SESSION['success'];
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo $_SESSION['error'];
        unset($_SESSION['error']);
    }
    ?> 
    <form action="inserisci_dati.php" method="post"> 
        <div>
        <label for="sport">Seleziona lo sport</label>
        <select name="sport" id="sport" onchange="mostraSport()" required>
            <option value="">-Sport-</option>
            <option value="basket">Basket</option>
            <option value="calcio">Calcio</option>
            <option value="tennis">Tennis</option>
        </select>
        <label for="data">Data </label>
        <input type="date" id="data" name="data" required>
        </div>
        <div id="formBasket" class="formSport hide">
            <div>
                <h2>Partita</h2>
                <label for="minuti_basket">Minuti giocati</label>
                <input type="number" id="minuti_basket" name="minuti_basket" min="0" required><br>
                <h2>Punti</h2>
                <label for="punti_basket">Totale</label>
                <input type="number" name="punti_basket" id="punti_basket" min="0" required>
                <h3>Tiri</h3>
                <label for="tiri_t">Tentati</label>
                <input type="number" id="tiri_t" name="tiri_t" min="0" required>
                <label for="makes">Reaizzati</label>
                <input type="number" name="makes" id="makes" min="0" required>
                <h3>Tiri da 3</h3>
                <label for="tiri3">Tentati</label>
                <input type="text" name="tiri3" id="tiri3" min="0" required>
                <label for="makes3">Realizzati</label>
                <input type="number" name="makes3" id="makes3" min="0" required>
                <h3>Tiri liberi</h3>
                <label for="fta">Tentati</label>
                <input type="number" id="fta" name="fta" min="0" required>
                <label for="ft">Realizzati</label>
                <input type="number" name="ft" id="ft" min="0" required>
                <h2>Rimbalzi</h2>
                <label for="rimbalzi">Totale</label>
                <input type="number" id="rimbalzi" name="rimbalzi" min="0" required> 
                <label for="rimbalzi_offensivi">Offensivi</label>
                <input type="number" name="rimbalzi_offensivi" id="rimbalzi_offensivi" min="0" required>
                <label for="rimbalzi_difensivi">Difensivi</label>
                <input type="number" name="rimbalzi_difensivi" id="rimbalzi_difensivi" min="0" required><br>
                <h2>Altro</h2>
                <label for="assist_basket">Assist</label>
                <input type="number" id="assist_basket" name="assist_basket" min="0" required>
                <label for="stoppate">Stoppate</label>
                <input type="number" id="stoppate" name="stoppate" min="0" required>
                <label for="rubate">Palle rubate</label>
                <input type="number" id="rubate" name="rubate" min="0" required>
                <label for="to">Palle perse</label>
                <input type="number" id="to" name="to" min="0" required>
            </div>
        </div>
        <div id="formCalcio" class="formSport hide">
            <div>
                <h2>Partita</h2>
                <label for="minuti_calcio">Minuti giocati</label>
                <input type="number" id="minuti_calcio" name="minuti_calcio" min="0" required><br>
                <h2>Attacco</h2>
                <label for="gol">Gol segnati</label>
                <input type="number" name="gol" id="gol" min="0" required>
                <label for="tiri">Tiri</label>
                <input type="number" id="tiri" name="tiri" min="0" required>
                <label for="tiri_in_porta">Tiri in porta</label>
                <input type="number" name="tiri_in_porta" id="tiri_in_porta" min="0" required><br>
                <h2>Passaggi</h2>
                <label for="assist_calcio">Assist</label>
                <input type="number" name="assist_calcio" id="assist_calcio" min="0" required>
                <label for="passaggi_tentati">Passaggi tentati</label>
                <input type="number" name="passaggi_tentati" id="passaggi_tentati" min="0" required>
                <label for="passaggi_riusciti">Passaggi riusciti</label>
                <input type="number" name="passaggi_riusciti" id="passaggi_riusciti" min="0" required><br>
                <h2>Difesa</h2>
                <label for="intercetti">Intercetti</label>
                <input type="number" id="intercetti" name="intercetti" min="0" required>
                <label for="contrasti">Contrasti vinti</label>
                <input type="number" id="contrasti" name="contrasti" min="0" required>
                <label for="palle_recuperate">Palle recuperate</label>
                <input type="number" id="palle_recuperate" name="palle_recuperate" min="0" required>
                <h2>Altro</h2>
                <h3>Dribbling:</h3>
                <label for="dribbling_tentati">Tentati</label>
                <input type="number" name="dribbling_tentati" id="dribbling_tentati" min="0" required>
                <label for="dribbling_riusciti">Riusciti</label>
                <input type="number" id="dribbling_riusciti" name="dribbling_riusciti" min="0" required>
            </div>
        </div>
        <div id="formTennis" class="formSport hide">
            <div>
                <h2>Partita</h2>
                <label for="tempo">Tempo di gioco</label>
                <input type="number" id="tempo" name="tempo" min="0" required>
                <h3>Punti:</h3>
                <label for="punti_giocati">Giocati</label>
                <input type="number" id="punti_giocati" name="punti_giocati" min="0" required>
                <label for="punti_vinti">Vinti</label>
                <input type="number" name="punti_vinti" id="punti_vinti" min="0" required><br>
                <h2>Servizio</h2>
                <h3>Prime palle:</h3>
                <label for="prima_g">Giocate</label>
                <input type="number" name="prima_g" id="prima_g" min="0" required>
                <label for="prima_r">In campo</label>
                <input type="number" name="prima_r" id="prima_r" min="0" required>
                <label for="prima_v">Vinte</label>
                <input type="number" name="prima_v" id="prima_v" min="0" required><br>
                <h3>Seconde palle:</h3>
                <label for="seconda_r">In campo</label>
                <input type="number" name="seconda_r" id="seconda_r" min="0" required>
                <label for="seconda_v">Vinte</label>
                <input type="number" name="seconda_v" id="seconda_v" min="0" required>
                <label for="doppio_fallo">Doppi falli</label>
                <input type="number" name="doppio_fallo" id="doppio_fallo" min="0" required><br>
                <h2>Risposta</h2>
                <label for="risposta_g">Punti in risposta giocati</label>
                <input type="number" name="risposta_g" id="risposta_g" min="0" required>
                <label for="risposta_v">Punti vinti in risposta</label>
                <input type="number" name="risposta_v" id="risposta_v" min="0" required>
                <label for="break">Punti break avuti</label>
                <input type="number" name="break" id="break" min="0" required>
                <label for="break_v">Punti break convertiti</label>
                <input type="number" name="break_v" id="break_v" min="0" required><br>
                <h2>Altro</h2>
                <label for="errori">Errori non forzati</label>
                <input type="number" name="errori" id="errori" min="0" required>
            </div>
        </div>
        <div>
        <button type="submit" id="invio">Invia</button>
        </div>
    </form>

    <script>
        function mostraSport(){
            const formSport=document.querySelectorAll('.formSport');
            formSport.forEach(form => {
                 form.classList.add('hide');
                 form.querySelectorAll('input,select').forEach(input => input.disabled=true);
            })
            const sport=document.getElementById('sport').value;
            document.getElementById('invio').style.display='none'
            if(sport){
                const selectedForm = document.getElementById('form' + sport.charAt(0).toUpperCase() + sport.slice(1));
                selectedForm.classList.remove('hide');
                selectedForm.querySelectorAll('input, select').forEach(input => input.disabled = false);
                document.getElementById('invio').style.display = 'block';   
            }
        }
    </script>
</body>
</html>