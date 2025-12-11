<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['id_user'])) {
  echo "login_required";
  exit;
}

$id_user = $_SESSION['id_user'];
$id_wisata = intval($_POST['id_wisata'] ?? 0);

if ($id_wisata <= 0) {
  echo "invalid_id";
  exit;
}

// Cek apakah sudah favorit
$cek = mysqli_query($koneksi, "SELECT * FROM favorit WHERE id_user='$id_user' AND id_wisata='$id_wisata'");
if (mysqli_num_rows($cek) > 0) {
  mysqli_query($koneksi, "DELETE FROM favorit WHERE id_user='$id_user' AND id_wisata='$id_wisata'");
  echo "removed";
} else {
  mysqli_query($koneksi, "INSERT INTO favorit (id_user, id_wisata) VALUES ('$id_user', '$id_wisata')");
  echo "added";
}
?>