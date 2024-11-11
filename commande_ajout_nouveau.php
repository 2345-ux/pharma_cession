<?php
// add_entree.php
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
    $prix_fournisseur = $_POST['prix_fournisseur'] ?? 0;
    $prix_total = $quantite * $prix_fournisseur; // Calcul du prix total
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
    if (empty($prix_fournisseur) || $prix_fournisseur <= 0) {
        throw new Exception("Le prix fournisseur doit être supérieur à zéro");
    }
    if (empty($date_ajout)) {
        throw new Exception("La date d'entrée est requise");
    }
    if (empty($date_expiration)) {
        throw new Exception("La date d'expiration est requise");
    }
    if (empty($fournisseur)) {
        throw new Exception("Le fournisseur est requis");
    }
    if (empty($categorie)) {
        throw new Exception("La categorie est requis");
    }
    
    $stmtCheck = $pdo->prepare("SELECT code FROM t_produit WHERE nom = :nom");
    $stmtCheck->execute([':nom' => $nom]);

    if (!$stmtCheck->fetch()) {
        // La catégorie n'existe pas, on l'ajoute
        $stmtAddCategorie = $pdo->prepare("INSERT INTO t_produit (code, nom) VALUES (:code, :nom)");
        $stmtAddCategorie->execute([':code' => $nom, ':nom' => $nom]);
    }

    $stmtCheck = $pdo->prepare("SELECT code FROM t_categories WHERE nom = :categorie");
    $stmtCheck->execute([':categorie' => $categorie]);

    if (!$stmtCheck->fetch()) {
        // Le fournisserur n'existe pas, on l'ajoute
        $stmtAddCategorie = $pdo->prepare("INSERT INTO t_categories (code, nom) VALUES (:code, :nom)");
        $stmtAddCategorie->execute([':code' => $categorie, ':nom' => $categorie]);
    }

    $stmtCheck = $pdo->prepare("SELECT code FROM t_fournisseurs WHERE nom = :fournisseur");
    $stmtCheck->execute([':fournisseur' => $fournisseur]);

    if (!$stmtCheck->fetch()) {
        // La catégorie n'existe pas, on l'ajoute
        $stmtAddCategorie = $pdo->prepare("INSERT INTO t_fournisseurs (code, nom) VALUES (:code, :nom)");
        $stmtAddCategorie->execute([':code' => $fournisseur, ':nom' => $fournisseur]);
    }
    // Insertion avec le prix_total
    $stmt = $pdo->prepare("INSERT INTO t_entrees (code, nom, quantite, prix_fournisseur, prix_total, date_ajout, date_expiration, fournisseur, categorie)
                          VALUES (:code, :nom, :quantite, :prix_fournisseur, :prix_total, :date_ajout, :date_expiration, :fournisseur, :categorie)");

    $stmt->execute([
        ':code' => $code,
        ':nom' => $nom,
        ':quantite' => $quantite,
        ':prix_fournisseur' => $prix_fournisseur,
        ':prix_total' => $prix_total,
        ':date_ajout' => $date_ajout,
        ':date_expiration' => $date_expiration,
        ':fournisseur' => $fournisseur,
        ':categorie' => $categorie
    ]);

    // Retourner la réponse de succès
    echo json_encode([
        'status' => 'success',
        'message' => 'Entrée de produit ajoutée avec succès !',
        'data' => [
            'code' => $code,
            'nom' => $nom,
            'quantite' => $quantite,
            'prix_fournisseur' => $prix_fournisseur,
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