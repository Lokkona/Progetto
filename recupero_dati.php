
<?php
session_start();
header("Content-Type: application/json");
// Connessione al database
$host = "localhost";
$dbname = "miodatabase";
$user = "www";
$password = "1Nf4m303";

$connection_string = "host=$host dbname=$dbname user=$user password=$password";

// Connessione al database con pg_connect
$db = pg_connect($connection_string);

if (!$db) {
    echo json_encode(["error" => "Connessione al database fallita"]);
    exit;
}

// Usa $_SESSION invece di $_GET per l'utente loggato
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "Utente non autenticato"]);
    exit;
}

$user_id = $_SESSION['user_id'];

    // Query per ottenere tutte le prestazioni inserite dall'utente
    $query = "SELECT id, sport, data, created_at FROM prestazioni WHERE user_id = $1 ORDER BY created_at DESC";
    $result = pg_query_params($db, $query, [$user_id]);

    if (!$result) {
        echo json_encode(["error" => "Errore nella query: " . pg_last_error($db)]);
        exit;
    }
    
    $prestazioni = pg_fetch_all($result);
    
    foreach ($prestazioni as &$p) {
        $sport = $p['sport'];
        $prestazione_id = $p['id'];

        // Query dinamica per recuperare i dettagli in base allo sport
        $dettagliQuery = "";
        switch ($sport) {
            case "calcio":
                $dettagliQuery = "SELECT minuti, gol, tiri, tiri_porta, assist, passaggi_tentati, passaggi_riusciti, intercetti, contrasti, palle_recuperate, dribbling_tentati, dribbling_riusciti FROM stats_calcio WHERE prestazione_id = $1";
                break;
            case "basket":
                $dettagliQuery = "SELECT minuti, punti, tiri_tentati, tiri_realizzati, tiri3_tentati, tiri3_realizzati, tiri_liberi_tentati, tiri_liberi_realizzati, rimbalzi_totali, rimbalzi_offensivi, rimbalzi_difensivi, assist, stoppate, palle_rubate, palle_perse FROM stats_basket WHERE prestazione_id = $1";
                break;
            case "tennis":
                $dettagliQuery = "SELECT tempo, punti_giocati, punti_vinti, prima_giocate, prima_campo, prima_vinte, seconda_campo, seconda_vinte, doppi_falli, risposta_giocati, risposta_vinti, break_punti, break_convertiti, errori FROM stats_tennis WHERE prestazione_id = $1";
                break;
        }

        if ($dettagliQuery) {
            $stmtDettagli = pg_query_params($db, $dettagliQuery, [$prestazione_id]);
            $dettagli = pg_fetch_assoc($stmtDettagli);
            $p['dettagli'] = $dettagli;
        }
        
        if ($dettagliQuery) {
            $stmtDettagli = pg_query_params($db, $dettagliQuery, [$prestazione_id]);
            if ($stmtDettagli) {
                $p['dettagli'] = pg_fetch_assoc($stmtDettagli);
            } else {
                $p['dettagli'] = null;
            }
        }
    }
    
    echo json_encode($prestazioni);
    
    // Chiudi la connessione al database
    pg_close($db);
    ?>