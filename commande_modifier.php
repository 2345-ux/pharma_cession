<?php
// commande_modifier.php
header('Content-Type: application/json');

try {
    // Log des données reçues
    error_log("Données reçues : " . print_r($_POST, true));

    // Validation des données requises
    $required_fields = ['id_commande', 'id_produit', 'id_fournisseur', 'quantite', 'prix_unitaire'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("Le champ {$field} est requis");
        }
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

    // Préparation des données sans prix_total
    $data = [
        ':id_commande' => $_POST['id_commande'],
        ':id_produit' => $_POST['id_produit'],
        ':id_fournisseur' => $_POST['id_fournisseur'],
        ':quantite' => $_POST['quantite'],
        ':prix_fournis' => $_POST['prix_fournis'],
        ':prix_unitaire' => $_POST['prix_unitaire'],
        ':date_ajout' => $_POST['date_ajout'],
        ':date_expiration' => $_POST['date_expiration']
    ];

    // Vérification si la commande existe
    $check = $pdo->prepare("SELECT id_commande FROM t_commandes WHERE id_commande = ?");
    $check->execute([$_POST['id_commande']]);
    if (!$check->fetch()) {
        throw new Exception("La commande n'existe pas");
    }

    // Mise à jour sans prix_total
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

    if ($result && $stmt->rowCount() > 0) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Commande modifiée avec succès!',
            'data' => ['id_commande' => $_POST['id_commande']]
        ]);
    } else {
        throw new Exception("Aucune modification n'a été effectuée");
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