<?php
// 1. KONEKSI KE DATABASE
// Pastikan urutannya: (host, user, password, nama_database)
$conn = mysqli_connect("localhost", "root", "", "db_surat");

// Cek koneksi (opsional, untuk debugging)
if (!$conn) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

// 2. FUNGSI QUERY (Untuk mengambil data)
function query($query)
{
  global $conn;
  $result = mysqli_query($conn, $query);
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }
  return $rows;
}

// 3. FUNGSI CARI (Untuk fitur pencarian)
function cari($keyword)
{
  $query = "SELECT * FROM surat_masuk
                WHERE
              no_surat LIKE '%$keyword%' OR
              pengirim LIKE '%$keyword%' OR
              perihal LIKE '%$keyword%'
            ";
  return query($query);
}

// 4. FUNGSI UPLOAD (Untuk upload gambar/file)
function upload()
{
  $namaFile = $_FILES['file_surat']['name'];
  $ukuranFile = $_FILES['file_surat']['size'];
  $error = $_FILES['file_surat']['error'];
  $tmpName = $_FILES['file_surat']['tmp_name'];

  // Cek apakah ada file yang diupload
  if ($error === 4) {
    echo "<script>alert('Pilih file surat terlebih dahulu!');</script>";
    return false;
  }

  // Cek ekstensi file (Boleh Gambar atau PDF)
  $ekstensiValid = ['jpg', 'jpeg', 'png', 'pdf'];
  $ekstensiFile = explode('.', $namaFile);
  $ekstensiFile = strtolower(end($ekstensiFile));

  if (!in_array($ekstensiFile, $ekstensiValid)) {
    echo "<script>alert('Yang anda upload bukan file yang valid!');</script>";
    return false;
  }

  // Cek ukuran (max 5MB)
  if ($ukuranFile > 5000000) {
    echo "<script>alert('Ukuran file terlalu besar!');</script>";
    return false;
  }

  // Generate nama file baru agar tidak bentrok
  $namaFileBaru = uniqid();
  $namaFileBaru .= '.';
  $namaFileBaru .= $ekstensiFile;

  // Pindahkan file ke folder img
  // Pastikan folder 'img' sudah Anda buat sebelumnya!
  move_uploaded_file($tmpName, 'img/' . $namaFileBaru);

  return $namaFileBaru;
}
