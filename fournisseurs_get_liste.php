<?php
// add_fournisseur.php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=phama_cession;charset=utf8",
        "saw24",
        "saw24",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );



    $stmt = $pdo->prepare("SELECT * FROM t_fournisseurs");

    $stmt->execute([]);
    // Récupérer les résultats
    $fournisseurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'message' => 'Liste reccuperée !',
        'donnees' => $fournisseurs
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}