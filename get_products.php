// get_products.php
<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=pharma_cession;charset=utf8", "saw24", "saw24");
    
    // Modifier la requête pour inclure la catégorie
    $stmt = $pdo->query("
        SELECT p.*, c.nom as categorie_nom 
        FROM t_produit p 
        LEFT JOIN t_categories c ON p.categorie_id = c.id 
        ORDER BY p.nom
    ");
    
    echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}