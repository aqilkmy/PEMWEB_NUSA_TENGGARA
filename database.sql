-- Buat database
CREATE DATABASE IF NOT EXISTS ntt_db;
USE ntt_db;

-- Tabel users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert admin default (email: admin123@gmail.com, password: admin123)
INSERT INTO users (nama, email, password, role)
VALUES ('Admin', 'admin123@gmail.com', SHA2('admin123', 256), 'admin');

-- Tabel destinasi
CREATE TABLE destinasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150),
    lokasi VARCHAR(150),
    deskripsi TEXT,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample data destinasi
INSERT INTO destinasi (nama, lokasi, deskripsi, gambar) VALUES
('Pulau Komodo', 'Taman Nasional Komodo', 'Pulau Komodo adalah sebuah pulau yang terletak di Kepulauan Nusa Tenggara. Pulau Komodo dikenal sebagai habitat asli hewan komodo yang merupakan kadal terbesar di dunia.', 'asset/pulau-komodo.png'),
('Pulau Padar', 'Flores', 'Pulau Padar menawarkan pemandangan alam yang spektakuler dengan bukit-bukit yang menjulang dan pantai berpasir putih yang memukau.', 'asset/pulau-padar.png'),
('Danau Kelimutu', 'Ende, Flores', 'Danau Kelimutu adalah danau vulkanik yang terkenal dengan tiga danau berwarna yang berubah-ubah. Keindahan alam dan mitos lokal menjadikannya destinasi wisata yang unik.', 'asset/danau-kalimutu.png');

-- Tabel komentar
CREATE TABLE komentar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destinasi_id INT,
    user_id INT,
    isi TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (destinasi_id) REFERENCES destinasi(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
