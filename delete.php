<?php 
include "connect.php";
if ($_SESSION['role'] != 'admin') exit("Access Denied");

$stmt = $pdo->prepare("DELETE FROM product WHERE id_menu=?");
$stmt->execute([$_GET["id_menu"]]);
header("Location: index.php?page=admin_panel");
?>