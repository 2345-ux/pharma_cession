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
          SELECT t_stock.id_produit, t_produits.nom, t_stock.prix_unitaire, t_stock.quantite, t_stock.valeur_totale, t_stock.date_expiration, t_stock.date_ajout, t_produits.seuil_produit, t_fournisseurs.nom as nom_fournisseurs
FROM t_stock, t_produits, t_commandes, t_fournisseurs
WHERE t_stock.id_produit = t_produits.id
AND t_stock.id_commandes = t_commandes.id_commande
AND t_commandes.id_fournisseur = t_fournisseurs.id";

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
