<?php
header('Content-Type: application/json');

try {
    error_log("=== Début de la modification de sortie ===");
    error_log("Données POST reçues : " . print_r($_POST, true));

    // Validation des données
    if (empty($_POST['id_sortie'])) { // Changé de 'code' à 'id_sortie'
        throw new Exception("L'ID de sortie est requis");
    }

    $pdo = new PDO("mysql:host=localhost;dbname=pharma_cession;charset=utf8", "saw24", "saw24", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Vérification de l'existence de la sortie
    $stmt = $pdo->prepare("SELECT id_sortie FROM t_sorties WHERE id_sortie = ?"); // Modifié la colonne
    $stmt->execute([$_POST['id_sortie']]);
    
    if (!$stmt->fetch()) {
        error_log("Vérification de la sortie - ID recherché: " . $_POST['id_sortie']);
        error_log("Requête SQL: " . $stmt->queryString);
        throw new Exception("Sortie non trouvée avec l'ID : " . $_POST['id_sortie']);
    }

    // Début de la transaction
    $pdo->beginTransaction();

    try {
        // Mise à jour de la sortie
        $sql = "UPDATE t_sorties SET 
                id_produit = :id_produit,
                quantite = :quantite,
                prix_unitaire = :prix_unitaire,
                date_sortie = :date_sortie,
                date_expiration = :date_expiration
                WHERE id_sortie = :id_sortie"; // Changé de 'code' à 'id_sortie'

        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([
            ':id_sortie' => $_POST['id_sortie'], // Changé de ':code' à ':id_sortie'
            ':id_produit' => $_POST['id_produit'],
            ':quantite' => $_POST['quantite'],
            ':prix_unitaire' => $_POST['prix_unitaire'],
            ':date_sortie' => $_POST['date_sortie'],
            ':date_expiration' => $_POST['date_expiration'] ?? null
        ]);

        if (!$success) {
            throw new Exception("Échec de la mise à jour de la sortie");
        }

        $pdo->commit();

        echo json_encode([
            'status' => 'success',
            'message' => 'Sortie modifiée avec succès',
            'data' => [
                'id_sortie' => $_POST['id_sortie'], // Changé de 'code' à 'id_sortie'
                'rows_affected' => $stmt->rowCount()
            ]
        ]);

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
