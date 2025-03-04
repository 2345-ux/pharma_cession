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

    // Vérification de l'existence de l'ID
    if (!isset($_POST['code']) || empty($_POST['code'])) {
        throw new Exception("ID de commande non fourni");
    }

    $id_commande = $_POST['code'];

    // Vérification si la commande existe avant suppression
    $checkStmt = $pdo->prepare("SELECT id_commande FROM t_commandes WHERE id_commande = ?");
    $checkStmt->execute([$id_commande]);
    
    if ($checkStmt->rowCount() === 0) {
        throw new Exception("La commande n'existe pas");
    }

    // Suppression de la commande
    $stmt = $pdo->prepare("DELETE FROM t_commandes WHERE id_commande = ?");
    $result = $stmt->execute([$id_commande]);

    if ($result && $stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Commande supprimée avec succès!'
        ]);
    } else {
        throw new Exception("Erreur lors de la suppression");
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