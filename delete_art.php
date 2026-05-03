<?php
require 'init.php';
if(!isset($_SESSION['admin_id'])) exit();

if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    // Supprimer aussi le fichier image si nécessaire
    $res = $mysqli->query("SELECT image_url FROM artworks WHERE id=$id");
    if($res && $row=$res->fetch_assoc()){
        if(!empty($row['image_url']) && file_exists('../'.$row['image_url'])){
            unlink('../'.$row['image_url']);
        }
    }
    $mysqli->query("DELETE FROM artworks WHERE id=$id");
}
header("Location: dashboard.php?menu=artworks");
exit();
?>
