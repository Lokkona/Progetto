<?php
  session_start();
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  $host = 'localhost'; // o l'indirizzo del server DB
  $username = 'www';  // il nome utente del DB
  $password = '1Nf4m303';      // la password del DB
  $dbname = 'miodatabase';  // il nome del database
  $error_message = ""; 
  $success_message = "";
  
  if($_SERVER["REQUEST_METHOD"] == "POST"){
   if(isset($_POST["name"]) && !empty($_POST["name"]) && isset($_POST["mail"]) && !empty($_POST["mail"]) && isset($_POST["uname"]) && !empty($_POST["uname"]) && isset($_POST["password"]) && !empty($_POST["password"]) && isset($_POST["fpassword"]) && !empty($_POST["fpassword"]) && isset($_POST["privacy"]) && !empty($_POST["privacy"])){
    $db=pg_connect("host=$host user=$username password=$password dbname=$dbname");
    if (!$db) {
       die("Connessione al database fallita: " . pg_last_error());
    }
    $name=$_POST["name"];
    $uname=$_POST["uname"];
    $mail=$_POST["mail"];
    $pass=$_POST["password"];
    $fpassword=$_POST["fpassword"];
    $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);
    $domain = substr(strrchr($mail, "@"), 1);
    $check_email_query = "SELECT * FROM utente WHERE mail = $1";
    pg_prepare($db, "check_email", $check_email_query);
    $check_email_result = pg_execute($db, "check_email", array($mail));
    $check_uname_query = "SELECT * FROM utente WHERE uname = $1";
    pg_prepare($db, "check_uname", $check_uname_query);
    $check_uname_result = pg_execute($db, "check_uname", array($uname));
    if (pg_num_rows($check_email_result) > 0) {
        $error_message .= "<div class=error>L'email inserita è già in uso. Utilizzane un'altra o effettua il <a href=Login.HTML>Login</a></div>";
    }
    else if (pg_num_rows($check_uname_result) > 0) {
        $error_message .= "<div class=error>Lo username inserito è già in uso. Scegline un altro.</div>";
    }
    else if ($pass!==$fpassword) {
        $error_message="<div class=error>Le password non coincidono</div>";
    }
    else if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $error_message= "<div class=error>Email non valida!</div>";
    }
    else if (!checkdnsrr($domain, "MX")) { // MX indica che il dominio ha un server di posta
        $error_message= "<div class=error>Email non valida!</div>";
    } 
    else {
        $query="INSERT INTO utente (nome, mail, uname, pass, fpass) VALUES ('$name', '$mail', '$uname', '$hashed_pass', '$fpassword')";
        $result=pg_query($db, $query);
        if($result){ 
            $mail=$_POST["mail"];
            $fpassword=$_POST["fpassword"];
            $query="SELECT id, pass FROM utente WHERE mail=$1";
            $result=pg_query_params($db, $query, array($mail));
            if($result){
              $row = pg_fetch_assoc($result);
                if ($row) {
                    if(password_verify($fpassword, $row["pass"])){
                        $_SESSION["user_id"] = $row["id"];
                        $_SESSION["user_mail"] = $mail;
                        $_SESSION['uname'] = $row['uname'];
                       header("Location: index.php");
                    }
                } else {
                    echo "<h3>Errore nella query: " . pg_last_error($db) . "</h3>";
                }                
        }
        else{
           $error_message="<h3 class=error> errore nell'inserimento dei dati.";
        }
    }
        pg_close($db);
  }
 }
}
?>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial scale=1.0">
    <style>
    body {
        background-image: url('sfondo.webp');
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
        margin: 0;
        font-family: 'Lato', sans-serif;
        color: whitesmoke;
        height: 200vh; 
        overflow-y: auto;
    }

    .sticky_form {
        position: sticky;
        top: 10px; 
        background-color: rgba(0, 0, 0, 0.8);
        padding: 30px;
        border-radius: 10px;
        width: 600px;
        margin: 20px auto;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.7);
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="submit"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
        border: 1px solid #ddd;
        font-size: 1em;
    }

    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        border: none;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background-color: #45a049;
    }

    .error {
        color: red;
        margin: 10px 0;
    }

    a {
        color: #4CAF50;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
    </style>
 </head>
  <body>
  <div class="sticky_form">
  <h1>Sport Stats Tracker</h1>
  <h2>Unisciti alla nostra community e rimani sempre aggiornato!</h2>

    <?php if (!empty($error_message)): ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php endif; ?>
    <?php if (!empty($success_message)): ?>
    <div class="error"><?php echo $success_message; ?></div>
    <?php endif; ?>
        <form method="POST" action="registrati.php" id="registration_form">
            <label for="name">Nome:</label><br>
            <input type="text" id="name" name="name" required><br>
            <label for="mail">E-mail:</label><br>
            <input type="email" id="mail" name="mail" required><br>
            <label for="uname">Username:</label><br>
            <input type="text" id="uname" name="uname" required><br>
            <label for="password">Password:</label><br>
            <div style="position: relative; display: flex; align-items: center;">
                <input type="password" id="password" name="password" required style="width: 100%; padding-right: 40px;">
                <span id="togglePassword" style="position: absolute; right: 10px; cursor: pointer;">
                    👁️
                </span>
            </div>
            <label for="fpassword">Ripeti Password:</label><br>
            <div style="position: relative; display: flex; align-items: center;">
                <input type="password" id="fpassword" name="fpassword" required style="width: 100%; padding-right: 40px;">
                <span id="toggleFPassword" style="position: absolute; right: 10px; cursor: pointer;">
                    👁️
                </span>
            </div>
            <p>Dai il tuo consenso per il trattamento dei dati:<br> 
                <input type="checkbox" id="privacy" name="privacy" required>
                <label for="privacy"><a href=https://protezionedatipersonali.it/informativa target="_blank">informativa sulla privacy</a></label>
            </p>
            <p>
                Acconsenti alla geolocalizzazione:<br> 
                <input type="checkbox" id="geolocation" name="geolocation">
                <label for="geolocation">Attiva la geolocalizzazione</label>
            </p>
                <input type="hidden" id="latitude" name="latitude">
                <input type="hidden" id="longitude" name="longitude">
                <input type="submit" value="Registrati">
        </form>
    </div>
    <script>
function validateForm(event) {
    // Previeni l'invio del form di default
    event.preventDefault();
    
    // Reset dei messaggi di errore
    clearErrors();
    
    // Recupero dei valori dei campi
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('mail').value.trim();
    const username = document.getElementById('uname').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('fpassword').value;
    const privacy = document.getElementById('privacy').checked;
    
    let isValid = true;
    
    // Validazione nome
    if (name.length < 2) {
        showError('name', 'Il nome deve contenere almeno 2 caratteri');
        isValid = false;
    }
    
    // Validazione email
    const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!emailPattern.test(email)) {
        showError('mail', 'Inserisci un indirizzo email valido');
        isValid = false;
    }
    
    // Validazione username
    if (username.length < 3) {
        showError('uname', 'Lo username deve contenere almeno 3 caratteri');
        isValid = false;
    }
    if (/\s/.test(username)) {
        showError('uname', 'Lo username non può contenere spazi');
        isValid = false;
    }
    
    // Validazione password
    if (password.length < 8) {
        showError('password', 'La password deve contenere almeno 8 caratteri');
        isValid = false;
    }
    if (!/[A-Z]/.test(password)) {
        showError('password', 'La password deve contenere almeno una lettera maiuscola');
        isValid = false;
    }
    if (!/[0-9]/.test(password)) {
        showError('password', 'La password deve contenere almeno un numero');
        isValid = false;
    }
    if (!/[!@#$%^&*]/.test(password)) {
        showError('password', 'La password deve contenere almeno un carattere speciale (!@#$%^&*)');
        isValid = false;
    }
    
    // Validazione conferma password
    if (password !== confirmPassword) {
        showError('fpassword', 'Le password non coincidono');
        isValid = false;
    }
    
    // Validazione privacy
    if (!privacy) {
        showError('privacy', 'Devi accettare l\'informativa sulla privacy');
        isValid = false;
    }
    
    // Se tutto è valido, invia il form
    if (isValid) {
        event.target.submit();
    }
}

// Funzione per mostrare gli errori
function showError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.style.color = 'red';
    errorDiv.style.fontSize = '0.8em';
    errorDiv.style.marginTop = '5px';
    errorDiv.textContent = message;
    
    field.parentNode.insertBefore(errorDiv, field.nextSibling);
    field.style.borderColor = 'red';
}

// Funzione per pulire gli errori
function clearErrors() {
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(error => error.remove());
    
    const fields = document.querySelectorAll('input');
    fields.forEach(field => field.style.borderColor = '');
}

// Funzione per la validazione in tempo reale
function setupLiveValidation() {
    const fields = ['name', 'mail', 'uname', 'password', 'fpassword'];
    
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        field.addEventListener('input', function() {
            // Rimuovi l'errore specifico per questo campo
            const errorMessage = this.nextSibling;
            if (errorMessage && errorMessage.className === 'error-message') {
                errorMessage.remove();
            }
            this.style.borderColor = '';
        });
    });
}

// Gestione della geolocalizzazione
document.getElementById('geolocation').addEventListener('change', function() {
    if (this.checked) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    document.getElementById('latitude').value = position.coords.latitude;
                    document.getElementById('longitude').value = position.coords.longitude;
                },
                function(error) {
                    alert('Errore nella geolocalizzazione: ' + error.message);
                    document.getElementById('geolocation').checked = false;
                }
            );
        } else {
            alert('Il tuo browser non supporta la geolocalizzazione.');
            document.getElementById('geolocation').checked = false;
        }
    }
});

// Toggle visibilità password
document.getElementById('togglePassword').addEventListener('click', function() {
    let passwordField = document.getElementById('password');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        this.innerText = '🙈';
    } else {
        passwordField.type = 'password';
        this.innerText = '👁️';
    }
});

document.getElementById('toggleFPassword').addEventListener('click', function() {
    let passwordField = document.getElementById('fpassword');
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        this.innerText = '🙈';
    } else {
        passwordField.type = 'password';
        this.innerText = '👁️';
    }
});

// Inizializzazione
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registration_form');
    form.addEventListener('submit', validateForm);
    setupLiveValidation();
});
    </script>
    </body>
</html>

    