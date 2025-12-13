CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (nama, email, password, role)
VALUES ('admin', 'admin123@gmail.com', SHA2('admin123', 256), 'admin');

CREATE TABLE destinasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150),
    lokasi VARCHAR(150),
    deskripsi TEXT,
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE komentar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    destinasi_id INT,
    user_id INT,
    isi TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (destinasi_id) REFERENCES destinasi(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

ALTER TABLE destinasi
ADD COLUMN sub_gambar1 VARCHAR(255),
ADD COLUMN sub_gambar2 VARCHAR(255),
ADD COLUMN sub_gambar3 VARCHAR(255),
ADD COLUMN link_gmaps VARCHAR(255) NULL,
ADD COLUMN label_destinasi VARCHAR(100);

CREATE TABLE jenis_destinasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_jenis VARCHAR(100) NOT NULL
);

ALTER TABLE destinasi
ADD jenis_id INT,
ADD FOREIGN KEY (jenis_id) REFERENCES jenis_destinasi(id);

CREATE TABLE kontak (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    pesan TEXT NOT NULL,
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);