<?php
// get_products.php
header('Content-Type: application/json'); // Indique que la réponse sera en JSON

try {
    // Connexion à la base de données avec gestion des erreurs
    $pdo = new PDO("mysql:host=localhost;dbname=pharma_cession;charset=utf8", "saw24", "saw24", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Préparation et exécution de la requête pour récupérer les produits
    $stmt = $pdo->query("SELECT id, code, nom FROM t_produit");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupère les résultats sous forme de tableau associatif

    // Encode les résultats en JSON et les envoie au client
    echo json_encode(['status' => 'success', 'data' => $products]);
} catch (PDOException $e) {
    // En cas d'erreur, renvoie un message d'erreur en JSON
    echo json_encode(['status' => 'error', 'message' => 'Erreur: ' . $e->getMessage()]);
}
