<?php

// Requête pour récupérer les artworks
$res = mysqli_query($conn, "SELECT * FROM artworks ORDER BY created_at DESC");

if (!$res) {
    die("Erreur lors de la récupération des artworks : " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acheter un art - Art Numérique du Bénin</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Feuille de style -->
    <link rel="stylesheet" href="style2.css">
</head>
<body>

    <!-- HEADER -->
    <header>
        <div class="header-inner">
            <div class="logo-title">
                <span class="logo"><i class="fa-solid fa-palette"></i></span>
                <span class="site-title">Art Numérique du Bénin</span>
            </div>

            <!-- Bouton burger mobile -->
            <button class="menu-toggle" aria-label="Ouvrir le menu">
                <i class="fa-solid fa-bars"></i>
            </button>

            <!-- Navigation -->
            <nav>
                <a href="index.php"><i class="fa-solid fa-house nav-icon"></i> Accueil</a>
                <a href="about.php"><i class="fa-solid fa-circle-info nav-icon"></i> À propos</a>
                <a href="contact.php"><i class="fa-solid fa-envelope nav-icon"></i> Contact</a>
                <a href="buy.php" class="active"><i class="fa-solid fa-cart-shopping nav-icon"></i> Acheter</a>
            </nav>
        </div>
    </header>

    <!-- SECTION ACHAT -->
    <main class="page-flex">
        <!-- Illustration -->
        <div class="page-illustration">
            <img src="assets/svg/test.svg" alt="Illustration achat">
        </div>

        <!-- Contenu -->
        <div class="page-content">
            <div class="container">
                <h2>Demander l'achat d'une œuvre</h2>

            <div class="art-container">
<?php if ($res && mysqli_num_rows($res) > 0): ?>
    <?php while($row = mysqli_fetch_assoc($res)): ?>
        <div class="art">
            <?php if(!empty($row['image_url'])): ?>
                <img src="../<?= htmlspecialchars($row['image_url']) ?>" class="art-img" alt="<?= htmlspecialchars($row['title']) ?>">
            <?php endif; ?>
            <h3><?= htmlspecialchars($row['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>

            <div class="art-buttons">
                <!-- Bouton Demander à acheter -->
                <a href="contact.php?art=<?= urlencode($row['title']) ?>" class="art-btn">
                    <i class="fa-solid fa-paper-plane"></i> Demander à acheter
                </a>

                <!-- Bouton Ouvrir / Scanner QR -->
                <?php if(!empty($row['image_url'])): ?>
                <a href="../<?= htmlspecialchars($row['image_url']) ?>" target="_blank" class="art-qr-btn">
                    <i class="fa-solid fa-qrcode"></i> Ouvrir / Scanner QR
                </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>Aucune œuvre disponible à l'achat.</p>
<?php endif; ?>
</div>
<style>
    .art .art-img {
    width: 100%;       /* largeur totale du conteneur */
    height: auto;      /* hauteur proportionnelle */
    border-radius: 6px;
    object-fit: contain;
}

.art .art-buttons {
    margin-top: 10px;
    display: flex;
    gap: 10px;        /* espace entre les boutons */
    flex-wrap: wrap;
}

.art .art-qr-btn {
    padding: 8px 12px;
    background: #28a745;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    display: inline-flex;
    align-items: center;
    font-size: 0.9rem;
    transition: background 0.3s;
}

.art .art-qr-btn:hover {
    background: #1e7e34;
}

.art .art-qr-btn i {
    margin-right: 5px;
}

</style>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <p>&copy; <?= date("Y") ?> Art Numérique du Bénin | <a href="login.php">🔒 Administration</a></p>
    </footer>

    <!-- Script JS pour menu mobile -->
    <script>
        const toggleBtn = document.querySelector('.menu-toggle');
        const nav = document.querySelector('header nav');

        toggleBtn.addEventListener('click', () => {
            nav.classList.toggle('open');
        });
    </script>
</body>
</html>
