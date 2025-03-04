<?php
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

    // Vérification de l'ID
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        throw new Exception('ID du fournisseur non fourni');
    }

    $id = $_POST['id'];

    // Début de la transaction
    $pdo->beginTransaction();

    try {
        // Vérifier si le fournisseur existe
        $checkStmt = $pdo->prepare("SELECT code FROM t_fournisseurs WHERE id = ?");
        $checkStmt->execute([$id]);
        
        if (!$checkStmt->fetch()) {
            throw new Exception('Fournisseur non trouvé');
        }

        // Vérifier si le fournisseur est utilisé dans t_commandes
        $checkUsageStmt = $pdo->prepare("SELECT COUNT(*) FROM t_commandes WHERE id_fournisseur = ?");
        $checkUsageStmt->execute([$id]);
        
        if ($checkUsageStmt->fetchColumn() > 0) {
            // Mettre à NULL les références dans t_commandes
            $updateStmt = $pdo->prepare("UPDATE t_commandes SET id_fournisseur = NULL WHERE id_fournisseur = ?");
            $updateStmt->execute([$id]);
        }

        // Supprimer le fournisseur
        $deleteStmt = $pdo->prepare("DELETE FROM t_fournisseurs WHERE id = ?");
        $result = $deleteStmt->execute([$id]);

        if ($result) {
            $pdo->commit();
            echo json_encode([
                'status' => 'success',
                'message' => 'Fournisseur supprimé avec succès'
            ]);
        } else {
            throw new Exception('Erreur lors de la suppression du fournisseur');
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
