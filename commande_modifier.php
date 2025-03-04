<?php
header('Content-Type: application/json');

try {
    // Log des données reçues
    error_log("Données reçues : " . print_r($_POST, true));

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

    // Validation des données
    if (!isset($_POST['id_commande']) || empty($_POST['id_commande'])) {
        throw new Exception("L'ID de la commande est requis");
    }

    // Préparation des données
    $data = [
        ':id_commande' => $_POST['id_commande'],
        ':id_produit' => $_POST['id_produit'] ?? null,
        ':id_fournisseur' => $_POST['id_fournisseur'] ?? null,
        ':quantite' => $_POST['quantite'] ?? 0,
        ':prix_fournis' => $_POST['prix_fournis'] ?? 0,
        ':prix_unitaire' => $_POST['prix_unitaire'] ?? 0,
        ':date_ajout' => $_POST['date_ajout'] ?? date('Y-m-d'),
        ':date_expiration' => $_POST['date_expiration'] ?? null
    ];

    // Début de la transaction
    $pdo->beginTransaction();

    // Mise à jour de la commande
    $sql = "UPDATE t_commandes SET 
            id_produit = :id_produit,
            id_fournisseur = :id_fournisseur,
            quantite = :quantite,
            prix_fournis = :prix_fournis,
            prix_unitaire = :prix_unitaire,
            date_ajout = :date_ajout,
            date_expiration = :date_expiration
            WHERE id_commande = :id_commande";

    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($data);

    if ($result) {
        // Mise à jour du stock
        $updateStock = $pdo->prepare("
            UPDATE t_stock 
            SET quantite = :quantite,
                prix_unitaire = :prix_unitaire,
                date_ajout = :date_ajout,
                date_expiration = :date_expiration
            WHERE id_commandes = :id_commande
        ");
        
        $updateStock->execute([
            ':quantite' => $data[':quantite'],
            ':prix_unitaire' => $data[':prix_unitaire'],
            ':date_ajout' => $data[':date_ajout'],
            ':date_expiration' => $data[':date_expiration'],
            ':id_commande' => $data[':id_commande']
        ]);

        $pdo->commit();
        
        echo json_encode([
            'status' => 'success',
            'message' => 'Commande modifiée avec succès!',
            'data' => ['id_commande' => $_POST['id_commande']]
        ]);
    } else {
        throw new Exception("Aucune modification n'a été effectuée");
    }

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