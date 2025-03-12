<?php
// produit_get_liste.php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );



    $stmt = $pdo->prepare("SELECT 
    t_produits.id,
    t_produits.code, 
    t_produits.nom, 
    seuil_produit, 
    t_categories.nom AS nom_categories
FROM 
    t_produits
JOIN 
    t_categories ON t_produits.categorie = t_categories.code
ORDER BY 
   t_produits.id;");

    $stmt->execute([]);
    // Récupérer les résultats
    $produit = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'message' => 'Liste reccuperée !',
        'donnees' => $produit
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
