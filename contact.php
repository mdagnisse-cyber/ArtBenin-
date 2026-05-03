<?php session_start(); 


// Affichage des messages de notification
if (isset($_SESSION['success'])) {
    echo '<div class="message success">'.htmlspecialchars($_SESSION['success']).'</div>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="message error">'.htmlspecialchars($_SESSION['error']).'</div>';
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nous contacter - Art Numérique du Bénin</title>
    
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
                <a href="contact.php" class="active"><i class="fa-solid fa-envelope nav-icon"></i> Contact</a>
                <a href="buy.php"><i class="fa-solid fa-cart-shopping nav-icon"></i> Acheter</a>
            </nav>
        </div>
    </header>

    <!-- SECTION CONTACT -->
    <main class="page-flex">
        
        <!-- Illustration -->
        <div class="page-illustration">
            <img src="assets/svg/message.svg" alt="Illustration contact">
        </div>

        <!-- Contenu -->
        <div class="page-content">
            <div class="container">
                <h2>Nous contacter</h2>
                <p>
                    Pour toute question, demande d'achat ou collaboration, 
                    n'hésitez pas à remplir le formulaire ci-dessous :
                </p>

                <!-- Messages de session -->
                <?php
                if(isset($_SESSION['success'])) {
                    echo '<div class="message success">'.htmlspecialchars($_SESSION['success']).'</div>';
                    unset($_SESSION['success']);
                }
                if(isset($_SESSION['error'])) {
                    echo '<div class="message error">'.htmlspecialchars($_SESSION['error']).'</div>';
                    unset($_SESSION['error']);
                }

                // Pré-remplir le message si un art est demandé
                $art = isset($_GET['art']) ? htmlspecialchars($_GET['art']) : '';
                $prefill = $art ? "Je souhaite acheter l'œuvre : $art" : "";
                ?>

                <!-- Formulaire -->
                <form method="post" action="contact_submit.php">
                    <input type="text" name="name" placeholder="Votre nom" required>
                    <input type="email" name="email" placeholder="Votre email" required>
                    <textarea name="message" placeholder="Votre message" required rows="5"><?= $prefill ?></textarea>

                    <?php if($art): ?>
                        <input type="hidden" name="art" value="<?= $art ?>">
                        <p><strong>Œuvre demandée :</strong> <?= $art ?></p>
                    <?php endif; ?>

                    <button type="submit">
                        <i class="fa-solid fa-paper-plane"></i> Envoyer
                    </button>
                </form>

            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <footer>
        <p>&copy; <?= date("Y") ?> Art Numérique du Bénin — Tous droits réservés.</p>
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
