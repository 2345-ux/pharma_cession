<?php
header('Content-Type: application/json');

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=localhost;dbname=pharma_cession;charset=utf8", "saw24", "saw24");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Requête SQL pour calculer les bénéfices
    $stmt = $pdo->prepare("
        SELECT 
            COALESCE(SUM(t_stock.prix_unitaire * t_stock.quantite), 0) - 
            COALESCE(SUM(t_commandes.prix_fournis * t_stock.quantite), 0) AS benefices
        FROM 
            t_stock
        INNER JOIN 
            t_commandes ON t_stock.id_commandes = t_commandes.id_commande
    ");
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Retourne les bénéfices en JSON
    echo json_encode(['benefices' => $result['benefices']]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
