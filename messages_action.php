<?php
require '../init.php';
if(!isset($_SESSION['admin_id'])) exit();

// Suppression multiple
if(isset($_POST['delete_selected_messages']) && !empty($_POST['message_ids'])){
    $ids = array_map('intval', $_POST['message_ids']);
   header("Location: dashboard.php?menu=messages");
    exit();
}
?>
