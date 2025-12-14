CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO users (nama, email, password, role)
VALUES ('admin', 'admin123@gmail.com', SHA2('admin123', 256), 'admin');

CREATE TABLE IF NOT EXISTS destinasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150),
    lokasi VARCHAR(150),
    deskripsi TEXT,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS komentar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destinasi_id INT,
    user_id INT,
    isi TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (destinasi_id) REFERENCES destinasi(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Add columns to destinasi if not exists
ALTER TABLE destinasi
ADD COLUMN IF NOT EXISTS sub_gambar1 VARCHAR(255),
ADD COLUMN IF NOT EXISTS sub_gambar2 VARCHAR(255),
ADD COLUMN IF NOT EXISTS sub_gambar3 VARCHAR(255),
ADD COLUMN IF NOT EXISTS link_gmaps VARCHAR(255) NULL,
ADD COLUMN IF NOT EXISTS label_destinasi VARCHAR(100);

CREATE TABLE IF NOT EXISTS kategori (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL
);

-- Insert kategori default
INSERT IGNORE INTO kategori (id, nama) VALUES 
(1, 'Wisata Alam'),
(2, 'Wisata Budaya'),
(3, 'Wisata Kuliner'),
(4, 'Wisata Religi'),
(5, 'Wisata Sejarah');

-- Add kategori_id column if not exists
ALTER TABLE destinasi
ADD COLUMN IF NOT EXISTS kategori_id INT,
ADD CONSTRAINT fk_kategori FOREIGN KEY (kategori_id) REFERENCES kategori(id);

CREATE TABLE IF NOT EXISTS kontak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    pesan TEXT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);