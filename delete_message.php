<?php
require 'init.php';
if(!isset($_SESSION['admin_id'])) exit();
if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $mysqli->query("DELETE FROM contacts WHERE id=$id");
}
header("Location: dashboard.php?menu=messages");
exit();
?>
