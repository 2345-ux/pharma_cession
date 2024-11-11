<?php
// add_sortie.php
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
    $date_sortie = $_POST['date_sortie'] ?? '';
    $date_expiration = $_POST['date_expiration'] ?? '';
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
    if (empty($date_sortie)) {
        throw new Exception("La date de sortie est requise");
    }
    if (empty($date_expiration)) {
        throw new Exception("La date d'expiration est requise");
        
    }
    if (empty($categorie)) {
        throw new Exception("La categorie est requise");
        
    }

    // Calcul du prix total
    $prix_total = $quantite * $prix_unitaire;

    // Insertion de la sortie de produit
    $stmt = $pdo->prepare("INSERT INTO t_sorties (code, nom, quantite, prix_unitaire, prix_total, date_sortie, date_expiration, categorie) 
                           VALUES (:code, :nom, :quantite, :prix_unitaire, :prix_total, :date_sortie, :date_expiration, :categorie)");

    $stmt->execute([
        ':code' => $code,
        ':nom' => $nom,
        ':quantite' => $quantite,
        ':prix_unitaire' => $prix_unitaire,
        ':prix_total' => $prix_total,
        ':date_sortie' => $date_sortie,
        ':date_expiration' => $date_expiration,
        ':categorie' => $categorie
    ]);

    // Retourner la réponse de succès
    echo json_encode([
        'status' => 'success',
        'message' => 'Sortie de produit ajoutée avec succès !',
        'data' => [
            'code' => $code,
            'nom' => $nom,
            'quantite' => $quantite,
            'prix_unitaire' => $prix_unitaire,
            'prix_total' => $prix_total,
            'date_sortie' => $date_sortie,
            'date_expiration' => $date_expiration,
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

// Fonction pour générer un code unique pour chaque sortie
function generateUniqueCode() {
    $date = new DateTime();
    return 'SRT' . $date->format('YmdHis') . sprintf('%03d', rand(0, 999));
}
