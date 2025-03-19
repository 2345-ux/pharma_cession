<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Debug log
    error_log("Données reçues : " . print_r($_POST, true));
    
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception('ID du magasin non fourni');
    }

    $id = trim($_POST['id']);
    error_log("ID à supprimer : " . $id);
    
    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Vérifier si le magasin existe
    $checkStmt = $pdo->prepare("SELECT id FROM t_structure_vente WHERE id = ?");
    $checkStmt->execute([$id]);
    
    if (!$checkStmt->fetch()) {
        throw new Exception('Magasin non trouvé (ID: ' . $id . ')');
    }

    // Commencer une transaction
    $pdo->beginTransaction();

    try {
        // Supprimer le magasin
        $stmt = $pdo->prepare("DELETE FROM t_structure_vente WHERE id = ?");
        $result = $stmt->execute([$id]);

        if ($result && $stmt->rowCount() > 0) {
            $pdo->commit();
            echo json_encode([
                'status' => 'success',
                'message' => 'Magasin supprimé avec succès'
            ]);
        } else {
            throw new Exception('Erreur lors de la suppression du magasin');
        }

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }

} catch (PDOException $e) {
    error_log("Erreur PDO : " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Erreur : " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
