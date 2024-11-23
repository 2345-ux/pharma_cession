<?php
// add_fournisseur.php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=pharma_cession;charset=utf8",
        "saw24",
        "saw24",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    //$code = $_POST['code'] ?? '';
    $code = generateUniqueCode();
    $nom = $_POST['nom'] ?? '';
    $tel = $_POST['tel'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $email = $_POST['email'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO t_fournisseurs (code, nom, tel, adresse, email) VALUES (:code, :nom, :tel, :adresse, :email)");

    $stmt->execute([
        ':code' => $code,
        ':nom' => $nom,
        ':tel' => $tel,
        ':adresse' => $adresse,
        ':email' => $email
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Fournisseur ajouté avec succès !'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}



function generateUniqueCode()
{
    // Obtenir la date et l'heure actuelles
    $date = new DateTime();

    // Formater la date et l'heure selon le format souhaité
    $uniqueCode = $date->format('YmdHis') . sprintf('%04d', rand(0, 9999));

    // S'assurer que le code est une chaîne de caractères
    return strval($uniqueCode);
}

// Exemple d'utilisation
//$codeUnique = generateUniqueCode();
//echo $codeUnique;