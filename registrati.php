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
                       header("Location: homepage.html");
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
        .style4{
            font-family:'Lato', sans-serif;
            color: whitesmoke;
            background-color: black;
            position:absolute;
            margin-left:200px;
            margin-right:200px;
            margin-top:70px;
            padding-left:50px;
            width: 1050px;
            height:500px;
        }
        .error{
            color:red;
            font-family:'Lato', sans-serif;
            position:absolute;
            padding-top:150px;
            padding-left:600px;
            z-index:1000;
        }
        body{
            background-image: src="sfondo.jpeg" alt="Wallpaper" style="width:250px;height:200px;";
            background-image: url('sfondo.WEBP'); 
            background-size: cover; 
            background-repeat: no-repeat; 
            background-position: center;
            margin: 0;
            height: 100vh;
        }
    </style>
 </head>
  <body>
  <?php if (!empty($error_message)): ?>
    <p><?php echo $error_message; ?></p>
<?php endif; ?>
<?php if (!empty($success_message)): ?>
    <p><?php echo $success_message; ?></p>
<?php endif; ?>
    <form method="POST" action="registrati.php" id="registration_form">
    <fieldset class="style4">
        <h1 style=text-align:center>"Nome Sito"</h1>
        <h2 style=float:right;padding-right:100px;padding-top:100px>Unisciti alla nostra community<br>e rimani sempre aggiornato!</h2>
        <h2>Registrati</h2>
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
        <input type="submit" value="Registrati">
    </fieldset>
    </form>
    <script>
    document.getElementById('registration_form').onsubmit = function(event) {
        var email = document.getElementById('mail').value;
        var pattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;

        if (!pattern.test(email)) {
            alert('Email non valida!');
            event.preventDefault();
        }
    };
    </script>
    </body>
</html>

    