<?php
// get_stock.php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Requête pour récupérer tous les produits en stock avec des champs spécifiques
    $query = "
        SELECT 
            id_produit, 
            quantite, 
            prix_unitaire, 
            (quantite * prix_unitaire) AS montant_total,
            date_ajout, 
            date_expiration 
        FROM t_stock";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Récupérer les résultats
    $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$stocks) {
        $stocks = []; // Retourner un tableau vide si aucun stock trouvé
    }

    // Renvoyer les données en format JSON
    echo json_encode([
        'status' => 'success',
        'message' => 'Liste des produits en stock récupérée avec succès !',
        'donnees' => $stocks
    ]);

} catch (PDOException $e) {
    // Gestion des erreurs
    http_response_code(500); // Erreur serveur
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
