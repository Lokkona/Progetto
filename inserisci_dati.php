<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: Login.html");
    exit();
}
 
  $connection_string="host=localhost port=5432 dbname=moidatabase user=www password=1Nf4m303";

  $db=pg_connect($connection_string) or die('Errore nella connessione al database' . pg_last_error());

$sport = pg_escape_string($_POST['sport']);
$data = pg_escape_string($_POST['data']);
$uname = $_SESSION['user_id'];

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
                    $_POST['minuti_basket'],
                    $_POST['punti_basket'],
                    $_POST['tiri_t'],
                    $_POST['makes'],
                    $_POST['tiri3'],
                    $_POST['makes3'],
                    $_POST['fta'],
                    $_POST['ft'],
                    $_POST['rimbalzi'],
                    $_POST['rimbalzi_offensivi'],
                    $_POST['rimbalzi_difensivi'],
                    $_POST['assist_basket'],
                    $_POST['stoppate'],
                    $_POST['rubate'],
                    $_POST['to']
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
                    $_POST['minuti_calcio'],
                    $_POST['gol'],
                    $_POST['tiri'],
                    $_POST['tiri_in_porta'],
                    $_POST['assist_calcio'],
                    $_POST['passaggi_tentati'],
                    $_POST['passaggi_riusciti'],
                    $_POST['intercetti'],
                    $_POST['contrasti'],
                    $_POST['palle_recuperate'],
                    $_POST['dribbling_tentati'],
                    $_POST['dribbling_riusciti']
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
                    $_POST['tempo'],
                    $_POST['punti_giocati'],
                    $_POST['punti_vinti'],
                    $_POST['prima_g'],
                    $_POST['prima_r'],
                    $_POST['prima_v'],
                    $_POST['seconda_r'],
                    $_POST['seconda_v'],
                    $_POST['doppio_fallo'],
                    $_POST['risposta_g'],
                    $_POST['risposta_v'],
                    $_POST['break'],
                    $_POST['break_v'],
                    $_POST['errori']
                ]);
                break;
    }

    if(!$result){
        throw new Exception(pg_last_error($db));
    }

    pg_query($db,"COMMIT");

} catch(Exception $e) {
    pg_query($db, "ROLLBACK");
    die("Errore nell'inserimento dei dati: " . $e->getMessage());
} finally {
    pg_close($db);
}
?>