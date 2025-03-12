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

    // Requête SQL corrigée pour obtenir les produits en voie de rupture
    $query = "SELECT 
                p.id,
                p.nom,
                p.seuil_produit,
                COALESCE(s.quantite, 0) as quantite
            FROM 
                t_produits p
            LEFT JOIN 
                t_stock s ON p.id = s.id_produit
            WHERE 
                COALESCE(s.quantite, 0) <= p.seuil_produit
            ORDER BY 
                p.nom ASC";

    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $produits = $stmt->fetchAll();

    // Vérification s'il y a des résultats
    if ($produits) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Liste des produits en voie de rupture récupérée avec succès',
            'data' => $produits,
            'count' => count($produits)
        ]);
    } else {
        echo json_encode([
            'status' => 'success',
            'message' => 'Aucun produit en voie de rupture',
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
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
