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

    // Validation des données requises
    $required = ['id_produit', 'id_fournisseur', 'quantite', 'prix_unitaire', 'prix_fournis'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Le champ $field est requis");
        }
    }

    // Début de la transaction
    $pdo->beginTransaction();

    // Générer un code unique
    $code = 'CMD' . date('YmdHis') . rand(1000, 9999);

    // Insertion de la commande
    $stmt = $pdo->prepare("
        INSERT INTO t_commandes (
            code, id_produit, id_fournisseur, quantite, 
            prix_fournis, prix_unitaire, date_ajout, date_expiration
        ) VALUES (
            :code, :id_produit, :id_fournisseur, :quantite,
            :prix_fournis, :prix_unitaire, :date_ajout, :date_expiration
        )
    ");

    $result = $stmt->execute([
        ':code' => $code,
        ':id_produit' => $_POST['id_produit'],
        ':id_fournisseur' => $_POST['id_fournisseur'],
        ':quantite' => $_POST['quantite'],
        ':prix_fournis' => $_POST['prix_fournis'],
        ':prix_unitaire' => $_POST['prix_unitaire'],
        ':date_ajout' => $_POST['date_ajout'] ?? date('Y-m-d'),
        ':date_expiration' => $_POST['date_expiration']
    ]);

    $id_commande = $pdo->lastInsertId();

    // Mise à jour ou insertion dans le stock
    $checkStock = $pdo->prepare("
        SELECT id FROM t_stock 
        WHERE id_produit = :id_produit 
        AND date_expiration = :date_expiration
    ");
    
    $checkStock->execute([
        ':id_produit' => $_POST['id_produit'],
        ':date_expiration' => $_POST['date_expiration']
    ]);

    if ($checkStock->fetch()) {
        // Mise à jour du stock existant
        $updateStock = $pdo->prepare("
            UPDATE t_stock 
            SET quantite = quantite + :quantite,
                prix_unitaire = :prix_unitaire,
                date_ajout = :date_ajout
            WHERE id_produit = :id_produit 
            AND date_expiration = :date_expiration
        ");
    } else {
        // Création d'une nouvelle entrée de stock
        $updateStock = $pdo->prepare("
            INSERT INTO t_stock (
                id_produit, quantite, prix_unitaire, 
                date_ajout, date_expiration, id_commandes
            ) VALUES (
                :id_produit, :quantite, :prix_unitaire,
                :date_ajout, :date_expiration, :id_commandes
            )
        ");
    }

    $updateStock->execute([
        ':id_produit' => $_POST['id_produit'],
        ':quantite' => $_POST['quantite'],
        ':prix_unitaire' => $_POST['prix_unitaire'],
        ':date_ajout' => $_POST['date_ajout'] ?? date('Y-m-d'),
        ':date_expiration' => $_POST['date_expiration'],
        ':id_commandes' => $id_commande
    ]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Commande créée avec succès',
        //'data' => ['id' => $id_commande, 'code' => $code]
    ]);

} catch (PDOException $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Erreur PDO : " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Erreur : " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
