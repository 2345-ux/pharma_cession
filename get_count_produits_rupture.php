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

    // Requête SQL corrigée pour compter les produits en voie de rupture
    $query = "SELECT 
                COUNT(*) as nombre
            FROM 
                t_produits p
            LEFT JOIN 
                t_stock s ON p.id = s.id_produit
            WHERE 
                COALESCE(s.quantite, 0) <= p.seuil_produit";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch();

    echo json_encode([
        'status' => 'success',
        'message' => 'Nombre de produits en voie de rupture récupéré avec succès',
        'count' => (int)$result['nombre']
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
}
