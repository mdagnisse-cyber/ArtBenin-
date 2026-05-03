<?php
// ============================
// CONFIG GLOBAL
// ============================

// URL de base
$base_url = "/art/public/";

// Chemin absolu du dossier uploads (hors webroot)
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    define('UPLOADS_DIR', __DIR__ . '/../uploads/'); // local
} else {
    define('UPLOADS_DIR', '/var/www/uploads_benin_art/'); // en ligne
}

// ============================
// BASE DE DONNÉES
// ============================
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    // Local XAMPP/WAMP
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');        // user local
    define('DB_PASSWORD', '');            // mot de passe local
    define('DB_NAME', 'if0_39714282_benin_art'); // même nom que ligne
} else {
    // En ligne (InfinityFree)
    define('DB_SERVER', 'sql202.infinityfree.com');
    define('DB_USERNAME', 'if0_39714282');
    define('DB_PASSWORD', 'MonDomaine1234');
    define('DB_NAME', 'if0_39714282_benin_art');
}

// Connexion MySQLi
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_error) {
    die("Erreur de connexion : " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

// ============================
// PARAMÈTRES UPLOAD
// ============================
define('SERVE_IMAGE_PATH', $base_url . 'serve_image.php?f=');
define('MAX_UPLOAD_BYTES', 2 * 1024 * 1024); // 2MB
$ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/webp'];

// ============================
// SESSION
// ============================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
