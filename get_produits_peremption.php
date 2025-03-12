<?php
header('Content-Type: application/json');

try {
    // Connexion à la base de données
    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Requête pour obtenir les produits qui périment dans les 3 prochains mois
    $query = "SELECT 
                p.id,
                p.nom,
                s.quantite,
                s.date_expiration,
                DATEDIFF(s.date_expiration, CURDATE()) as jours_restants
            FROM 
                t_produits p
            JOIN 
                t_stock s ON p.id = s.id_produit
            WHERE 
                s.date_expiration < DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                AND s.date_expiration >= CURDATE()
            ORDER BY 
                s.date_expiration ASC";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $produits = $stmt->fetchAll();

    // Requête pour compter le nombre total de produits en péremption
    $queryCount = "SELECT COUNT(*) as total FROM t_produits p
                   JOIN t_stock s ON p.id = s.id_produit
                   WHERE s.date_expiration < DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                   AND s.date_expiration >= CURDATE()";
    
    $stmtCount = $pdo->prepare($queryCount);
    $stmtCount->execute();
    $count = $stmtCount->fetch()['total'];

    if ($produits) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Liste des produits en péremption récupérée avec succès',
            'data' => $produits,
            'count' => (int)$count
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'message' => 'Aucun produit en péremption dans les 3 prochains mois',
            'data' => [],
            'count' => 0
        ]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
}
