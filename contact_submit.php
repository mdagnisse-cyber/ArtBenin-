<?php
session_start();
/*$conn = mysqli_connect(
    "sql202.infinityfree.com",       // Hostname fourni
    "if0_39714282",                  // Nom utilisateur MySQL
    "MonDomaine1234",              // Mot de passe MySQL
    "if0_39714282_benin_art"       // Nom exact de la base
);
*/

// Détection environnement : local ou en ligne
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'if0_39714282_benin_art'); // même nom que ligne
} else {
    define('DB_SERVER', 'sql202.infinityfree.com');
    define('DB_USERNAME', 'if0_39714282');
    define('DB_PASSWORD', 'MonDomaine1234');
    define('DB_NAME', 'if0_39714282_benin_art');
}

// Connexion
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);
    $art = isset($_POST['art']) ? $_POST['art'] : '';

    if ($name && $email && $message) {
        $stmt = $conn->prepare("INSERT INTO contacts (name,email,message,art) VALUES(?,?,?,?)");
        $stmt->bind_param("ssss",$name,$email,$message,$art);
        if($stmt->execute()){
            $_SESSION['success'] = "Message envoyé avec succès !";
        } else {
            $_SESSION['error'] = "Erreur lors de l'envoi : ".$conn->error;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Veuillez remplir tous les champs.";
    }
} else {
    $_SESSION['error'] = "Accès direct interdit.";
}

header("Location: contact.php");
exit();
