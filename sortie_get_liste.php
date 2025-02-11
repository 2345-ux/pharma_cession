<?php
// get_sorties.php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $paramsearch = $_GET['paramsearch'] ?? '';
    // Requête pour récupérer toutes les sorties
    $query = "
      SELECT 
            t_sorties.id_sortie,
            t_produits.id AS id_produit,  
            t_produits.nom AS nom_produit, 
            t_sorties.quantite, 
            t_sorties.prix_unitaire, 
            t_sorties.prix_total, 
            t_sorties.date_sortie, 
            t_sorties.date_expiration, 
            t_categories.nom AS nom_categorie
        FROM t_sorties
        LEFT JOIN t_produits ON t_sorties.id_produit = t_produits.id
        LEFT JOIN t_categories ON t_produits.categorie = t_categories.code
        WHERE t_produits.nom LIKE '%$paramsearch%'
        ORDER BY t_sorties.id_sortie
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Récupérer les résultats
    $sorties = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Renvoyer les données en format JSON
    echo json_encode([
        'status' => 'success',
        'message' => 'Liste des sorties récupérée avec succès !',
        'donnees' => $sorties
    ]);

} catch (PDOException $e) {
    // Gestion des erreurs
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}