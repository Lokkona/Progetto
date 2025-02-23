<?php
session_start();
header("Content-Type:application/json");

if(!isset($_SESSION['user_id'])){
    echo json_encode(["error"=>"Utente non autenticato"]);
    exit;
}

$data=json_decode(file_get_contents('php://input'), true);
$sport=$data['sport']??'';
$dataInizio=$data['dataInizio']??'';
$dataFine=$data['dataFine']??'';

$host="localhost";
$dbname="gruppo21";
$user="www";
$password="tw2024";
$connection_string="host=$host dbname=$dbname user=$user password=$password";

$db=pg_connect($connection_string);
if(!$db){
    echo json_encode(["error"=>"Connessione al database fallita"]);
    exit;
}

$user_id=$_SESSION['user_id'];

$params=[$user_id, $sport];
$query="SELECT id, sport, data, created_at FROM prestazioni WHERE user_id = $1 AND sport = $2";

if ($dataInizio && $dataFine) {
    $query .= " AND data BETWEEN $3 AND $4";
    $params[] = $dataInizio;
    $params[] = $dataFine;
}

$query .= " ORDER BY data ASC";

$result = pg_query_params($db, $query, $params);
if (!$result) {
    echo json_encode(["error" => "Errore nella query: " . pg_last_error($db)]);
    exit;
}

$prestazioni = pg_fetch_all($result);

foreach ($prestazioni as &$p) {
    $prestazione_id = $p['id'];
    
    $dettagliQuery = match($sport) {
        "calcio" => "SELECT minuti, gol, tiri, tiri_porta, assist, passaggi_tentati, passaggi_riusciti, 
                     intercetti, contrasti, palle_recuperate, dribbling_tentati, dribbling_riusciti 
                     FROM stats_calcio WHERE prestazione_id = $1",
        "basket" => "SELECT minuti, punti, tiri_tentati, tiri_realizzati, tiri3_tentati, tiri3_realizzati,
                     tiri_liberi_tentati, tiri_liberi_realizzati, rimbalzi_totali, rimbalzi_offensivi,
                     rimbalzi_difensivi, assist, stoppate, palle_rubate, palle_perse 
                     FROM stats_basket WHERE prestazione_id = $1",
        "tennis" => "SELECT tempo, punti_giocati, punti_vinti, prima_giocate, prima_campo, prima_vinte,
                     seconda_campo, seconda_vinte, doppi_falli, risposta_giocati, risposta_vinti,
                     break_punti, break_convertiti, errori 
                     FROM stats_tennis WHERE prestazione_id = $1",
        default => ""
    };

    if ($dettagliQuery) {
        $stmtDettagli = pg_query_params($db, $dettagliQuery, [$prestazione_id]);
        if ($stmtDettagli) {
            $p['dettagli'] = pg_fetch_assoc($stmtDettagli);
        }
    }
}

echo json_encode($prestazioni);
pg_close($db);
?>