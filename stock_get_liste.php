<?php
// get_stock.php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Requête pour récupérer tous les produits en stock
    $stmt = $pdo->prepare("SELECT * FROM t_stock");
    $stmt->execute();

    // Récupérer les résultats
    $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Renvoyer les données en format JSON
    echo json_encode([
        'status' => 'success',
        'message' => 'Liste des produits en stock récupérée avec succès !',
        'donnees' => $stocks
    ]);

} catch (PDOException $e) {
    // Gestion des erreurs
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
