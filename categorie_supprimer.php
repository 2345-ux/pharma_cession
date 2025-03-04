<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Debug log
    error_log("Données reçues : " . print_r($_POST, true));
    
    if (!isset($_POST['code']) || empty($_POST['code'])) {
        throw new Exception('Code de catégorie non fourni');
    }

    $code = trim($_POST['code']);
    
    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Vérifier si la catégorie existe
    $checkStmt = $pdo->prepare("SELECT code FROM t_categories WHERE code = ?");
    $checkStmt->execute([$code]);
    
    if (!$checkStmt->fetch()) {
        throw new Exception('Catégorie non trouvée');
    }

    // Commencer une transaction
    $pdo->beginTransaction();

    try {
        // Mettre à jour les produits qui utilisent cette catégorie (mettre une catégorie par défaut ou null)
        $updateProduits = $pdo->prepare("UPDATE t_produits SET categorie = NULL WHERE categorie = ?");
        $updateProduits->execute([$code]);

        // Supprimer la catégorie
        $stmt = $pdo->prepare("DELETE FROM t_categories WHERE code = ?");
        $result = $stmt->execute([$code]);

        if ($result && $stmt->rowCount() > 0) {
            $pdo->commit();
            echo json_encode([
                'status' => 'success',
                'message' => 'Catégorie supprimée avec succès'
            ]);
        } else {
            throw new Exception('Erreur lors de la suppression de la catégorie');
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
