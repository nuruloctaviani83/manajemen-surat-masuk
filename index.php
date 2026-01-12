<?php
$conn = mysqli_connect("localhost", "root", "", "db_surat");

// --- LOGIKA PENCARIAN ---
if (isset($_POST["cari"])) {
  $keyword = mysqli_real_escape_string($conn, $_POST["keyword"]);
  // Mencari berdasarkan nomor surat, pengirim, atau perihal
  $query = "SELECT * FROM surat_masuk 
              WHERE no_surat LIKE '%$keyword%' OR 
                    pengirim LIKE '%$keyword%' OR
                    perihal LIKE '%$keyword%'
              ORDER BY id DESC";
} else {
  $query = "SELECT * FROM surat_masuk ORDER BY id DESC";
}

$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>

<head>
  <title>Daftar Surat Masuk</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      padding: 40px;
      background-color: #f4f4f4;
    }

    .container {
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .header-tools {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .search-box {
      display: flex;
      gap: 5px;
    }

    .input-search {
      padding: 8px 12px;
      border: 1px solid #ccc;
      border-radius: 4px;
      width: 250px;
    }

    .btn-search {
      background-color: #7f8c8d;
      color: white;
      border: none;
      padding: 8px 15px;
      border-radius: 4px;
      cursor: pointer;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th,
    td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #2c3e50;
      color: white;
    }

    /* Membatasi lebar kolom perihal agar tidak terlalu panjang */
    .col-perihal {
      max-width: 200px;
      word-wrap: break-word;
    }

    .btn {
      padding: 8px 12px;
      text-decoration: none;
      border-radius: 4px;
      font-size: 13px;
      color: white;
      display: inline-block;
      border: none;
    }

    .btn-tambah {
      background: #27ae60;
    }

    .btn-lihat {
      background: #3498db;
    }

    .btn-ubah {
      background: #f39c12;
      margin-right: 5px;
    }

    .btn-hapus {
      background: #e74c3c;
    }

    .refresh-link {
      font-size: 12px;
      color: #3498db;
      text-decoration: none;
      margin-left: 10px;
    }
  </style>
</head>

<body>
  <div class="container">
    <h2>Manajemen Surat Masuk</h2>

    <div class="header-tools">
      <a href="tambah.php" class="btn btn-tambah">+ Tambah Surat Baru</a>

      <form action="" method="post" class="search-box">
        <input type="text" name="keyword" class="input-search" placeholder="Cari No/Pengirim/Perihal..." autocomplete="off">
        <button type="submit" name="cari" class="btn-search">Cari</button>
        <?php if (isset($_POST["cari"])): ?>
          <a href="index.php" class="refresh-link">Tampilkan Semua</a>
        <?php endif; ?>
      </form>
    </div>

    <table>
      <tr>
        <th>No</th>
        <th>Nomor Surat</th>
        <th>Pengirim</th>
        <th>Perihal</th>
        <th>Tanggal</th>
        <th>File</th>
        <th>Aksi</th>
      </tr>
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php $i = 1;
        while ($row = mysqli_fetch_assoc($result)) : ?>
          <tr>
            <td><?= $i++; ?></td>
            <td><?= $row["no_surat"]; ?></td>
            <td><?= $row["pengirim"]; ?></td>
            <td class="col-perihal"><?= $row["perihal"]; ?></td>
            <td><?= date('d/m/Y', strtotime($row["tanggal_surat"])); ?></td>
            <td>
              <a href="uploads/<?= $row["file_surat"]; ?>" target="_blank" class="btn btn-lihat">📄 Buka</a>
            </td>
            <td>
              <a href="ubah.php?id=<?= $row["id"]; ?>" class="btn btn-ubah">Ubah</a>
              <a href="hapus.php?id=<?= $row["id"]; ?>" class="btn btn-hapus" onclick="return confirm('Hapus data ini?')">Hapus</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" style="text-align: center; color: #7f8c8d;">Data tidak ditemukan.</td>
        </tr>
      <?php endif; ?>
    </table>
  </div>
</body>

</html>