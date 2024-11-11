<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=pharma_cession;charset=utf8", "saw24", "saw24");

    // Récupération des données POST
    $nom = $_POST['nom'] ?? '';

    // Vérifier si le fournisseur existe déjà
    $stmtCheck = $pdo->prepare("SELECT code FROM t_fournisseurs WHERE nom = :nom");
    $stmtCheck->execute([':nom' => $nom]);

    if (!$stmtCheck->fetch()) {
        // Le fournisseur n'existe pas, on l'ajoute
        $stmtAddSupplier = $pdo->prepare("INSERT INTO t_fournisseurs (code, nom) VALUES (:code, :nom)");
        $stmtAddSupplier->execute([':code' => $nom, ':nom' => $nom]);

        echo json_encode([
            'status' => 'success',
            'message' => 'Fournisseur ajouté avec succès !',
            'data' => [
                'nom' => $nom
            ]
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Ce fournisseur existe déjà.'
        ]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}