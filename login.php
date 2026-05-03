<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require __DIR__ . '/init.php';



$error = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = trim($_POST['username']);
    $pass = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, password_hash FROM admins WHERE username = ?");
    $stmt->bind_param('s', $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['password_hash'])) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['username'] = $user;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Mot de passe incorrect";
        }
    } else {
        $error = "Utilisateur introuvable";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion Admin</title>
<link rel="stylesheet" href="style2.css">
</style>
</head>
<body>
<div class="page-flex">
    <div class="page-illustration">
        <img src="assets/svg/auth.svg" alt="Description">
    </div>
    <div class="page-content">
        <p>Vous êtes sur la page de connexion admin. Veuillez entrez vos identifiants pour accéder aux fonctionnalités admin</p>
        <form method="post">
            <h2>Connexion Admin</h2>
            <?php if($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</div>
<style>
    .page-flex {
    display: flex;
    align-items: flex-start;
    gap: 32px;
    margin: 30px 0 15px 0;
    flex-wrap: wrap;
}

.page-illustration {
    flex: 0 0 110px;
    max-width: 110px;
    min-width: 80px;
    text-align: center;
    margin: 0;
    display: flex;
    align-items: flex-start;
    justify-content: center;
}

.page-illustration img, .page-illustration svg {
    max-width: 100%;
    width: 100%;
    height: auto;
    display: block;
    filter: drop-shadow(0 4px 16px rgba(34,34,34,0.10));
}

.page-content {
    flex: 1 1 250px;
    min-width: 0;
}

@media (max-width: 900px) {
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s;
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
}
@media (min-width: 901px) {
    .sidebar-toggle {
        display: none;
    }
    .sidebar {
        transform: none !important;
    }
}

</style>
<footer class="admin-footer">
    <p>
        &copy; <?= date("Y") ?> Art Numérique du Bénin — Administration
        | <a href="index.php"><i class="fa-solid fa-arrow-left"></i> Retour au site</a>
    </p>
</footer>
</body>
</html>
