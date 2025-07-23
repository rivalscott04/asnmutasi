-- Database Schema untuk ASN Mutasi
-- Skema: 1 pegawai bisa memiliki banyak surat
-- Note: Tabel pegawai sudah ada dengan struktur yang telah ditentukan

-- Tabel jenis_surat (master data jenis surat)
CREATE TABLE jenis_surat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kode VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    template_file VARCHAR(100),
    deskripsi TEXT,
    status ENUM('aktif', 'non_aktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel surat (data surat yang dibuat)
CREATE TABLE surat (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nomor_surat VARCHAR(50) UNIQUE NOT NULL,
    pegawai_nip VARCHAR(20) NOT NULL,
    jenis_surat_id INT NOT NULL,
    pejabat_penandatangan_nip VARCHAR(20),
    judul VARCHAR(200),
    tanggal_surat DATE NOT NULL,
    bulan INT NOT NULL, -- Bulan dalam angka (1-12)
    tahun VARCHAR(4),
    status ENUM('draft', 'generated', 'signed') DEFAULT 'draft',
    file_path VARCHAR(255),
    data_surat JSON, -- Menyimpan semua data form dalam format JSON
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (pegawai_nip) REFERENCES pegawai(nip) ON DELETE CASCADE,
    FOREIGN KEY (jenis_surat_id) REFERENCES jenis_surat(id) ON DELETE RESTRICT,
    FOREIGN KEY (pejabat_penandatangan_nip) REFERENCES pegawai(nip) ON DELETE SET NULL
);

-- Tabel kantor (data kantor/instansi)
CREATE TABLE kantor (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    kabupaten_kota VARCHAR(100),
    alamat TEXT,
    telepon VARCHAR(20),
    fax VARCHAR(20),
    email VARCHAR(100),
    website VARCHAR(100),
    logo_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel log_aktivitas (untuk audit trail)
CREATE TABLE log_aktivitas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    surat_id INT,
    aktivitas VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (surat_id) REFERENCES surat(id) ON DELETE CASCADE
);

-- Insert data jenis surat
INSERT INTO jenis_surat (kode, nama, template_file, deskripsi) VALUES
('SKPM', 'Surat Keterangan Pengalaman Mengajar', 'surat_keterangan_pengalaman_mengajar', 'Surat keterangan pengalaman mengajar untuk keperluan sertifikasi'),
('SKBT', 'Surat Permohonan SKBT', 'surat_permohonan_skbt', 'Surat permohonan Surat Keterangan Bebas Temuan'),
('PD', 'Surat Pernyataan Disiplin', 'surat_pernyataan_disiplin_new', 'Surat pernyataan tidak pernah dijatuhi hukuman disiplin'),
('PTB', 'Surat Pernyataan Tugas Belajar', 'surat_pernyataan_tugas_belajar', 'Surat pernyataan tidak sedang menjalankan tugas belajar'),
('PP', 'Surat Pernyataan Pidana', 'surat_pernyataan_pidana', 'Surat pernyataan tidak pernah dipidana'),
('SPL', 'Surat Persetujuan Pelepasan', 'surat_persetujuan_pelepasan', 'Surat persetujuan pelepasan pegawai untuk mutasi'),
('SPN', 'Surat Persetujuan Penerimaan', 'surat_persetujuan_penerimaan', 'Surat persetujuan penerimaan pegawai untuk mutasi'),
('SPTJM', 'Surat Pernyataan Tanggung Jawab Mutlak', 'surat_sptjm', 'Surat pernyataan tanggung jawab mutlak untuk mutasi');

-- Insert data kantor default
INSERT INTO kantor (nama, kabupaten_kota, alamat, telepon, fax, email) VALUES
('Kantor Kementerian Agama', 'KABUPATEN LOMBOK BARAT', 'Jl. Raya Gerung No. 1', 'Telp. (0370) 681234', 'Fax. (0370) 681235', 'kankemenag@kemenag.go.id');

-- Index untuk performa
CREATE INDEX idx_pegawai_nip ON pegawai(nip);
CREATE INDEX idx_pegawai_nama ON pegawai(nama);
CREATE INDEX idx_surat_nomor ON surat(nomor_surat);
CREATE INDEX idx_surat_pegawai ON surat(pegawai_nip);
CREATE INDEX idx_surat_tanggal ON surat(tanggal_surat);
CREATE INDEX idx_surat_jenis ON surat(jenis_surat_id);
CREATE INDEX idx_log_surat ON log_aktivitas(surat_id);
CREATE INDEX idx_log_tanggal ON log_aktivitas(created_at);