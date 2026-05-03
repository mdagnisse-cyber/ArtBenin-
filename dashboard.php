<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require __DIR__ . '/init.php'; 

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$menu = isset($_GET['menu']) ? $_GET['menu'] : 'home';

if ($menu === 'artworks' || $menu === 'publish') {
    $artworks = $mysqli->query("SELECT * FROM artworks ORDER BY created_at DESC");
}
if ($menu === 'messages') {
    $contacts = $mysqli->query("SELECT * FROM contacts ORDER BY created_at DESC");
}

// Statistiques pour le dashboard
$stats = [];

// Nombre d'œuvres publiées
$res = $mysqli->query("SELECT COUNT(*) AS total_artworks FROM artworks");
$row = $res->fetch_assoc();
$stats['artworks'] = $row['total_artworks'];

// Nombre de demandes/commandes
$res = $mysqli->query("SELECT COUNT(*) AS total_orders FROM contacts");
$row = $res->fetch_assoc();
$stats['orders'] = $row['total_orders'];


?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin - Art Numérique du Bénin</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
:root {
    --gold: #ffd700;
    --gold-dark: #bfa100;
    --gold-light: #fff8dc;
    --white: #fff;
}

/* Reset */
body, h1, h2, h3, p, ul, li, table, tr, td, th {
    margin: 0; padding: 0;
    font-family: Arial, sans-serif;
}

/* Sidebar */
.sidebar { 
    width: 220px;
    background: linear-gradient(180deg, var(--gold-dark), var(--gold));
    color: var(--white);
    display: flex;
    flex-direction: column;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 100;
    transition: transform 0.3s;
    box-shadow: 2px 0 10px rgba(34,34,34,0.08);
}
.sidebar h2 {
    text-align: center;
    margin: 2rem 0 1rem 0;
    font-size: 1.5em;
    color: var(--gold-light);
    letter-spacing: 1px;
}
.sidebar a {
    color: var(--white);
    text-decoration: none;
    padding: 0.8rem 1.2rem;
    display: block;
    border-bottom: 1px solid var(--gold-dark);
    transition: background 0.3s, color 0.3s;
    font-weight: 500;
}
.sidebar a:hover, .sidebar a.active {
    background: var(--gold-light);
    color: var(--gold-dark);
}

/* Content */
.content {
    margin-left: 220px;
    padding: 20px;
    min-height: calc(100vh - 60px);
}

/* Page flex */
.page-flex {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    margin: 30px 0 15px 0;
    flex-wrap: wrap;
}
.page-illustration {
    flex: 0 0 110px;
    max-width: 110px;
    min-width: 80px;
    text-align: center;
    display: flex;
    align-items: flex-start;
    justify-content: center;
}
.page-illustration img {
    max-width: 100%;
    height: auto;
    display: block;
    filter: drop-shadow(0 4px 16px rgba(34,34,34,0.10));
}
.page-content {
    flex: 1 1 250px;
    min-width: 0;
}

/* Tables desktop */
table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}
table th, table td {
    padding: 0.5rem 0.7rem;
    text-align: left;
    border: 1px solid #ddd;
}
table th {
    background: var(--gold-light);
    color: var(--gold-dark);
}
.art-img {
    width: 80px;
    border-radius: 5px;
}
.qr-btn, .edit-btn, .delete-btn {
    padding: 4px 6px;
    background: var(--gold-dark);
    color: #fff;
    border-radius: 4px;
    text-decoration: none;
    display: inline-block;
    font-size: 0.9rem;
    margin: 0 2px 2px 0;
}
.qr-btn:hover, .edit-btn:hover, .delete-btn:hover {
    background: var(--gold-light);
    color: var(--gold-dark);
}

/* Footer */
.admin-footer {
    background: var(--gold-dark);
    color: var(--white);
    text-align: center;
    padding: 10px;
    position: relative;
    width: 100%;
}
.admin-footer a {
    color: var(--white);
    text-decoration: none;
}
.admin-footer a:hover {
    text-decoration: underline;
}

/* Toggle sidebar */
.sidebar-toggle {
    display: none;
    position: fixed;
    top: 10px;
    left: 10px;
    background: var(--gold);
    border: none;
    color: var(--white);
    padding: 8px 10px;
    font-size: 1.2rem;
    border-radius: 4px;
    z-index: 200;
}
.sidebar {
    min-height: 100vh;      /* hauteur au moins égale à la fenêtre */
    max-height: 100vh;      /* ne dépasse pas la fenêtre */
    overflow-y: auto;       /* ajoute un scroll si le contenu est trop long */
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
    .content {
        margin-left: 0;
        padding: 15px;
    }
    .page-flex {
        flex-direction: column;
        align-items: stretch;
    }
    .page-illustration {
        max-width: 160px;
        margin: 0 auto;
    }

}
@media (max-width: 900px) {
    table, thead, tbody, th, td, tr {
        display: block;
        width: 100%;
    }

    thead {
        display: none; /* On masque l'entête classique */
    }

    tr {
        margin-bottom: 15px;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
        background-color: #fff8dc; /* léger fond pour distinguer chaque ligne */
    }

    td {
        padding: 6px 10px;
        position: relative;
        text-align: left;
        border: none;
        display: flex;
        flex-direction: column; /* label au-dessus du contenu */
    }

    td::before {
        content: attr(data-label);
        font-weight: bold;
        margin-bottom: 4px; /* espace entre label et contenu */
        color: #bfa100; /* couleur label, ton gold-dark */
        display: block;
    }

    .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
    }
}

</style>

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

<div class="content">
<?php if($menu==='home'): ?>
<div class="dashboard-home">

    <div class="page-flex">
        <!-- Illustration -->
        <div class="page-illustration">
            <img src="assets/svg/accueil.svg" alt="Dashboard illustration">
        </div>

        <!-- Contenu principal -->
        <div class="page-content">
            <h1>Bienvenue dans le <span class="highlight">Dashboard Admin</span></h1>
            <p>Gérez vos œuvres, publiez de nouvelles créations et consultez les messages ou demandes d'achat depuis ce tableau de bord.</p>

            <!-- Actions rapides -->
            <div class="quick-actions">
                <a href="?menu=artworks" class="action-btn"><i class="fa-solid fa-paint-brush"></i> Gérer les œuvres</a>
                <a href="?menu=publish" class="action-btn"><i class="fa-solid fa-plus-circle"></i> Ajouter une œuvre</a>
                <a href="?menu=messages" class="action-btn"><i class="fa-solid fa-envelope"></i> Voir les messages</a>
            </div>
        </div>
    </div>
<div class="dashboard-stats">
    <div class="stat-card artworks">
        <i class="fa-solid fa-image"></i>
        <div class="stat-info">
            <h3><?= $stats['artworks'] ?></h3>
            <p>Œuvres publiées</p>
        </div>
    </div>

    <div class="stat-card orders">
        <i class="fa-solid fa-cart-shopping"></i>
        <div class="stat-info">
            <h3><?= $stats['orders'] ?></h3>
            <p>Demandes/achats</p>
        </div>
    </div>

    
</div>


</div>
<style>
    .dashboard-home .page-flex {
    display: flex;
    gap: 40px;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
}

.page-illustration img {
    max-width: 400px;
    width: 100%;
    border-radius: 10px;
}

.page-content h1 {
    font-size: 2rem;
    margin-bottom: 15px;
}

.page-content .highlight {
    color: #007bff;
}

.page-content p {
    font-size: 1rem;
    line-height: 1.6;
    color: #333;
}

.quick-actions {
    margin-top: 20px;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    padding: 10px 15px;
    background: #28a745;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s;
}

.action-btn i {
    margin-right: 8px;
}

.action-btn:hover {
    background: #1e7e34;
}

.dashboard-stats {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin: 20px 0;
}

.stat-card {
    flex: 1 1 200px;
    background: #f5f5f5;
    padding: 20px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card i {
    font-size: 40px;
    color: #4CAF50; /* tu peux changer selon le type */
}

.stat-card.artworks i { color: #2196F3; }
.stat-card.orders i { color: #FF9800; }
.stat-card.users i { color: #9C27B0; }

.stat-info h3 {
    margin: 0;
    font-size: 24px;
}

.stat-info p {
    margin: 0;
    font-size: 14px;
    color: #555;
}

</style>
<?php elseif($menu==='publish'): ?>
<div class="page-flex">
    <div class="page-illustration">
        <img src="assets/svg/publish.svg" alt="Description">
    </div>
    <div class="page-content">
        <h1>Publier une nouvelle œuvre</h1>
        <form method="post" enctype="multipart/form-data" action="publish.php">
            <input type="text" name="title" placeholder="Titre" required><br>
            <textarea name="description" placeholder="Description" rows="5" required></textarea><br>
            <input type="url" name="qr_link" placeholder="Lien QR code" required><br>
            <input type="file" name="image" accept="image/*" required><br>
            <button type="submit">Publier</button>
        </form>
    </div>
    <style>
  /* Appliquer box-sizing à tout pour éviter débordement */
.page-content, 
.page-content * {
    box-sizing: border-box;
}

/* Conteneur général pour la section Publish */
.page-flex {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin: 30px 0;
    align-items: flex-start;
}

.page-illustration {
    flex: 0 0 120px;
    max-width: 120px;
    text-align: center;
}

.page-illustration img {
    max-width: 100%;
    height: auto;
    display: block;
    filter: drop-shadow(0 4px 16px rgba(34, 34, 34, 0.1));
}

.page-content {
    flex: 1 1 300px;
    min-width: 250px;
    background-color: #fff8dc; /* couleur light gold */
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

/* Titres et textes */
.page-content h1 {
    color: #bfa100; /* gold-dark */
    margin-bottom: 15px;
}

.page-content p {
    margin-bottom: 15px;
    color: #333;
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
    box-sizing: border-box; /* ✅ Correction débordement */
}

.page-content input[type="text"]:focus,
.page-content input[type="url"]:focus,
.page-content textarea:focus,
.page-content input[type="file"]:focus {
    outline: none;
    border-color: #ffd700; /* gold */
    box-shadow: 0 0 4px rgba(255,215,0,0.5);
}

/* Bouton Publier */
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

/* Responsive */
@media (max-width: 900px) {
    .page-flex {
        flex-direction: column;
        align-items: stretch;
    }

    .page-illustration {
        max-width: 160px;
        margin: 0 auto 20px auto;
    }

    .page-content {
        padding: 15px;
    }
}

    </style>
</div>
<?php elseif($menu==='artworks'): ?>
<section>
<div class="page-flex">
    <div class="page-illustration">
        <img src="assets/svg/art.svg" alt="Description">
    </div>
    <div class="page-content">
        <h1>Œuvres publiées</h1>
        <?php if($artworks && $artworks->num_rows>0): ?>
        <form method="post" action="artworks_action.php">
        <table>
            <thead>
            <tr>
                <th>Titre</th>
                <th>Description</th>
                <th>Image</th>
                <th>QR Code</th>
                <th>Publié le</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while($row=$artworks->fetch_assoc()): ?>
            <tr>
                <td data-label="Titre"><?= htmlspecialchars($row['title']) ?></td>
                <td data-label="Description"><?= nl2br(htmlspecialchars($row['description'])) ?></td>
                <td data-label="Image"><?php if(!empty($row['image_url'])): ?><img src="../<?= htmlspecialchars($row['image_url']) ?>" class="art-img"><?php endif; ?></td>
                <td data-label="QR Code"><a href="<?= htmlspecialchars($row['qr_link']) ?>" class="qr-btn" target="_blank"><i class="fa-solid fa-qrcode"></i> Voir QR</a></td>
                <td data-label="Publié le"><?= $row['created_at'] ?></td>
                <td data-label="Actions">
                    <a href="edit_art.php?id=<?= $row['id'] ?>" class="edit-btn"><i class="fa-solid fa-pen-to-square"></i> Modifier</a>
                    <a href="delete_art.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Supprimer cette œuvre ?')"><i class="fa-solid fa-trash"></i> Supprimer</a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </form>
        <?php else: ?>
        <p>Aucune œuvre publiée.</p>
        <?php endif; ?>
    </div>
</div>
</section>
<?php elseif($menu==='messages'): ?>
<div class="page-flex">
    <div class="page-illustration">
        <img src="assets/svg/lettre.svg" alt="Messages illustration">
    </div>
    <div class="page-content">
        <h1>Messages reçus</h1>
        <?php if($contacts && $contacts->num_rows>0): ?>
        <form method="post" action="messages_action.php">
        <table>
            <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Œuvre demandée</th>
                <th>Message</th>
                <th>Reçu le</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while($row=$contacts->fetch_assoc()): ?>
            <tr>
                <td data-label="Nom"><?= htmlspecialchars($row['name']) ?></td>
                <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
                <td data-label="Œuvre demandée"><?= isset($row['art']) ? htmlspecialchars($row['art']) : '' ?></td>
                <td data-label="Message"><?= nl2br(htmlspecialchars($row['message'])) ?></td>
                <td data-label="Reçu le"><?= $row['created_at'] ?></td>
                <td data-label="Actions">
                    <a href="delete_message.php?id=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Supprimer ce message ?')"><i class="fa-solid fa-trash"></i> Supprimer</a>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        </form>
        <?php else: ?>
        <p>Aucun message reçu.</p>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
</div>

<footer class="admin-footer">
    <p>&copy; <?= date("Y") ?> Art Numérique du Bénin — Administration | <a href="index.php"><i class="fa-solid fa-arrow-left"></i> Retour au site</a></p>
</footer>

<script>
const sidebar = document.getElementById('sidebar');
const toggle = document.getElementById('sidebarToggle');
if (sidebar && toggle) {
    toggle.addEventListener('click', () => {
        sidebar.classList.toggle('open');
    });
}
</script>

</body>
</html>
