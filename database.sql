-- Database WonderfulNTT

CREATE DATABASE IF NOT EXISTS ntt_db;
USE ntt_db;

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin
-- Email: admin123@gmail.com
-- Password: admin123
INSERT IGNORE INTO users (nama, email, password, role)
VALUES ('Admin', 'admin123@gmail.com', SHA2('admin123', 256), 'admin');

-- Table: kategori
CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default categories
INSERT IGNORE INTO kategori (id, nama) VALUES 
(1, 'Wisata Alam'),
(2, 'Wisata Budaya'),
(3, 'Wisata Kuliner'),
(4, 'Wisata Religi'),
(5, 'Wisata Sejarah');

-- Table: destinasi
CREATE TABLE IF NOT EXISTS destinasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    lokasi VARCHAR(150) NOT NULL,
    deskripsi TEXT NOT NULL,
    kategori_id INT DEFAULT NULL,
    gambar VARCHAR(255) NOT NULL,
    sub_gambar1 VARCHAR(255) DEFAULT NULL,
    sub_gambar2 VARCHAR(255) DEFAULT NULL,
    sub_gambar3 VARCHAR(255) DEFAULT NULL,
    link_gmaps VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (kategori_id) REFERENCES kategori(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: komentar
CREATE TABLE IF NOT EXISTS komentar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destinasi_id INT NOT NULL,
    user_id INT NOT NULL,
    isi TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (destinasi_id) REFERENCES destinasi(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: kontak
CREATE TABLE IF NOT EXISTS kontak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    pesan TEXT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;