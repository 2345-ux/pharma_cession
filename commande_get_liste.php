<?php
// get_entrees.php
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

    // Requête pour récupérer toutes les entrées avec les noms de colonnes corrects
    $query = "SELECT 
                id,
                code,
                nom,
                quantite,
                prix_fournisseur,
                prix_total,
                DATE_FORMAT(date_ajout, '%d-%m-%Y') as date_ajout,
                DATE_FORMAT(date_expiration, '%d-%m-%Y') as date_expiration,
                fournisseur,
                DATE_FORMAT(date_creation, '%d-%m-%Y %H:%i:%s') as date_creation,
                DATE_FORMAT(date_modification, '%d-%m-%Y %H:%i:%s') as date_modification
              FROM t_entrees 
              ORDER BY date_creation DESC";
              
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Récupérer les résultats
    $entrees = $stmt->fetchAll();

    // Calculer les statistiques avec les noms de colonnes corrects
    $statsQuery = "SELECT 
                    COUNT(*) as total_entrees,
                    SUM(quantite) as total_quantite,
                    SUM(prix_total) as total_montant,
                    MIN(date_ajout) as premiere_entree,
                    MAX(date_ajout) as derniere_entree
                   FROM t_entrees";
    
    $statsStmt = $pdo->prepare($statsQuery);
    $statsStmt->execute();
    $stats = $statsStmt->fetch();

    // Renvoyer les données en format JSON
    echo json_encode([
        'status' => 'success',
        'message' => 'Liste des entrées récupérée avec succès !',
        'donnees' => $entrees,
        'statistiques' => [
            'nombre_entrees' => $stats['total_entrees'],
            'quantite_totale' => $stats['total_quantite'],
            'montant_total' => number_format($stats['total_montant'], 2, '.', ' '),
            'premiere_entree' => $stats['premiere_entree'],
            'derniere_entree' => $stats['derniere_entree']
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