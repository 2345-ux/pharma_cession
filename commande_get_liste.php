<?php
// get_commande.php
header('Content-Type: application/json');

try {
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

    // Requête pour récupérer toutes les commandes avec les informations nécessaires
    $query = "SELECT 
    id_commande,
    id_produit, 
    t_produits.nom, 
    quantite, 
    prix_unitaire, 
    t_commandes.prix_fournis, 
    prix_total,  
    date_ajout, 
    date_expiration, 
    id_fournisseur, 
    t_fournisseurs.nom AS nom_fournisseurs
FROM 
    t_commandes
JOIN 
    t_produits ON t_commandes.id_produit = t_produits.id
JOIN 
    t_fournisseurs ON t_commandes.id_fournisseur = t_fournisseurs.id
ORDER BY 
    id_commande";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Vérification des résultats
    $commandes = $stmt->fetchAll();
    if (!$commandes) {
        $commandes = []; // Retourner un tableau vide si aucune commande trouvée
    }

    // Calculer les statistiques liées aux commandes
    $statsQuery = "SELECT 
                    COUNT(*) AS total_commandes,
                    SUM(quantite) AS total_quantite,
                    SUM(quantite * prix_unitaire) AS total_montant, -- Correction pour calculer le montant total
                    MIN(date_ajout) AS premiere_commande,
                    MAX(date_ajout) AS derniere_commande
                   FROM t_commandes";
    
    $statsStmt = $pdo->prepare($statsQuery);
    $statsStmt->execute();
    $stats = $statsStmt->fetch();

    if (!$stats) {
        // Si aucune statistique, renvoyer des valeurs par défaut
        $stats = [
            'total_commandes' => 0,
            'total_quantite' => 0,
            'total_montant' => 0,
            'premiere_commande' => null,
            'derniere_commande' => null,
        ];
    }

    // Renvoyer les données en format JSON
    echo json_encode([
        'status' => 'success',
        'message' => 'Liste des commandes récupérée avec succès !',
        'donnees' => $commandes,
        'statistiques' => [
            'nombre_commandes' => $stats['total_commandes'],
            'quantite_totale' => $stats['total_quantite'],
            'montant_total' => number_format($stats['total_montant'], 2, '.', ' '),
            'premiere_commande' => $stats['premiere_commande'],
            'derniere_commande' => $stats['derniere_commande']
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
