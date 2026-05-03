<?php
require 'init.php';

$result = $mysqli->query("SELECT titre, description, created_at FROM oeuvres ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head><meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Œuvres</title></head>
<body>
<h2>Œuvres publiées</h2>
<?php while ($row = $result->fetch_assoc()): ?>
    <div style="border:1px solid #ccc; margin:10px; padding:10px;">
        <h3><?= htmlspecialchars($row['titre']) ?></h3>
        <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
        <small>Publié le <?= $row['created_at'] ?></small>
    </div>
<?php endwhile; ?>
</body>
</html>
