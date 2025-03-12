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

    // Requête pour compter les produits en voie de péremption
    $query = "SELECT 
                COUNT(*) as nombre_produits_en_voie_de_peremption
            FROM 
                t_produits p
            JOIN 
                t_stock s ON p.id = s.id_produit
            WHERE 
                s.date_expiration < DATE_ADD(CURDATE(), INTERVAL 3 MONTH)
                AND s.date_expiration >= CURDATE()";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch();

    echo json_encode([
        'status' => 'success',
        'message' => 'Nombre de produits en péremption récupéré avec succès',
        'count' => (int)$result['nombre_produits_en_voie_de_peremption']
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
}
