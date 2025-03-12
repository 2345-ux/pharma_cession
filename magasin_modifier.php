<?php
header('Content-Type: application/json');

try {
    if (empty($_POST['id']) || empty($_POST['nom'])) {
        throw new Exception('L\'ID et le nom du magasin sont requis');
    }

    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $stmt = $pdo->prepare("UPDATE t_structure_vente SET nom = :nom WHERE id = :id");
    
    $result = $stmt->execute([
        ':id' => $_POST['id'],
        ':nom' => $_POST['nom']
    ]);

    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Magasin modifié avec succès'
        ]);
    } else {
        throw new Exception('Aucune modification effectuée ou magasin non trouvé');
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
