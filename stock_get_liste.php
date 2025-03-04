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

    // Requête pour récupérer tous les produits en stock
    $stmt = $pdo->prepare("SELECT 
    t_stock.id_stock,
    t_produits.id AS id_produit,  
    t_produits.nom AS nom_produit, 
    t_stock.quantite, 
    t_stock.prix_unitaire, 
    t_stock.valeur_totale, 
    t_stock.date_ajout,
    t_fournisseurs.id AS id_fournisseur,
    t_fournisseurs.nom AS nom_fournisseur,
    t_stock.date_expiration
FROM t_stock 
LEFT JOIN t_produits ON t_stock.id_produit = t_produits.id
LEFT JOIN t_fournisseurs ON t_stock.id_fournisseur = t_fournisseurs.id");
    $stmt->execute();

    // Récupérer les résultats
    $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Renvoyer les données en format JSON
    echo json_encode([
        'status' => 'success',
        'message' => 'Liste des produits en stock récupérée avec succès !',
        'donnees' => $stocks
    ]);

} catch (PDOException $e) {
    // Gestion des erreurs
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
