<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Permohonan SKBT</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
        }
        
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 16pt;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .header p {
            font-size: 12pt;
            margin: 2px 0;
        }
        
        .content {
            margin: 30px 0;
        }
        
        .surat-info {
            margin-bottom: 30px;
        }
        
        .surat-info table {
            width: 100%;
        }
        
        .surat-info td {
            padding: 2px 0;
            vertical-align: top;
        }
        
        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 30px 0;
            text-transform: uppercase;
        }
        
        .body-text {
            text-align: justify;
            margin: 20px 0;
        }
        
        .data-table {
            width: 100%;
            margin: 20px 0;
        }
        
        .data-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        
        .data-table .label {
            width: 200px;
        }
        
        .data-table .colon {
            width: 20px;
            text-align: center;
        }
        
        .signature {
            margin-top: 50px;
        }
        
        .signature-table {
            width: 100%;
        }
        
        .signature-left {
            width: 50%;
            text-align: center;
        }
        
        .signature-right {
            width: 50%;
            text-align: center;
        }
        
        .signature-space {
            height: 80px;
        }
        
        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
        }
        
        .bold {
            font-weight: bold;
        }
        
        .center {
            text-align: center;
        }
        
        .right {
            text-align: right;
        }
        
        .mt-20 {
            margin-top: 20px;
        }
        
        .mt-30 {
            margin-top: 30px;
        }
        
        .mb-20 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEMERINTAH DAERAH PROVINSI DKI JAKARTA</h1>
        <h2>DINAS PENDIDIKAN</h2>
        <p>Jl. Gatot Subroto Kav. 40-41 Jakarta Selatan 12190</p>
        <p>Telepon: (021) 525-4857, Fax: (021) 525-4857</p>
        <p>Website: www.jakarta.go.id, Email: disdik@jakarta.go.id</p>
    </div>
    
    <div class="content">
        <div class="surat-info">
            <table>
                <tr>
                    <td style="width: 100px;">Nomor</td>
                    <td style="width: 20px;">:</td>
                    <td><?= htmlspecialchars($data['nomor_surat']) ?></td>
                    <td class="right"><?= htmlspecialchars($data['tempat_surat'] ?? 'Jakarta') ?>, <?= date('d F Y', strtotime($data['tanggal_surat'])) ?></td>
                </tr>
                <tr>
                    <td>Lampiran</td>
                    <td>:</td>
                    <td>-</td>
                    <td></td>
                </tr>
                <tr>
                    <td>Perihal</td>
                    <td>:</td>
                    <td class="bold">Permohonan SKBT</td>
                    <td></td>
                </tr>
            </table>
        </div>
        
        <div class="body-text">
            <p>Kepada Yth.<br>
            Kepala Badan Kepegawaian Daerah<br>
            Provinsi DKI Jakarta<br>
            di Jakarta</p>
        </div>
        
        <div class="title">
            PERMOHONAN SURAT KETERANGAN BEBAS TUGAS (SKBT)
        </div>
        
        <div class="body-text">
            <p>Dengan hormat,</p>
            <p>Yang bertanda tangan di bawah ini:</p>
        </div>
        
        <table class="data-table">
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td><?= htmlspecialchars($data['nama_pegawai']) ?></td>
            </tr>
            <tr>
                <td class="label">NIP</td>
                <td class="colon">:</td>
                <td><?= htmlspecialchars($data['nip_pegawai']) ?></td>
            </tr>
            <tr>
                <td class="label">Pangkat/Golongan</td>
                <td class="colon">:</td>
                <td><?= htmlspecialchars($data['pangkat_pegawai']) ?></td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="colon">:</td>
                <td><?= htmlspecialchars($data['jabatan_pegawai']) ?></td>
            </tr>
            <tr>
                <td class="label">Unit Kerja</td>
                <td class="colon">:</td>
                <td><?= htmlspecialchars($data['unit_kerja_pegawai']) ?></td>
            </tr>
        </table>
        
        <div class="body-text mt-20">
            <p>Dengan ini mengajukan permohonan untuk diterbitkan Surat Keterangan Bebas Tugas (SKBT) untuk keperluan 
            <?php 
            $keperluan_text = [
                'mutasi' => 'mutasi jabatan',
                'promosi' => 'promosi jabatan',
                'pensiun' => 'pensiun',
                'tugas_belajar' => 'tugas belajar',
                'lainnya' => $data['keperluan_lainnya'] ?? 'lainnya'
            ];
            echo htmlspecialchars($keperluan_text[$data['keperluan']] ?? $data['keperluan']);
            ?>.</p>
            
            <p>Surat Keterangan Bebas Tugas ini diperlukan sebagai salah satu persyaratan administrasi yang harus dipenuhi sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.</p>
            
            <p>Demikian permohonan ini kami sampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.</p>
        </div>
        
        <div class="signature">
            <table class="signature-table">
                <tr>
                    <td class="signature-left">
                        <p>Mengetahui,</p>
                        <p><?= htmlspecialchars($data['jabatan_pejabat']) ?></p>
                        <div class="signature-space"></div>
                        <p class="bold underline"><?= htmlspecialchars($data['nama_pejabat']) ?></p>
                        <p>NIP. <?= htmlspecialchars($data['nip_pejabat']) ?></p>
                    </td>
                    <td class="signature-right">
                        <p>Pemohon,</p>
                        <div class="signature-space"></div>
                        <p class="bold underline"><?= htmlspecialchars($data['nama_pegawai']) ?></p>
                        <p>NIP. <?= htmlspecialchars($data['nip_pegawai']) ?></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="mt-30">
            <p><strong>Catatan:</strong></p>
            <p style="font-size: 10pt;">1. Surat ini dibuat secara elektronik dan sah tanpa tanda tangan basah sesuai dengan ketentuan peraturan perundang-undangan.</p>
            <p style="font-size: 10pt;">2. SKBT diperlukan untuk memastikan bahwa pegawai tidak memiliki tugas atau tanggung jawab yang mengikat di unit kerja asal.</p>
        </div>
    </div>
</body>
</html>