<?php
// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); 

try {
    // Log des données reçues
    error_log("Données POST reçues : " . print_r($_POST, true));

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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérer et nettoyer les données
        $code = generateUniqueCode();
        $id_produit = $_POST['id_produit'] ?? null;
$id_fournisseur = $_POST['id_fournisseur'] ?? null;
$quantite = $_POST['quantite'] ?? 0;
$prix_unitaire = $_POST['prix_unitaire'] ?? 0;
$date_ajout = $_POST['date_ajout'] ?? '';
$date_expiration = $_POST['date_expiration'] ?? '';

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


        // Préparer et exécuter la requête
        $stmt = $pdo->prepare("INSERT INTO t_commandes (
            code,
            id_produit, 
            id_fournisseur, 
            quantite, 
            prix_unitaire, 
            date_ajout, 
            date_expiration
        ) VALUES (
            :code,
            :id_produit, 
            :id_fournisseur, 
            :quantite, 
            :prix_unitaire, 
            :date_ajout, 
            :date_expiration
        )");

        $params = [
            ':code' => $code,
            ':id_produit' => $id_produit,
            ':id_fournisseur' => $id_fournisseur,
            ':quantite' => $quantite,
            ':prix_unitaire' => $prix_unitaire,
            ':date_ajout' => $date_ajout,
            ':date_expiration' => $date_expiration
        ];

        // Debug: afficher les paramètres
        error_log("Paramètres de la requête : " . print_r($params, true));

        $stmt->execute($params);

        echo json_encode([
            'status' => 'success',
            'message' => 'Commande ajoutée avec succès',
            'data' => [
                'code' => $code,
                'id' => $pdo->lastInsertId()
            ]
        ]);

    } else {
        throw new Exception("Méthode HTTP invalide. Utilisez POST.");
    }
} catch (PDOException $e) {
    error_log("Erreur PDO dans commande_ajout.php: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur lors de l\'enregistrement dans la base de données',
        'debug' => $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log("Exception dans commande_ajout.php: " . $e->getMessage());
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