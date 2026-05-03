<?php
require_once __DIR__ . '/../init.php';

$fn = basename($_GET['f'] ?? '');
if (!preg_match('/^[a-f0-9]{32}\.[a-zA-Z0-9]{1,6}$/', $fn)) { http_response_code(400); exit; }

$path = UPLOADS_DIR . $fn;
if (!is_file($path)) { http_response_code(404); exit; }

$mime = mime_content_type($path);
$allowed = ['image/jpeg','image/png','image/webp'];
if (!in_array($mime, $allowed)) { http_response_code(403); exit; }

header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($path));
header('Cache-Control: public, max-age=86400');
readfile($path);
