<?php
header('Content-Type: application/json');

try {
    if (empty($_POST['nom'])) {
        throw new Exception('Le nom du magasin est requis');
    }

    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $pdo->prepare("INSERT INTO t_structure_vente (nom) VALUES (:nom)");
    
    $result = $stmt->execute([
        ':nom' => $_POST['nom']
    ]);

    if ($result) {
        $id = $pdo->lastInsertId();
        echo json_encode([
            'status' => 'success',
            'message' => 'Magasin ajouté avec succès',
            'data' => ['id' => $id]
        ]);
    } else {
        throw new Exception('Erreur lors de l\'ajout du magasin');
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
