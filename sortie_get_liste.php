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

    // Requête pour récupérer toutes les sorties
    $stmt = $pdo->prepare("SELECT DISTINCT 
    t_sorties.id, 
    t_sorties.id_produit, 
    t_sorties.prix_unitaire, 
    t_sorties.quantite, 
    t_sorties.prix_total, 
    t_sorties.date_sortie, 
    t_sorties.date_expiration, 
    t_produits.nom
FROM 
    t_sorties
JOIN 
    t_produits ON t_sorties.id_produit = t_produits.id
ORDER BY 
    t_sorties.id");
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
