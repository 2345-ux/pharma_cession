<?php
header('Content-Type: application/json');

try {
    // Vérification du code produit
    if (!isset($_POST['code']) || empty($_POST['code'])) {
        throw new Exception('Code produit non fourni');
    }

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

    // Début de la transaction
    $pdo->beginTransaction();

    try {
        // Vérifier si le produit existe
        $checkStmt = $pdo->prepare("SELECT code FROM t_produits WHERE code = ?");
        $checkStmt->execute([$_POST['code']]);
        
        if (!$checkStmt->fetch()) {
            throw new Exception('Produit non trouvé');
        }

        // Vérifier si le produit est utilisé dans le stock
        $checkStockStmt = $pdo->prepare("SELECT COUNT(*) FROM t_stock WHERE id_produit = ?");
        $checkStockStmt->execute([$_POST['code']]);
        
        if ($checkStockStmt->fetchColumn() > 0) {
            // Mettre à jour les références dans t_stock
            $updateStockStmt = $pdo->prepare("UPDATE t_stock SET id_produit = NULL WHERE id_produit = ?");
            $updateStockStmt->execute([$_POST['code']]);
        }

        // Supprimer le produit
        $deleteStmt = $pdo->prepare("DELETE FROM t_produits WHERE code = ?");
        $result = $deleteStmt->execute([$_POST['code']]);

        if ($result) {
            $pdo->commit();
            echo json_encode([
                'status' => 'success',
                'message' => 'Produit supprimé avec succès'
            ]);
        } else {
            throw new Exception('Erreur lors de la suppression du produit');
        }

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
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
