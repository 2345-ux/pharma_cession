<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $where = [];
    $params = [];

    // Recherche par nom de produit
    if (!empty($_POST['search'])) {
        $where[] = "t_produits.nom LIKE :search";
        $params[':search'] = '%' . $_POST['search'] . '%';
    }

    // Recherche par date
    if (!empty($_POST['date_debut'])) {
        $where[] = "t_commandes.date_ajout >= :date_debut";
        $params[':date_debut'] = $_POST['date_debut'];
    }

    if (!empty($_POST['date_fin'])) {
        $where[] = "t_commandes.date_ajout <= :date_fin";
        $params[':date_fin'] = $_POST['date_fin'];
    }

    $whereClause = !empty($where) ? 'WHERE ' . implode(' AND ', $where) : '';

    $sql = "SELECT 
        t_commandes.id_commande,
        t_commandes.code,
        t_produits.id AS id_produit,
        t_produits.nom,
        t_commandes.quantite,
        t_commandes.prix_unitaire,
        t_commandes.prix_fournis,
        t_commandes.prix_total,
        t_commandes.date_ajout,
        t_commandes.date_expiration,
        t_fournisseurs.id AS id_fournisseur,
        t_fournisseurs.nom AS nom_fournisseurs
    FROM t_commandes
    JOIN t_produits ON t_commandes.id_produit = t_produits.id
    JOIN t_fournisseurs ON t_commandes.id_fournisseur = t_fournisseurs.id
    $whereClause
    ORDER BY t_commandes.date_ajout DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'message' => 'Recherche effectuée avec succès',
        'donnees' => $resultats
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
}
