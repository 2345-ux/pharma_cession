<?php
header('Content-Type: application/json');

$files = [
    'get_produits_rupture.php',
    'get_produits_peremption.php',
    'get_count_produits_rupture.php',
    'get_count_produits_peremption.php',
    'acceuil_get_montant_total.php',
    'acceuil_get_benifices.php'
];

$statuses = [];

foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    $status = [
        'file' => $file,
        'exists' => file_exists($path),
        'readable' => is_readable($path),
        'size' => file_exists($path) ? filesize($path) : 0,
        'last_modified' => file_exists($path) ? date("Y-m-d H:i:s", filemtime($path)) : null
    ];
    
    // Vérifier si le fichier est accessible via HTTP
    $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/' . $file;
    $headers = @get_headers($url);
    $status['accessible'] = $headers && strpos($headers[0], '200') !== false;
    
    $statuses[] = $status;
}

echo json_encode([
    'status' => 'success',
    'timestamp' => date('Y-m-d H:i:s'),
    'files' => $statuses
]);
