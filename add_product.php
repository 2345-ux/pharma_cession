<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=pharma_cession;charset=utf8", "saw24", "saw24");

    // Récupération des données POST
    $nom = $_POST['nom'] ?? '';
    $categorie = getCategorieProduit($pdo, $nom);

    // Reste du code pour ajouter le produit...

    echo json_encode([
        'status' => 'success',
        'message' => 'Produit ajouté avec succès !',
        'data' => [
            'nom' => $nom,
            'categorie' => $categorie
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}

function getCategorieProduit($pdo, $nomProduit) {
    $stmt = $pdo->prepare("SELECT c.nom AS categorie 
                          FROM t_produit p
                          JOIN t_categories c ON p.categorie = c.code
                          WHERE p.nom = :nom
                          LIMIT 1");
    $stmt->execute([':nom' => $nomProduit]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['categorie'] : '';
}