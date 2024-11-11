<?php
// add_categories.php
header('Content-Type: application/json');

try {
    $pdo = new PDO("mysql:host=localhost;dbname=pharma_cession;charset=utf8", "saw24", "saw24", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $nom = $_POST['nom'] ?? '';

    // Vérification si la catégorie existe déjà
    $stmt = $pdo->prepare("SELECT id FROM t_categories WHERE nom = :nom");
    $stmt->execute([':nom' => $nom]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'La catégorie existe déjà.']);
        exit;
    }

    // Génération du code si nécessaire
    $code = generateUniqueCode();

    // Insertion de la nouvelle catégorie
    $stmt = $pdo->prepare("INSERT INTO t_categories (code, nom) VALUES (:code, :nom)");
    $stmt->execute([
        ':code' => $code,
        ':nom' => $nom,
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Catégorie ajoutée avec succès !']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erreur: ' . $e->getMessage()]);
}

// Fonction pour générer un code unique

function generateUniqueCode()
{
    // Obtenir la date et l'heure actuelles
    $date = new DateTime();

    // Formater la date et l'heure selon le format souhaité
    $uniqueCode = $date->format('YmdHis') . sprintf('%04d', rand(0, 9999));

    // S'assurer que le code est une chaîne de caractères
    return strval($uniqueCode);
}
