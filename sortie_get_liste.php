<?php
// get_sorties.php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Requête pour récupérer toutes les sorties
    $stmt = $pdo->prepare("SELECT * FROM t_sorties");
    $stmt->execute();

    // Récupérer les résultats
    $sorties = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Renvoyer les données en format JSON
    echo json_encode([
        'status' => 'success',
        'message' => 'Liste des sorties récupérée avec succès !',
        'donnees' => $sorties
    ]);

} catch (PDOException $e) {
    // Gestion des erreurs
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
