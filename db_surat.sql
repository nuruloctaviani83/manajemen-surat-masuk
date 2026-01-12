-- Buat Database
CREATE DATABASE db_surat;
USE db_surat;

-- 1. Tabel Users (Untuk Login Admin)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);

-- Password default: 'admin123' (sudah di-hash)
INSERT INTO users (username, password) VALUES 
('admin', '$2y$10$uZ/3k/..hash_password_example...'); 
-- Catatan: Di aplikasi nanti, gunakan fitur registrasi untuk membuat user baru agar hash valid.

-- 2. Tabel Surat Masuk
CREATE TABLE surat_masuk (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_surat VARCHAR(100) NOT NULL,
    tanggal_surat DATE NOT NULL,
    pengirim VARCHAR(100) NOT NULL,
    perihal VARCHAR(255) NOT NULL,
    file_surat VARCHAR(100) NOT NULL, -- Nama file scan/pdf
    status VARCHAR(50) DEFAULT 'Belum Dibaca'
);

-- Dummy Data
INSERT INTO surat_masuk (no_surat, tanggal_surat, pengirim, perihal, file_surat, status) VALUES
('001/DINAS/XK/2023', '2023-10-25', 'Dinas Pendidikan', 'Undangan Rapat Koordinasi', 'surat1.jpg', 'Belum Dibaca'),
('002/KEMEN/XI/2023', '2023-10-26', 'Kementerian Kesehatan', 'Edaran Protokol Kesehatan', 'surat2.jpg', 'Disposisi');