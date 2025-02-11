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
    $quantite = $_POST['quantite'] ?? 0;
    $prix_unitaire = $_POST['prix_unitaire'] ?? 0;
    $date_sortie = $_POST['date_sortie'] ?? '';
    $date_expiration = $_POST['date_expiration'] ?? '';

    // Valider les données
    if (empty($id_produit)) {
        throw new Exception("L'ID du produit est requis.");
    }

    if (empty($quantite) || $quantite <= 0) {
        throw new Exception("La quantité est requise et doit être supérieure à zéro.");
    }

    if (empty($prix_unitaire) || $prix_unitaire <= 0) {
        throw new Exception("Le prix unitaire est requis et doit être supérieur à zéro.");
    }

    if (empty($date_sortie)) {
        throw new Exception("La date de sortie est requise.");
    }

    if (empty($date_expiration)) {
        throw new Exception("La date d'expiration est requise.");
    }

    // Calculer le prix total
    $prix_total = $quantite * $prix_unitaire;

    // Début de la transaction
    $pdo->beginTransaction();

    // Vérifier si le produit existe dans le stock
    $stmtStock = $pdo->prepare("SELECT quantite FROM t_stock WHERE id_produit = :id_produit");
    $stmtStock->execute([':id_produit' => $id_produit]);
    $stock = $stmtStock->fetch();

    if (!$stock) {
        throw new Exception("Produit non trouvé dans le stock.");
    }

    $stockQuantite = (int) $stock['quantite'];

    // Vérifier si le stock est suffisant
    if ($stockQuantite < $quantite) {
        throw new Exception("Stock insuffisant pour ce produit.");
    }

    // Insérer la sortie dans `t_sorties`
    $stmtSorties = $pdo->prepare("
        INSERT INTO t_sorties (code, id_produit, quantite, prix_unitaire, prix_total, date_sortie, date_expiration)
        VALUES (:code, :id_produit, :quantite, :prix_unitaire, :prix_total, :date_sortie, :date_expiration)
    ");
    $stmtSorties->execute([
        ':code' => $code,
        ':id_produit' => $id_produit,
        ':quantite' => $quantite,
        ':prix_unitaire' => $prix_unitaire,
        ':prix_total' => $prix_total,
        ':date_sortie' => $date_sortie,
        ':date_expiration' => $date_expiration
    ]);

    // Mettre à jour le stock
    $stmtUpdateStock = $pdo->prepare("
        UPDATE t_stock
        SET quantite = quantite - :quantite
        WHERE id_produit = :id_produit
    ");
    $stmtUpdateStock->execute([
        ':quantite' => $quantite,
        ':id_produit' => $id_produit
    ]);

    // Valider la transaction
    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Sortie ajoutée avec succès.',
        'data' => [
            'code' => $code
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
        'message' => $e->getMessage()
    ]);
}

function generateUniqueCode() {
    return 'SORT' . date('YmdHis') . sprintf('%04d', random_int(0, 9999));
}
?>
