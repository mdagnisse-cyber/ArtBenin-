<?php
require 'init.php';
if(!isset($_SESSION['admin_id'])) exit();

if(isset($_POST['delete_selected_arts']) && !empty($_POST['art_ids'])){
    $ids = array_map('intval', $_POST['art_ids']);
    $in = implode(',', $ids);
    $mysqli->query("DELETE FROM artworks WHERE id IN ($in)");
    header("Location: dashboard.php?menu=artworks");
    exit();
}
?>
