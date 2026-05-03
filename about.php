<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>À propos - Art Numérique du Bénin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="style2.css">
</head>
<body>

<header>
   <div class="header-inner">
        <div class="logo-title">
            <span class="logo"><i class="fa-solid fa-palette"></i></span>
            <span class="site-title">Art Numérique du Bénin</span>
        </div>
        <button class="menu-toggle" id="menuToggle">
            <i class="fa-solid fa-bars"></i>
        </button>
        <nav class="responsive-nav" id="navMenu">
            <a href="index.php"><i class="fa-solid fa-house nav-icon"></i> Accueil</a>
            <a href="about.php" class="active"><i class="fa-solid fa-circle-info nav-icon"></i> À propos</a>
            <a href="contact.php"><i class="fa-solid fa-envelope nav-icon"></i> Contact</a>
            <a href="buy.php"><i class="fa-solid fa-cart-shopping nav-icon"></i> Acheter</a>
        </nav>
    </div>
</header>

<div class="page-flex">
    <div class="page-illustration">
        <img src="assets/svg/about.svg" alt="Illustration à propos">
    </div>
    <div class="page-content">
        <div class="container">
            <h2>À propos de l’artiste</h2>
            <p>Bienvenue sur mon espace dédié à l’art numérique du Bénin. Chaque œuvre est créée avec passion et s’inspire de la culture, de l’histoire et des traditions béninoises.</p>
            <p>Mon objectif est de faire découvrir le patrimoine béninois à travers des créations interactives et immersives utilisant des QR codes.</p>
        </div>
    </div>
</div>

<script>
document.getElementById("menuToggle").addEventListener("click", function() {
    document.getElementById("navMenu").classList.toggle("open");
});
</script>

<footer>
    <p>&copy; <?= date("Y") ?> Art Numérique du Bénin | <a href="login.php">🔒 Administration</a></p>
</footer>

</body>
</html>
