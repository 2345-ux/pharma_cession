<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $code = $_POST['code'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $tel = $_POST['tel'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $email = $_POST['email'] ?? '';

    // Removed the extra comma before WHERE
    $stmt = $pdo->prepare("UPDATE t_fournisseurs SET nom = :nom, email = :email, tel = :tel, adresse = :adresse WHERE code = :code");
    
    $stmt->execute([
        ':code' => $code,
        ':nom' => $nom,
        ':tel' => $tel,
        ':adresse' => $adresse,
        ':email' => $email
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Fournisseur modifié avec succès !'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}