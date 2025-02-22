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
            <input type="password" id="password" name="password" placeholder="min 6 acratteri" required><br>
            <label for="fpassword">Ripeti Password:</label><br>
            <input type="password" id="fpassword" name="fpassword" required><br>
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
    document.getElementById('registration_form').onsubmit = function(event) {
        var email = document.getElementById('mail').value;
        var pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

        if (!pattern.test(email)) {
            alert('Email non valida!');
            event.preventDefault();
        }
    };
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
                    document.getElementById('geolocation').checked = false; // Deseleziona se fallisce
                }
            );
        } else {
            alert('Il tuo browser non supporta la geolocalizzazione.');
            document.getElementById('geolocation').checked = false;
        }
    }
});
    </script>
    </body>
</html>

    