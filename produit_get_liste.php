<?php
// add_fournisseur.php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );



    $stmt = $pdo->prepare("SELECT t_produit.code, t_produit.nom, t_categories.nom as nom_cat, categorie FROM t_produit, t_categories
WHERE t_produit.categorie = t_categories.code;");

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
