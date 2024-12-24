<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

    // Vérifier si les données sont envoyées via POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Méthode HTTP invalide. Utilisez POST.");
    }

    // Récupérer et nettoyer les données
    $code = generateUniqueCode();
    $id_produit = $_POST['id_produit'] ?? null;
    $id_fournisseur = $_POST['id_fournisseur'] ?? null;
    $quantite = $_POST['quantite'] ?? 0;
    $prix_unitaire = $_POST['prix_unitaire'] ?? 0;
    $date_ajout = $_POST['date_ajout'] ?? '';
    $date_expiration = $_POST['date_expiration'] ?? '';
    $prix_fournis = $_POST['prix_fournis'] ?? '';

    // Valider les données
    if (empty($id_produit)) {
        throw new Exception("L'ID du produit est requis.");
    }

    if (empty($id_fournisseur)) {
        throw new Exception("L'ID du fournisseur est requis.");
    }

    if (empty($quantite) || $quantite <= 0) {
        throw new Exception("La quantité est requise et doit être supérieure à zéro.");
    }

    if (empty($prix_unitaire) || $prix_unitaire <= 0) {
        throw new Exception("Le prix unitaire est requis et doit être supérieur à zéro.");
    }

    if (empty($date_ajout)) {
        throw new Exception("La date d'ajout est requise.");
    }

    if (empty($date_expiration)) {
        throw new Exception("La date d'expiration est requise.");
    }
    if (empty($prix_fournis)) {
        throw new Exception("Le prix fournisseur est requise.");
    }

    // Début de la transaction
    $pdo->beginTransaction();

    // Vérifier si le produit existe
    $stmtVerifyProduit = $pdo->prepare("SELECT COUNT(*) FROM t_produits WHERE id = :id_produit");
    $stmtVerifyProduit->execute([':id_produit' => $id_produit]);
    if ($stmtVerifyProduit->fetchColumn() == 0) {
        throw new Exception("Le produit avec l'ID $id_produit n'existe pas.");
    }

    // Vérifier si le fournisseur existe
    $stmtVerifyFournisseur = $pdo->prepare("SELECT COUNT(*) FROM t_fournisseurs WHERE id = :id_fournisseur");
    $stmtVerifyFournisseur->execute([':id_fournisseur' => $id_fournisseur]);
    if ($stmtVerifyFournisseur->fetchColumn() == 0) {
        throw new Exception("Le fournisseur avec l'ID $id_fournisseur n'existe pas.");
    }

    // Insérer dans t_commandes
    $stmtCommandes = $pdo->prepare("INSERT INTO t_commandes (
        code, id_produit, id_fournisseur, quantite, prix_unitaire, date_ajout, date_expiration, prix_fournis
    ) VALUES (
        :code, :id_produit, :id_fournisseur, :quantite, :prix_unitaire, :date_ajout, :date_expiration, :prix_fournis
    )");
    $paramsCommandes = [
        ':code' => $code,
        ':id_produit' => $id_produit,
        ':id_fournisseur' => $id_fournisseur,
        ':quantite' => $quantite,
        ':prix_unitaire' => $prix_unitaire,
        ':date_ajout' => $date_ajout,
        ':date_expiration' => $date_expiration,
        ':prix_fournis' => $prix_fournis
    ];
    $stmtCommandes->execute($paramsCommandes);
    $id_commande = $pdo->lastInsertId();

    // Mettre à jour ou insérer dans t_stock
    // Étape 1 : Vérifier si la ligne existe déjà
$stmtSelect = $pdo->prepare("
SELECT COUNT(*) AS count 
FROM t_stock 
WHERE id_produit = :id_produit AND date_expiration = :date_expiration AND prix_unitaire = :prix_unitaire
");

$paramsSelect = [
':id_produit' => $id_produit,
':date_expiration' => $date_expiration,
':prix_unitaire' => $prix_unitaire
];

$stmtSelect->execute($paramsSelect);
$result = $stmtSelect->fetch(PDO::FETCH_ASSOC);

if ($result['count'] > 0) {
// Étape 2 : Si la ligne existe, faire un UPDATE
$stmtUpdate = $pdo->prepare("
    UPDATE t_stock 
    SET 
        quantite = quantite + :quantite,
        prix_unitaire = :prix_unitaire,
        date_ajout = :date_ajout
    WHERE 
        id_produit = :id_produit AND date_expiration = :date_expiration AND prix_unitaire = :prix_unitaire
");

$paramsUpdate = [
    ':quantite' => $quantite,
    ':prix_unitaire' => $prix_unitaire,
    ':date_ajout' => $date_ajout,
    ':id_produit' => $id_produit,
    ':date_expiration' => $date_expiration
];

$stmtUpdate->execute($paramsUpdate);

} else {
// Étape 3 : Si la ligne n'existe pas, faire un INSERT
$stmtInsert = $pdo->prepare("
    INSERT INTO t_stock (
        id_produit, quantite, prix_unitaire, date_ajout, date_expiration, id_commandes
    ) VALUES (
        :id_produit, :quantite, :prix_unitaire, :date_ajout, :date_expiration, :id_commandes
    )
");

$paramsInsert = [
    ':id_produit' => $id_produit,
    ':quantite' => $quantite,
    ':prix_unitaire' => $prix_unitaire,
    ':date_ajout' => $date_ajout,
    ':date_expiration' => $date_expiration,
    ':id_commandes' => $id_commande,
];

$stmtInsert->execute($paramsInsert);
}


   // Valider la transaction
$pdo->commit();

echo json_encode([
    'status' => 'success',
    'data' => [
        'code' => $code,
        'id' => $pdo->lastInsertId()
    ]
]);

} catch (PDOException $e) {
    // Annuler la transaction en cas d'erreur SQL
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Erreur PDO : " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur lors de l\'opération en base de données.'.$e->getMessage(),
        'debug' => $e->getMessage()
    ]);
} catch (Exception $e) {
    // Gestion des autres erreurs
    error_log("Erreur générale : " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'debug' => 'Erreur attrapée dans le bloc catch principal'
    ]);
}

function generateUniqueCode() {
    return 'CMD' . date('YmdHis') . sprintf('%04d', random_int(0, 9999));
}
?>
