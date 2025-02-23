<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: Login.html");
    exit();
}

function validateBasketStats($data) {
    $error_message = "";
    
    if ($data['makes'] > $data['tiri_t']) {
        $error_message .= "<div class=error>I tiri realizzati non possono essere maggiori dei tiri tentati</div>";
    }
    if ($data['makes3'] > $data['tiri3']) {
        $error_message .= "<div class=error>I tiri da 3 realizzati non possono essere maggiori dei tiri da 3 tentati</div>";
    }
    if ($data['ft'] > $data['fta']) {
        $error_message .= "<div class=error>I tiri liberi realizzati non possono essere maggiori dei tiri liberi tentati</div>";
    }
    
    $total_rebounds = intval($data['rimbalzi_offensivi']) + intval($data['rimbalzi_difensivi']);
    if ($total_rebounds !== intval($data['rimbalzi'])) {
        $error_message .= "<div class=error>La somma dei rimbalzi offensivi e difensivi deve coincidere con il totale dei rimbalzi</div>";
    }
    
    return $error_message;
}

function validateCalcioStats($data) {
    $error_message = "";
    
    if ($data['tiri_in_porta'] > $data['tiri']) {
        $error_message .= "<div class=error>I tiri in porta non possono essere maggiori del totale dei tiri</div>";
    }
    if ($data['gol'] > $data['tiri_in_porta']) {
        $error_message .= "<div class=error>I gol non possono essere maggiori dei tiri in porta</div>";
    }
    if ($data['passaggi_riusciti'] > $data['passaggi_tentati']) {
        $error_message .= "<div class=error>I passaggi riusciti non possono essere maggiori dei passaggi tentati</div>";
    }
    if ($data['dribbling_riusciti'] > $data['dribbling_tentati']) {
        $error_message .= "<div class=error>I dribbling riusciti non possono essere maggiori dei dribbling tentati</div>";
    }
    
    return $error_message;
}

function validateTennisStats($data) {
    $error_message = "";
    
    if ($data['prima_r'] > $data['prima_g']) {
        $error_message .= "<div class=error>Le prime in campo non possono essere maggiori delle prime giocate</div>";
    }
    if ($data['prima_v'] > $data['prima_r']) {
        $error_message .= "<div class=error>I punti vinti con la prima non possono essere maggiori delle prime in campo</div>";
    }
    if ($data['seconda_v'] > $data['seconda_r']) {
        $error_message .= "<div class=error>I punti vinti con la seconda non possono essere maggiori delle seconde in campo</div>";
    }
    if ($data['break_v'] > $data['break']) {
        $error_message .= "<div class=error>I break convertiti non possono essere maggiori dei punti break</div>";
    }
    if ($data['risposta_v'] > $data['risposta_g']) {
        $error_message .= "<div class=error>I punti in risposta vinti non possono essere maggiori dei punti in risposta giocati</div>";
    }
    
    return $error_message;
}

  $connection_string="host=localhost port=5432 dbname=gruppo21 user=www password=tw2024";

  $db=pg_connect($connection_string) or die('Errore nella connessione al database' . pg_last_error());

$sport =isset($_POST['sport']) ? pg_escape_string($_POST['sport']) : null;
$data =isset($_POST['data']) ? pg_escape_string($_POST['data']) : null;
$user_id = $_SESSION['user_id'] ?? null;

if (!$sport || !$data || !$user_id) {
    die("Errore: Sport, data o utente mancante.");
}

$error_message = "";
switch($sport) {
    case 'basket':
        $error_message = validateBasketStats($_POST);
        break;
    case 'calcio':
        $error_message = validateCalcioStats($_POST);
        break;
    case 'tennis':
        $error_message = validateTennisStats($_POST);
        break;
    default:
        $error_message = "<div class=error>Sport non valido</div>";
}

if ($error_message !== "") {
    $_SESSION['error'] = $error_message;
    header("Location: inserisci_dati_form.php");
    exit();
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

    pg_query($db, "COMMIT");
    $_SESSION['success'] = "<div class=success>Dati inseriti con successo!</div>";
    header("Location: inserisci_dati_form.php");
    exit();

} catch(Exception $e) {
    pg_query($db, "ROLLBACK");
    die("Errore nell'inserimento dei dati: " . $e->getMessage());
} finally {
    pg_close($db);
}
?>