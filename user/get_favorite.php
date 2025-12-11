<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['id_user'])) {
  echo json_encode([]);
  exit;
}

$id_user = $_SESSION['id_user'];
$favorites = [];

$query = mysqli_query($koneksi, "SELECT id_wisata FROM favorit WHERE id_user = '$id_user'");
while ($row = mysqli_fetch_assoc($query)) {
  $favorites[] = intval($row['id_wisata']);
}

header('Content-Type: application/json');
echo json_encode($favorites);
?>