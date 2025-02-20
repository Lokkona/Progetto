<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: Login.html");
    exit();
}
 
  $connection_string="host=localhost port=5432 dbname=miodatabase user=www password=1Nf4m303";

  $db=pg_connect($connection_string) or die('Errore nella connessione al database' . pg_last_error());

$sport =isset($_POST['sport']) ? pg_escape_string($_POST['sport']) : null;
$data =isset($_POST['data']) ? pg_escape_string($_POST['data']) : null;
$user_id = $_SESSION['user_id'] ?? null;

if (!$sport || !$data || !$user_id) {
    die("Errore: Sport, data o utente mancante.");
}

pg_query($db,"BEGIN");

try{
    $query = "INSERT INTO prestazioni (user_id, sport, data) VALUES ($1, $2, $3) RETURNING id";
    $result = pg_query_params($db, $query, [$user_id, $sport, $data]);
    $row = pg_fetch_row($result);
    $prestazione_id = $row[0];
    switch($sport) {
        case 'basket':
            $query = "INSERT INTO stats_basket (
                prestazione_id, minuti, punti, tiri_tentati, tiri_realizzati,tiri3_tentati, tiri3_realizzati, tiri_liberi_tentati, tiri_liberi_realizzati,
                rimbalzi_totali, rimbalzi_offensivi, rimbalzi_difensivi,
                assist, stoppate, palle_rubate, palle_perse
            ) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15, $16)";
                 $result = pg_query_params($db, $query, [
                    $prestazione_id,
                    intval($_POST['minuti_basket'] ?? 0),
                    intval($_POST['punti_basket'] ?? 0),
                    intval($_POST['tiri_t'] ?? 0),
                    intval($_POST['makes'] ?? 0),
                    intval($_POST['tiri3'] ?? 0),
                    intval($_POST['makes3'] ?? 0),
                    intval($_POST['fta'] ?? 0),
                    intval($_POST['ft'] ?? 0),
                    intval($_POST['rimbalzi'] ?? 0),
                    intval($_POST['rimbalzi_offensivi'] ?? 0),
                    intval($_POST['rimbalzi_difensivi'] ?? 0),
                    intval($_POST['assist_basket'] ?? 0),
                    intval($_POST['stoppate'] ?? 0),
                    intval($_POST['rubate'] ?? 0),
                    intval($_POST['to'] ?? 0)
                ]);
                break;

        case 'calcio':
            $query = "INSERT INTO stats_calcio (
                prestazione_id, minuti, gol, tiri, tiri_porta,
                assist, passaggi_tentati, passaggi_riusciti,
                intercetti, contrasti, palle_recuperate,
                dribbling_tentati, dribbling_riusciti
            ) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13)";
                    
                 $result = pg_query_params($db, $query, [
                    $prestazione_id,
                    intval($_POST['minuti_calcio'] ?? 0),
                    intval($_POST['gol'] ?? 0),
                    intval($_POST['tiri'] ?? 0),
                    intval($_POST['tiri_in_porta'] ?? 0),
                    intval($_POST['assist_calcio'] ?? 0),
                    intval($_POST['passaggi_tentati'] ?? 0),
                    intval($_POST['passaggi_riusciti'] ?? 0),
                    intval($_POST['intercetti'] ?? 0),
                    intval($_POST['contrasti'] ?? 0),
                    intval($_POST['palle_recuperate'] ?? 0),
                    intval($_POST['dribbling_tentati'] ?? 0),
                    intval($_POST['dribbling_riusciti'] ?? 0)
                ]);
                break;

        case 'tennis':
            $query = "INSERT INTO stats_tennis (
                prestazione_id, tempo, punti_giocati, punti_vinti,
                prima_giocate, prima_campo, prima_vinte,
                seconda_campo, seconda_vinte, doppi_falli,
                risposta_giocati, risposta_vinti,
                break_punti, break_convertiti, errori
            ) VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11, $12, $13, $14, $15)";
                        
                 $result = pg_query_params($db, $query, [
                    $prestazione_id,
                    intval($_POST['tempo'] ?? 0),
                    intval($_POST['punti_giocati'] ?? 0),
                    intval($_POST['punti_vinti'] ?? 0),
                    intval($_POST['prima_g'] ?? 0),
                    intval($_POST['prima_r'] ?? 0),
                    intval($_POST['prima_v'] ?? 0),
                    intval($_POST['seconda_r'] ?? 0),
                    intval($_POST['seconda_v'] ?? 0),
                    intval($_POST['doppio_fallo'] ?? 0),
                    intval($_POST['risposta_g'] ?? 0),
                    intval($_POST['risposta_v'] ?? 0),
                    intval($_POST['break'] ?? 0),
                    intval($_POST['break_v'] ?? 0),
                    intval($_POST['errori'] ?? 0)
                ]);
                break;
    }

    if(!$result){
        throw new Exception(pg_last_error($db));
    }

    pg_query($db,"COMMIT");

    header("Location: homepage.html");
    exit();

} catch(Exception $e) {
    pg_query($db, "ROLLBACK");
    die("Errore nell'inserimento dei dati: " . $e->getMessage());
} finally {
    pg_close($db);
}
?>