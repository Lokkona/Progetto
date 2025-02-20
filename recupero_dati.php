
<?php
header("Content-Type: application/json");

// Connessione al database
$host = "localhost";
$dbname = "miodatabase";
$user = "www";
$password = "1Nf4m303";

try {
    $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $utente_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 1;

    // Query per ottenere tutte le prestazioni inserite dall'utente
    $query = "SELECT id, sport, data, created_at FROM prestazioni WHERE user_id = :utente_id ORDER BY created_at DESC";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":utente_id", $utente_id, PDO::PARAM_INT);
    $stmt->execute();

    $prestazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($prestazioni as &$p) {
        $sport = $p['sport'];
        $prestazione_id = $p['id'];

        // Query dinamica per recuperare i dettagli in base allo sport
        $dettagliQuery = "";
        switch ($sport) {
            case "calcio":
                $dettagliQuery = "SELECT minuti, gol, tiri, tiri_porta, assist, passaggi_tentati, passaggi_riusciti, intercetti, contrasti, palle_recuperate, dribbling_tentati, dribbling_riusciti FROM stats_calcio WHERE prestazione_id = :prestazione_id";
                break;
            case "basket":
                $dettagliQuery = "SELECT minuti, punti, tiri_tentati, tiri_realizzati, tiri3_tentati, tiri3_realizzati, tiri_liberi_tentati, tiri_liberi_realizzati, rimbalzi_totali, rimbalzi_offensivi, rimbalzi_difensivi, assist, stoppate, palle_rubate, palle_perse FROM stats_basket WHERE prestazione_id = :prestazione_id";
                break;
            case "tennis":
                $dettagliQuery = "SELECT tempo, punti_giocati, punti_vinti, prima_giocate, prima_campo, prima_vinte, seconda_campo, seconda_vinte, doppi_falli, risposta_giocati, risposta_vinti, break_punti, break_convertiti, errori FROM stats_tennis WHERE prestazione_id = :prestazione_id";
                break;
        }

        if ($dettagliQuery) {
            $stmtDettagli = $conn->prepare($dettagliQuery);
            $stmtDettagli->bindParam(":prestazione_id", $prestazione_id, PDO::PARAM_INT);
            $stmtDettagli->execute();
            $p['dettagli'] = $stmtDettagli->fetch(PDO::FETCH_ASSOC);
        }
    }

    echo json_encode($prestazioni);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>