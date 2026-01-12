<?php
// 1. NYALAKAN ERROR REPORTING (Agar tidak layar putih)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'functions.php';

// 2. CEK APAKAH ADA ID DI URL
if (!isset($_GET["id"])) {
  // Jika tidak ada id, kembalikan ke index
  header("Location: index.php");
  exit;
}

// 3. AMBIL DATA LAMA
$id = $_GET["id"];
$data_surat = query("SELECT * FROM surat_masuk WHERE id = $id");

// Cek apakah data ditemukan (mencegah error jika ID ngawur)
if (empty($data_surat)) {
  echo "<script>alert('Data tidak ditemukan!'); document.location.href='index.php';</script>";
  exit;
}

// Ambil data pertama dari array hasil query
$srt = $data_surat[0];

// 4. PROSES UBAH DATA (JIKA TOMBOL SUBMIT DITEKAN)
if (isset($_POST["submit"])) {

  $no_surat = htmlspecialchars($_POST["no_surat"]);
  $pengirim = htmlspecialchars($_POST["pengirim"]);
  $tanggal = htmlspecialchars($_POST["tanggal_surat"]);
  $perihal = htmlspecialchars($_POST["perihal"]);
  $gambarLama = htmlspecialchars($_POST["gambarLama"]);

  // Cek apakah user pilih gambar baru atau tidak
  // Error 4 artinya tidak ada file yang diupload
  if ($_FILES['file_surat']['error'] === 4) {
    $file_surat = $gambarLama; // Pakai gambar lama
  } else {
    $file_surat = upload(); // Upload gambar baru
    if (!$file_surat) {
      return false; // Stop jika upload gagal
    }
  }

  // Query Update
  $query = "UPDATE surat_masuk SET
                no_surat = '$no_surat',
                tanggal_surat = '$tanggal',
                pengirim = '$pengirim',
                perihal = '$perihal',
                file_surat = '$file_surat'
              WHERE id = $id";

  mysqli_query($conn, $query);

  // Cek keberhasilan
  if (mysqli_affected_rows($conn) > 0) {
    echo "<script>alert('Data berhasil diubah!'); document.location.href = 'index.php';</script>";
  } else {
    // Jika 0, bisa jadi karena tidak ada data yang diganti (user cuma klik simpan tanpa edit)
    echo "<script>alert('Data berhasil disimpan (Tidak ada perubahan data).'); document.location.href = 'index.php';</script>";
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Ubah Data Surat</title>
  <style>
    body {
      font-family: sans-serif;
      padding: 20px;
    }

    ul {
      list-style: none;
      padding: 0;
    }

    li {
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }

    input[type="text"],
    input[type="date"] {
      width: 100%;
      padding: 8px;
      max-width: 400px;
    }

    button {
      padding: 10px 20px;
      background-color: #ffc107;
      color: black;
      border: none;
      cursor: pointer;
    }

    button:hover {
      background-color: #e0a800;
    }

    img {
      border: 1px solid #ddd;
      padding: 5px;
      margin-top: 5px;
    }
  </style>
</head>

<body>
  <h1>Ubah Data Surat Masuk</h1>

  <form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $srt["id"]; ?>">
    <input type="hidden" name="gambarLama" value="<?= $srt["file_surat"]; ?>">

    <ul>
      <li>
        <label for="no_surat">No. Surat : </label>
        <input type="text" name="no_surat" id="no_surat" required value="<?= $srt["no_surat"]; ?>">
      </li>
      <li>
        <label for="tanggal_surat">Tanggal Surat : </label>
        <input type="date" name="tanggal_surat" id="tanggal_surat" required value="<?= $srt["tanggal_surat"]; ?>">
      </li>
      <li>
        <label for="pengirim">Pengirim : </label>
        <input type="text" name="pengirim" id="pengirim" required value="<?= $srt["pengirim"]; ?>">
      </li>
      <li>
        <label for="perihal">Perihal : </label>
        <input type="text" name="perihal" id="perihal" required value="<?= $srt["perihal"]; ?>">
      </li>
      <li>
        <label for="file_surat">File Scan Surat : </label>
        <img src="img/<?= $srt['file_surat']; ?>" width="100"><br>
        <small>Biarkan kosong jika tidak ingin mengubah file.</small><br>
        <input type="file" name="file_surat" id="file_surat">
      </li>
      <li>
        <button type="submit" name="submit">Ubah Data!</button>
        <a href="index.php" style="margin-left: 10px; text-decoration: none;">Kembali</a>
      </li>
    </ul>
  </form>
</body>

</html>