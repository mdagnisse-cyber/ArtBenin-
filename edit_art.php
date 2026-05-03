<?php
if(session_status()===PHP_SESSION_NONE) session_start();
require 'init.php';

 $msg = ''; // initialisation pour éviter "Undefined variable"

// Vérifier connexion admin
if(!isset($_SESSION['admin_id'])){
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer l'œuvre
$stmt = $mysqli->prepare("SELECT * FROM artworks WHERE id=?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
$art = $res->fetch_assoc();
$stmt->close();

if(!$art){
    die("Œuvre non trouvée.");
}

// Traitement du formulaire
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $qr_link = trim($_POST['qr_link']);
    $imagePath = $art['image_url'];
    $msg = '';

    // Vérification du fichier image
    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0){
        $fileTmp = $_FILES['image']['tmp_name'];
        $fileName = $_FILES['image']['name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if(!in_array($fileExt, $allowed)){
            $msg = "Format d'image non autorisé !";
        } else {
            $uploadDir = __DIR__ . '/../uploads/';
            if(!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $newName = uniqid('art_') . '.' . $fileExt;
            $uploadPath = $uploadDir . $newName;

            if(move_uploaded_file($fileTmp, $uploadPath)){
                // Supprimer l'ancienne image si elle existe
                if(!empty($art['image_url']) && file_exists('../'.$art['image_url'])){
                    unlink('../'.$art['image_url']);
                }
                $imagePath = 'uploads/'.$newName;
            } else {
                $msg = "Erreur lors de l'upload de l'image.";
            }
        }
    }

    // Mise à jour en DB si aucune erreur
    if(empty($msg)){
        $stmt = $mysqli->prepare("UPDATE artworks SET title=?, description=?, qr_link=?, image_url=? WHERE id=?");
        $stmt->bind_param('ssssi', $title, $description, $qr_link, $imagePath, $id);
        if($stmt->execute()){
            // Stocker le message en session et rediriger
            $_SESSION['msg'] = "Œuvre mise à jour avec succès !";
            header("Location: dashboard.php?menu=artworks");
            exit();
        } else {
            $msg = "Erreur DB : ".$mysqli->error;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modifier une œuvre</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="style.css">
</head>
<body>
    
<button class="sidebar-toggle" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
<div class="sidebar" id="sidebar">
    <h2><i class="fa-solid fa-user-shield"></i> Admin</h2>
    <a href="?menu=home" class="<?= $menu==='home'?'active':'' ?>"><i class="fa-solid fa-gauge"></i> Accueil</a>
    <a href="?menu=publish" class="<?= $menu==='publish'?'active':'' ?>"><i class="fa-solid fa-plus"></i> Publier une œuvre</a>
    <a href="?menu=artworks" class="<?= $menu==='artworks'?'active':'' ?>"><i class="fa-solid fa-image"></i> Œuvres publiées</a>
    <a href="?menu=messages" class="<?= $menu==='messages'?'active':'' ?>"><i class="fa-solid fa-envelope"></i> Messages / Achats</a>
    <a href="logout.php"><i class="fa-solid fa-sign-out-alt"></i> Déconnexion</a>
</div>

<h2>Modifier l'œuvre</h2>
<div class="page-flex">
    <div class="page-illustration">
        <img src="../assets/svg/edit.svg" alt="Description">
    </div>
    <div class="page-content">
        <!-- Contenu principal de la page -->
        <?php if($msg): ?><p class="message"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" value="<?= htmlspecialchars($art['title']) ?>" placeholder="Titre" required>
    <textarea name="description" placeholder="Description" rows="5" required><?= htmlspecialchars($art['description']) ?></textarea>
    <input type="url" name="qr_link" value="<?= htmlspecialchars($art['qr_link']) ?>" placeholder="Lien QR code" required>
    <?php if(!empty($art['image_url'])): ?>
        <img src="../<?= htmlspecialchars($art['image_url']) ?>" class="art-img" alt="<?= htmlspecialchars($art['title']) ?>">
    <?php endif; ?>
    <input type="file" name="image" accept="image/*">
    <button type="submit">Mettre à jour</button>
</form>
<p style="text-align:center;"><a href="dashboard.php?menu=artworks">← Retour aux œuvres</a></p>
<style>
 /* Appliquer box-sizing pour éviter débordements */
*,
*::before,
*::after {
    box-sizing: border-box;
}

/* Conteneur général pour la page */
.page-flex {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin: 30px 0;
    align-items: flex-start;
}

/* Illustration */
.page-illustration {
    flex: 0 0 120px;
    max-width: 120px;
    text-align: center;
}

.page-illustration img,
.page-illustration svg {
    max-width: 100%;
    width: 100%;
    height: auto;
    display: block;
    filter: drop-shadow(0 4px 16px rgba(34,34,34,0.1));
}

/* Contenu principal */
.page-content {
    flex: 1 1 300px;
    min-width: 250px;
    background-color: #fff8dc; /* light gold */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

/* Titres et messages */
.page-content h2,
.page-content h1 {
    color: #bfa100; /* gold-dark */
    margin-bottom: 15px;
}

.page-content p {
    margin-bottom: 15px;
    color: #333;
}

.page-content p.message {
    background-color: #ffd70033;
    padding: 10px 12px;
    border-radius: 6px;
    font-weight: 500;
}

/* Formulaire */
.page-content form {
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.page-content input[type="text"],
.page-content input[type="url"],
.page-content textarea,
.page-content input[type="file"] {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #bfa100;
    border-radius: 6px;
    font-size: 1em;
    color: #333;
    background-color: #fff;
    transition: border 0.2s, box-shadow 0.2s;
}

.page-content input[type="text"]:focus,
.page-content input[type="url"]:focus,
.page-content textarea:focus,
.page-content input[type="file"]:focus {
    outline: none;
    border-color: #ffd700; /* gold */
    box-shadow: 0 0 4px rgba(255,215,0,0.5);
}

/* Image de l'œuvre */
.page-content .art-img {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    margin-bottom: 10px;
}

/* Bouton Mettre à jour */
.page-content button[type="submit"] {
    padding: 12px;
    border: none;
    background-color: #bfa100;
    color: #fff;
    font-weight: bold;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s;
}

.page-content button[type="submit"]:hover {
    background-color: #ffd700;
    color: #bfa100;
}

/* Lien retour */
.page-content a {
    color: #bfa100;
    text-decoration: none;
    font-weight: 500;
}

.page-content a:hover {
    text-decoration: underline;
}

/* Sidebar et toggle */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 220px;
    height: 100%;
    background-color: #bfa100;
    color: #fff;
    padding-top: 20px;
    transition: transform 0.3s;
}

.sidebar h2 {
    text-align: center;
    margin-bottom: 20px;
}

.sidebar a {
    display: block;
    color: #fff;
    padding: 12px 20px;
    text-decoration: none;
    margin-bottom: 5px;
    border-radius: 6px;
}

.sidebar a.active,
.sidebar a:hover {
    background-color: #ffd700;
    color: #bfa100;
}

.sidebar-toggle {
    position: fixed;
    top: 15px;
    left: 15px;
    font-size: 1.2em;
    background: none;
    border: none;
    color: #bfa100;
    cursor: pointer;
    z-index: 1000;
    display: none;
}

/* Contenu principal */
.content {
    margin-left: 220px;
    padding: 20px;
}

/* Footer */
.admin-footer {
    background-color: #bfa100;
    color: #fff;
    padding: 12px 20px;
    text-align: center;
    border-radius: 0 0 10px 10px;
}

.admin-footer a {
    color: #fff;
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 900px) {
    .sidebar {
        transform: translateX(-100%);
    }

    .sidebar.open {
        transform: translateX(0);
    }

    .sidebar-toggle {
        display: block;
    }

    .page-flex {
        flex-direction: column;
        align-items: stretch;
        gap: 18px;
    }

    .page-illustration {
        max-width: 160px;
        margin: 0 auto;
        justify-content: center;
    }

    .content {
        margin-left: 0;
        padding: 15px 10px 20px 10px;
    }
}

</style>
</body>
    </div>
</div>
<footer class="admin-footer">
    <p>
        &copy; <?= date("Y") ?> Art Numérique du Bénin — Administration
        | <a href="index.php"><i class="fa-solid fa-arrow-left"></i> Retour au site</a>
    </p>
</footer>
</html>
