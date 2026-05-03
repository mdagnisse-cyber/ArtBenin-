<?php 
if (session_status() === PHP_SESSION_NONE) session_start();
require 'init.php'; // connexion $mysqli

// Vérifier admin
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$msg = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $qr_link = trim($_POST['qr_link']);

    // Vérification fichier image
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $fileTmp = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if(!in_array($fileExt, $allowed)){
            $msg = "Format d'image non autorisé !";
        } else {
            // stockage réel
            $uploadDir = __DIR__ . '/uploads/';
            if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $newName = uniqid('art_') . '.' . $fileExt;
            $uploadPath = $uploadDir . $newName;

            if(move_uploaded_file($fileTmp, $uploadPath)){
                // chemin relatif pour DB
                $imagePath = 'uploads/' . $newName;

                // insertion en DB
                $stmt = $mysqli->prepare("INSERT INTO artworks (title, description, qr_link, image_url, created_at) VALUES (?,?,?,?,NOW())");
                $stmt->bind_param('ssss', $title, $description, $qr_link, $imagePath);
                
                if($stmt->execute()){
                    // ✅ Succès : message et redirection après 2 sec
                    echo "<script>
                        alert('Œuvre publiée avec succès !');
                        window.location.href='dashboard.php?menu=artworks';
                    </script>";
                    exit();
                } else {
                    $msg = "Erreur DB : ".$mysqli->error;
                }
                $stmt->close();
            } else {
                $msg = "Erreur lors de l'upload de l'image.";
            }
        }
    } else {
        $msg = "Veuillez sélectionner une image.";
    }
}

// Si $msg contient une erreur, on l’affiche
if($msg){
    echo "<script>alert('".addslashes($msg)."');</script>";
}
?>
