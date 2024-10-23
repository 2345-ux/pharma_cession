<?php
// add_fournisseur.php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=phama_cession;charset=utf8",
        "votre_utilisateur",
        "votre_mot_de_passe",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $code = $_POST['code'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $tel = $_POST['tel'] ?? '';
    $adre = $_POST['adre'] ?? '';
    $email = $_POST['email'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO t_fournisseurs (code, nom, tel, adre, email) VALUES (:code, :nom, :tel, :adre, :email)");

    $stmt->execute([
        ':code' => $code,
        ':nom' => $nom,
        ':tel' => $tel,
        ':adre' => $adre,
        ':email' => $email
    ]);

    echo json_encode([
        'status' => 'success',
        'message' => 'Fournisseur ajouté avec succès'
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Erreur: ' . $e->getMessage()
    ]);
}
