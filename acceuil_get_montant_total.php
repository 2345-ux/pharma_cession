<?php
header('Content-Type: application/json');

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=localhost;dbname=pharma_cession;charset=utf8", "saw24", "saw24");

    // Requête SQL pour calculer le montant
    $stmt = $pdo->prepare("
        SELECT SUM(t_stock.valeur_totale) AS montant 
        FROM t_stock
    ");
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Retourne le montant en JSON
    echo json_encode(['montant' => $result['montant'] ?? 0]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
