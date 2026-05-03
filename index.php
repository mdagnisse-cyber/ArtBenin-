<?php
// -------------------------------
// CONFIGURATION DE LA BASE DE DONNÉES
// -------------------------------
if ($_SERVER['HTTP_HOST'] === 'localhost') {
    define('DB_SERVER', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'if0_39714282_benin_art');
} else {
    define('DB_SERVER', 'sql202.infinityfree.com');
    define('DB_USERNAME', 'if0_39714282');
    define('DB_PASSWORD', 'MonDomaine1234');
    define('DB_NAME', 'if0_39714282_benin_art');
}

$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8mb4");

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Art Numérique du Bénin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="style2.css">
<style>
</style>
</head>
<body>
<header>
    <div class="header-inner">
        <div class="logo-title">
            <span class="logo"><i class="fa-solid fa-palette"></i></span>
            <span class="site-title">Art Numérique du Bénin</span>
        </div>
        <button class="menu-toggle" id="menuToggle"><i class="fa-solid fa-bars"></i></button>
        <nav class="responsive-nav" id="navMenu">
            <a href="index.php"><i class="fa-solid fa-house nav-icon"></i> Accueil</a>
            <a href="about.php"><i class="fa-solid fa-circle-info nav-icon"></i> À propos</a>
            <a href="contact.php"><i class="fa-solid fa-envelope nav-icon"></i> Contact</a>
            <a href="buy.php"><i class="fa-solid fa-cart-shopping nav-icon"></i> Acheter</a>
        </nav>
    </div>
</header>

<section class="hero">
    <h2>Découvrez le Bénin à travers l'art numérique QR code</h2>
    <p>Chaque œuvre est une porte ouverte vers la culture, l'histoire et les trésors du Bénin.</p>
</section>

<div class="container">
    <h2>Œuvres publiées</h2>
    <div class="art-container">
        <?php
        $res = mysqli_query($conn, "SELECT * FROM artworks ORDER BY created_at DESC");
        if ($res && mysqli_num_rows($res) > 0):
            while ($row = mysqli_fetch_assoc($res)): ?>
            <div class="art">
    <?php if(!empty($row['image_url'])): ?>
        <img src="../<?= htmlspecialchars($row['image_url']) ?>" 
             class="art-img" 
             alt="<?= htmlspecialchars($row['title']) ?>"
             onclick="openImage(this.src)">
    <?php endif; ?>
    <h3><?= htmlspecialchars($row['title']) ?></h3>
    <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
    
    <!-- Bouton achat -->
    <a href="contact.php?art=<?= urlencode($row['title']) ?>" class="art-btn">
        <i class="fa-solid fa-paper-plane"></i> Demander à acheter
    </a>

   <!-- Bouton ouvrir l'image QR -->
<?php if(!empty($row['image_url'])): ?>
    <a href="<?= '../'.htmlspecialchars($row['image_url']) ?>" target="_blank" class="art-qr-btn">
        <i class="fa-solid fa-qrcode"></i> Ouvrir / Scanner QR
    </a>
<?php endif; ?>
<style>
    .art .art-qr-btn {
    margin: 10px;
    padding: 8px 12px;
    background: #28a745;          /* vert pour différencier */
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    display: inline-flex;
    align-items: center;
    font-size: 0.9rem;
    transition: background 0.3s;
}

.art .art-qr-btn:hover {
    background: #1e7e34;          /* vert plus foncé au hover */
}

.art .art-qr-btn i {
    margin-right: 5px;            /* espace entre icône et texte */
}
</style>
</div>
            </div>
        <?php endwhile;
        else: ?>
            <p>Aucune œuvre n’a encore été publiée.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>&copy; <?= date("Y") ?> Art Numérique du Bénin</p>
</footer>

<script>
const menuToggle = document.getElementById("menuToggle");
const navMenu = document.getElementById("navMenu");

menuToggle.addEventListener("click", () => {
    navMenu.classList.toggle("open");
    menuToggle.innerHTML = navMenu.classList.contains("open") 
        ? '<i class="fa-solid fa-xmark"></i>' 
        : '<i class="fa-solid fa-bars"></i>';
});

function openImage(src){
    const imgWindow = window.open("", "_blank");
    imgWindow.document.write(`<img src="${src}" style="width:100%;height:auto;">`);
}
</script>
</body>
</html>
