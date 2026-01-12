<?php
require 'functions.php';

// Cek apakah ada id di URL
if (!isset($_GET["id"])) {
  header("Location: index.php");
  exit;
}

$id = $_GET["id"];

// Lakukan Query Hapus
// (Pastikan nama tabel 'surat_masuk' dan kolom 'id' sesuai database)
if (mysqli_query($conn, "DELETE FROM surat_masuk WHERE id = $id")) {
  echo "
        <script>
            alert('Data berhasil dihapus!');
            document.location.href = 'index.php';
        </script>
    ";
} else {
  echo "
        <script>
            alert('Data gagal dihapus!');
            document.location.href = 'index.php';
        </script>
    ";
}
