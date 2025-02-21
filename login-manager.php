<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["mail"]) && !empty($_POST["mail"]) && isset($_POST["fpassword"]) && !empty($_POST["fpassword"])){

    $host = "localhost"; 
    $dbname = "miodatabase";  
    $username = "www";  
    $password = "1Nf4m303"; 

    $db=pg_connect("host=$host user=$username password=$password dbname=$dbname");
    if (!$db) {
       die("Connessione al database fallita: " . pg_last_error());
    }
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
            echo"<h3>Utente inesistente o password errata</h3>";
        }
    } else {
        echo "<h3>Errore nella query: " . pg_last_error($db) . "</h3>";
    }
    pg_close($db);
   }
   else{
    echo "<h3>errore nella richiesta</h3>";
   }
   ?>