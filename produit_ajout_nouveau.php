<?php
// add_produit.php
header('Content-Type: application/json');

try {
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
    $code = generateUniqueCode();
    $nom = $_POST['nom'] ?? '';
    $categorie = $_POST['categorie'] ?? '';

    // Validation des données
    if (empty($nom)) {
        throw new Exception("Le nom du produit est requis");
    }
    if (empty($categorie)) {
        throw new Exception("La catégorie est requise");
    }

    /*$stmtCheck = $pdo->prepare("SELECT code FROM t_categories WHERE nom = :categorie");
    $stmtCheck->execute([':categorie' => $categorie]);

    if (!$stmtCheck->fetch()) {
        // La catégorie n'existe pas, on l'ajoute
        $stmtAddCategorie = $pdo->prepare("INSERT INTO t_categories (code, nom) VALUES (:code, :nom)");
        $stmtAddCategorie->execute([':code' => $categorie, ':nom' => $categorie]);
    }*/

    // Insertion du produit
    $stmt = $pdo->prepare("INSERT INTO t_produit (code, nom, categorie) VALUES (:code, :nom, :categorie)");
    $stmt->execute([
        ':code' => $code,
        ':nom' => $nom,
        ':categorie' => $categorie  // Correction de la variable $categories en $categorie
    ]);

    // Retourner la réponse de succès
    echo json_encode([
        'status' => 'success',
        'message' => 'Produit ajouté avec succès !',
        'data' => [
            'code' => $code,
            'nom' => $nom,
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

function generateUniqueCode()
{
    $date = new DateTime();
    // Préfixe PRD pour Produit suivi de la date et un nombre aléatoire
    return 'PRD' . $date->format('YmdHis') . sprintf('%03d', rand(0, 999));
}
