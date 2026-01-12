<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_surat";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_POST["submit"])) {
  $no_surat   = mysqli_real_escape_string($conn, $_POST["no_surat"]);
  $tanggal    = mysqli_real_escape_string($conn, $_POST["tanggal_surat"]);
  $pengirim   = mysqli_real_escape_string($conn, $_POST["pengirim"]);
  $perihal    = mysqli_real_escape_string($conn, $_POST["perihal"]);

  $namaFile   = $_FILES['file_scan']['name'];
  $tmpName    = $_FILES['file_scan']['tmp_name'];
  $error      = $_FILES['file_scan']['error'];

  if ($error === 4) {
    echo "<script>alert('Pilih file scan surat terlebih dahulu!');</script>";
  } else {
    $ekstensiFile = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
    $namaFileBaru = uniqid() . '.' . $ekstensiFile;

    if (move_uploaded_file($tmpName, 'uploads/' . $namaFileBaru)) {
      // DISINI PERBAIKANNYA: Menggunakan 'file_surat' sesuai screenshot database kamu
      $query = "INSERT INTO surat_masuk (no_surat, tanggal_surat, pengirim, perihal, file_surat)
                      VALUES ('$no_surat', '$tanggal', '$pengirim', '$perihal', '$namaFileBaru')";

      if (mysqli_query($conn, $query)) {
        echo "<script>
                        alert('Data Berhasil Disimpan!');
                        document.location.href = 'index.php';
                      </script>";
      } else {
        echo "Gagal Simpan. Error: " . mysqli_error($conn);
      }
    } else {
      echo "<script>alert('Gagal upload ke folder uploads!');</script>";
    }
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Tambah Data Surat</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      padding: 40px;
      display: flex;
      justify-content: center;
    }

    .container {
      background-color: white;
      width: 750px;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      border: 1px solid #ddd;
    }

    .header {
      border-bottom: 2px solid #2c3e50;
      margin-bottom: 25px;
      padding-bottom: 15px;
    }

    .header h2 {
      margin: 0;
      color: #2c3e50;
    }

    .content {
      display: flex;
      gap: 30px;
    }

    .left-panel,
    .right-panel {
      flex: 1;
    }

    .form-group {
      margin-bottom: 15px;
    }

    label {
      display: block;
      font-size: 13px;
      font-weight: bold;
      color: #34495e;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="date"],
    textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-sizing: border-box;
    }

    .upload-area {
      background-color: #f8f9fa;
      border: 2px dashed #3498db;
      border-radius: 6px;
      height: 100%;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      color: #555;
      padding: 20px;
    }

    .footer-actions {
      margin-top: 30px;
      text-align: right;
      border-top: 1px solid #eee;
      padding-top: 20px;
    }

    .btn {
      padding: 10px 25px;
      border: none;
      cursor: pointer;
      border-radius: 4px;
      font-weight: bold;
      text-decoration: none;
      display: inline-block;
    }

    .btn-save {
      background-color: #007bff;
      color: white;
    }

    .btn-cancel {
      background-color: #e0e0e0;
      color: #333;
      margin-right: 10px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h2>Tambah Data Surat Masuk</h2>
    </div>
    <form action="" method="post" enctype="multipart/form-data">
      <div class="content">
        <div class="left-panel">
          <div class="form-group">
            <label>Nomor Surat</label>
            <input type="text" name="no_surat" required>
          </div>
          <div class="form-group">
            <label>Tanggal Surat</label>
            <input type="date" name="tanggal_surat" required>
          </div>
          <div class="form-group">
            <label>Pengirim</label>
            <input type="text" name="pengirim" required>
          </div>
          <div class="form-group">
            <label>Perihal</label>
            <textarea name="perihal" rows="4"></textarea>
          </div>
        </div>
        <div class="right-panel">
          <div class="upload-area">
            <div style="font-size: 40px; margin-bottom: 10px;">☁️</div>
            <strong>Upload File Scan</strong>
            <input type="file" name="file_scan" required style="margin-top: 10px;">
          </div>
        </div>
      </div>
      <div class="footer-actions">
        <a href="index.php" class="btn btn-cancel">Batal</a>
        <button type="submit" name="submit" class="btn btn-save">SIMPAN DATA</button>
      </div>
    </form>
  </div>
</body>

</html>