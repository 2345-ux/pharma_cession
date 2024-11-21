<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $produit_id = $_POST['produit_id'] ?? null;
    $quantite = $_POST['quantite'] ?? 0;
    $prix_unitaire = $_POST['prix_unitaire'] ?? 0;
    $date_sortie = $_POST['date_sortie'] ?? '';
    $date_expiration = $_POST['date_expiration'] ?? '';

    if (empty($produit_id)) {
        throw new Exception("L'ID du produit est requis");
    }
    if (empty($quantite) || $quantite <= 0) {
        throw new Exception("La quantité doit être supérieure à zéro");
    }

   // $prix_total = $quantite * $prix_unitaire;

    $stmt = $pdo->prepare("INSERT INTO t_sorties (produit_id, quantite, prix_unitaire, date_sortie, date_expiration)
                           VALUES (:produit_id, :quantite, :prix_unitaire, :date_sortie, :date_expiration)");

    $stmt->execute([
        ':produit_id' => $produit_id,
        ':quantite' => $quantite,
        ':prix_unitaire' => $prix_unitaire,
        //':prix_total' => $prix_total,
        ':date_sortie' => $date_sortie,
        ':date_expiration' => $date_expiration
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Sortie de produit ajoutée avec succès !']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Erreur de base de données: ' . $e->getMessage()]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
