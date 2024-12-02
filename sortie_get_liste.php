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
    $stmt = $pdo->prepare("SELECT t_sorties.id, id_produit, prix_unitaire, quantite, prix_total, date_sortie, date_expiration,
t_produits.nom
FROM t_sorties, t_produits, t_categories
WHERE t_sorties.id_produit = t_produits.id 
ORDER by t_sorties.id");
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
