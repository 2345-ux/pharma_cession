<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

    // Vérification si la table existe
    $tableCheck = $pdo->query("SHOW TABLES LIKE 't_structure_vente'");
    if ($tableCheck->rowCount() === 0) {
        throw new Exception("La table t_structure_vente n'existe pas");
    }

    // Requête avec gestion d'erreur explicite
    $query = "SELECT id, nom FROM t_structure_vente ORDER BY nom";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $magasins = $stmt->fetchAll();

    // Log du résultat
    error_log('Données récupérées : ' . print_r($magasins, true));

    echo json_encode([
        'status' => 'success',
        'message' => 'Liste des magasins récupérée avec succès',
        'data' => $magasins
    ]);

} catch (PDOException $e) {
    error_log('Erreur PDO : ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    error_log('Erreur : ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
