<?php
// produit_modifier.php
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

    // Récupération des données POST
    $code = $_POST['code'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $seuil_produit = $_POST['seuil_produit'] ?? '';
    $categorie = $_POST['categorie'] ?? '';


    // Mise à jour du produit
    $stmt = $pdo->prepare(" UPDATE t_produits
        SET nom = :nom, seuil_produit = :seuil_produit, categorie = :categorie
        WHERE code = :code
    ");
    $stmt->execute([
        ':nom' => $nom,
        ':seuil_produit' => $seuil_produit,
        ':categorie' => $categorie,
        ':code' => $code
    ]);

    // Retourner la réponse de succès
    echo json_encode([
        'status' => 'success',
        'message' => 'Produit modifié avec succès !',
        'data' => [
            'code' => $code,
            'nom' => $nom,
            'seuil_produit' => $seuil_produit,
            'categorie' => $categorie
        ]
    ]);
} catch (PDOException $e) {
    // Gestion des erreurs de base de données
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Gestion des autres erreurs
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
