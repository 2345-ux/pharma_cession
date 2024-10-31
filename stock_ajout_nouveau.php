<?php
// stock_ajout_nouveau.php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Récupération des données POST
    $code = generateUniqueCode();
    $nom = $_POST['nom'] ?? '';
    $quantite = $_POST['quantite'] ?? 0;
    $prix_unitaire = $_POST['prix_unitaire'] ?? 0;
    $prix_total = $quantite * $prix_unitaire; // Calcul du prix total
    $date_ajout = $_POST['date_ajout'] ?? '';
    $date_expiration = $_POST['date_expiration'] ?? '';
    $fournisseur = $_POST['fournisseur'] ?? '';
    $categorie = $_POST['categorie'] ?? '';

    // Validation des données
    if (empty($nom)) {
        throw new Exception("Le nom du produit est requis");
    }
    if (empty($quantite) || $quantite <= 0) {
        throw new Exception("La quantité doit être supérieure à zéro");
    }
    if (empty($prix_unitaire) || $prix_unitaire <= 0) {
        throw new Exception("Le prix unitaire doit être supérieur à zéro");
    }
    if (empty($date_ajout)) {
        throw new Exception("La date d'ajout est requise");
    }
    if (empty($date_expiration)) {
        throw new Exception("La date d'expiration est requise");
    }
    if (empty($fournisseur)) {
        throw new Exception("Le fournisseur est requis");
    }
    if (empty($categorie)) {
        throw new Exception("La catégorie est requise");
    }

    // Insertion des données dans la table t_stock
    $stmt = $pdo->prepare("INSERT INTO t_stock (code, nom, quantite, prix_fournisseur, prix_total, date_ajout, date_expiration, fournisseur, categorie)
                          VALUES (:code, :nom, :quantite, :prix_unitaire, :prix_total, :date_ajout, :date_expiration, :fournisseur, :categorie)");

    $stmt->execute([
        ':code' => $code,
        ':nom' => $nom,
        ':quantite' => $quantite,
        ':prix_unitaire' => $prix_unitaire,
        ':prix_total' => $prix_total,
        ':date_ajout' => $date_ajout,
        ':date_expiration' => $date_expiration,
        ':fournisseur' => $fournisseur,
        ':categorie' => $categorie
    ]);

    // Retourner la réponse de succès
    echo json_encode([
        'status' => 'success',
        'message' => 'Produit ajouté avec succès !',
        'data' => [
            'code' => $code,
            'nom' => $nom,
            'quantite' => $quantite,
            'prix_unitaire' => $prix_unitaire,
            'prix_total' => $prix_total,
            'date_ajout' => $date_ajout,
            'date_expiration' => $date_expiration,
            'fournisseur' => $fournisseur,
            'categorie' => $categorie
        ]
    ]);

} catch (PDOException $e) {
    // Gestion des erreurs de base de données
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Gestion des autres erreurs
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

// Fonction pour générer un code unique
function generateUniqueCode() {
    $date = new DateTime();
    return 'ENT' . $date->format('YmdHis') . sprintf('%03d', rand(0, 999));
}