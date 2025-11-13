-- Database Schema untuk Lab Kampus

-- Drop tables if exists
DROP TABLE IF EXISTS contact_messages CASCADE;
DROP TABLE IF EXISTS gallery CASCADE;
DROP TABLE IF EXISTS mahasiswa CASCADE;
DROP TABLE IF EXISTS staff CASCADE;
DROP TABLE IF EXISTS struktur_organisasi CASCADE;
DROP TABLE IF EXISTS sejarah CASCADE;
DROP TABLE IF EXISTS visi_misi CASCADE;
DROP TABLE IF EXISTS admin_users CASCADE;

-- Table Admin Users
CREATE TABLE admin_users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table Visi Misi
CREATE TABLE visi_misi (
    id SERIAL PRIMARY KEY,
    visi TEXT NOT NULL,
    misi TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table Sejarah
CREATE TABLE sejarah (
    id SERIAL PRIMARY KEY,
    content TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table Struktur Organisasi
CREATE TABLE struktur_organisasi (
    id SERIAL PRIMARY KEY,
    jabatan VARCHAR(100) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    foto VARCHAR(255),
    urutan INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table Staff
CREATE TABLE staff (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nip VARCHAR(50),
    jabatan VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    foto VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table Mahasiswa
CREATE TABLE mahasiswa (
    id SERIAL PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nim VARCHAR(50) NOT NULL,
    program_studi VARCHAR(100),
    email VARCHAR(100),
    phone VARCHAR(20),
    foto VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table Gallery
CREATE TABLE gallery (
    id SERIAL PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    image VARCHAR(255) NOT NULL,
    tanggal DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table Contact Messages
CREATE TABLE contact_messages (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Berita (
    id SERIAL PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isi TEXT NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255),
    kategori VARCHAR(100),
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contoh data untuk testing
INSERT INTO Berita (judul, isi, deskripsi, gambar, kategori, tanggal) VALUES
('Pembukaan Laboratorium Baru', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Laboratorium baru telah dibuka dengan fasilitas lengkap.', 'Laboratorium baru dengan fasilitas modern', NULL, 'Pengumuman', CURRENT_DATE),
('Workshop Web Development', 'Workshop tentang web development akan diadakan minggu depan. Daftar segera!', 'Workshop gratis untuk mahasiswa', NULL, 'Event', CURRENT_DATE - INTERVAL ''5 days''),
('Pelatihan AI dan Machine Learning', 'Pelatihan intensif mengenai kecerdasan buatan dan machine learning untuk mahasiswa tingkat akhir.', 'Pelatihan AI selama 2 minggu', NULL, 'Pelatihan', CURRENT_DATE - INTERVAL ''10 days'');

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password, email, full_name) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@lab.ac.id', 'Administrator');
INSERT INTO admin_users (username, password, email, full_name) 
VALUES ('admin123', '$2y$12$tA01BRJ2AB/Fcok2ZdSpneExLlriCWESIYM3dLhpu/22uWbdka0ry', 'admin123@lab.ac.id', 'Administrator');

-- Insert default visi misi
INSERT INTO visi_misi (visi, misi) VALUES (
    'Menjadi laboratorium unggulan yang menghasilkan penelitian dan inovasi berkualitas tinggi dalam bidang teknologi informasi.',
    '1. Menyediakan fasilitas laboratorium yang modern dan lengkap
2. Mendukung kegiatan penelitian mahasiswa dan dosen
3. Mengembangkan inovasi teknologi yang bermanfaat bagi masyarakat
4. Menjalin kerjasama dengan industri dan institusi lain
5. Meningkatkan kompetensi SDM di bidang teknologi informasi'
);

-- Insert default sejarah
INSERT INTO sejarah (content) VALUES (
    'Laboratorium Komputer didirikan pada tahun 2010 sebagai bagian dari upaya peningkatan kualitas pendidikan di bidang teknologi informasi. Sejak awal berdirinya, laboratorium ini telah dilengkapi dengan berbagai fasilitas modern untuk mendukung kegiatan praktikum dan penelitian mahasiswa.

Dalam perjalanannya, laboratorium terus berkembang dan melakukan berbagai inovasi baik dalam hal fasilitas maupun program-program unggulan. Hingga saat ini, laboratorium telah menghasilkan berbagai penelitian dan karya inovasi yang bermanfaat bagi masyarakat luas.'
);
