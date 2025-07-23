<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPTJM - Surat Pernyataan Tanggung Jawab Mutlak</title>
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
        
        .indent {
            margin-left: 40px;
        }
        
        .materai-box {
            border: 2px solid #000;
            width: 100px;
            height: 80px;
            display: inline-block;
            text-align: center;
            vertical-align: middle;
            line-height: 80px;
            font-size: 10pt;
            margin: 10px;
        }
        
        .pernyataan-list {
            margin: 20px 0;
        }
        
        .pernyataan-list ol {
            padding-left: 20px;
        }
        
        .pernyataan-list li {
            margin-bottom: 10px;
            text-align: justify;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEMERINTAH DAERAH PROVINSI DKI JAKARTA</h1>
        <h2>BADAN KEPEGAWAIAN DAERAH</h2>
        <p>Jl. Letjen S. Parman Kav. 14 Jakarta Barat 11440</p>
        <p>Telepon: (021) 563-4444, Fax: (021) 563-4445</p>
        <p>Website: www.bkd.jakarta.go.id, Email: bkd@jakarta.go.id</p>
    </div>
    
    <div class="content">
        <div class="surat-info">
            <table>
                <tr>
                    <td style="width: 100px;">Nomor</td>
                    <td style="width: 20px;">:</td>
                    <td><?= htmlspecialchars($data['nomor_surat']) ?></td>
                    <td class="right"><?= htmlspecialchars($data['tempat_pernyataan']) ?>, <?= date('d F Y', strtotime($data['tanggal_surat'])) ?></td>
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
                    <td class="bold">SPTJM Mutasi PNS</td>
                    <td></td>
                </tr>
            </table>
        </div>
        
        <div class="title">
            SURAT PERNYATAAN TANGGUNG JAWAB MUTLAK<br>
            (SPTJM)
        </div>
        
        <div class="body-text">
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
                <td class="label">Unit Kerja Asal</td>
                <td class="colon">:</td>
                <td><?= htmlspecialchars($data['unit_kerja_asal']) ?></td>
            </tr>
            <tr>
                <td class="label">Unit Kerja Tujuan</td>
                <td class="colon">:</td>
                <td><?= htmlspecialchars($data['unit_kerja_tujuan']) ?></td>
            </tr>
            <tr>
                <td class="label">Jenis Mutasi</td>
                <td class="colon">:</td>
                <td><?php 
                    $jenis_mutasi_text = [
                        'mutasi_biasa' => 'Mutasi Biasa',
                        'mutasi_promosi' => 'Mutasi dengan Promosi',
                        'mutasi_demosi' => 'Mutasi dengan Demosi',
                        'mutasi_alih_tugas' => 'Mutasi Alih Tugas'
                    ];
                    echo htmlspecialchars($jenis_mutasi_text[$data['jenis_mutasi']] ?? $data['jenis_mutasi']);
                ?></td>
            </tr>
            <tr>
                <td class="label">Alasan Mutasi</td>
                <td class="colon">:</td>
                <td><?= htmlspecialchars($data['alasan_mutasi']) ?></td>
            </tr>
        </table>
        
        <div class="body-text mt-20">
            <p>Dengan ini menyatakan dengan sesungguhnya dan penuh tanggung jawab bahwa:</p>
        </div>
        
        <div class="pernyataan-list">
            <ol>
                <li>Saya bertanggung jawab penuh atas kebenaran semua data dan dokumen yang saya sampaikan dalam rangka proses mutasi ini.</li>
                
                <li>Saya menyatakan bahwa tidak ada halangan apapun bagi saya untuk dimutasi dari <?= htmlspecialchars($data['unit_kerja_asal']) ?> ke <?= htmlspecialchars($data['unit_kerja_tujuan']) ?>.</li>
                
                <li>Saya bersedia melaksanakan tugas dan tanggung jawab dengan penuh dedikasi di unit kerja yang baru sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.</li>
                
                <li>Saya akan mematuhi semua peraturan, tata tertib, dan ketentuan yang berlaku di unit kerja yang baru.</li>
                
                <li>Saya tidak akan menuntut atau meminta untuk dipindahkan kembali ke unit kerja asal atau unit kerja lain dalam jangka waktu minimal 2 (dua) tahun sejak tanggal penempatan, kecuali atas kebijakan pimpinan.</li>
                
                <li>Apabila di kemudian hari terbukti bahwa data atau dokumen yang saya sampaikan tidak benar atau palsu, maka saya bersedia menerima sanksi sesuai dengan ketentuan peraturan perundang-undangan yang berlaku, termasuk sanksi pidana.</li>
                
                <li>Saya bertanggung jawab mutlak atas segala konsekuensi yang timbul dari mutasi ini, baik yang bersifat administratif, finansial, maupun hukum.</li>
            </ol>
        </div>
        
        <div class="body-text">
            <p>Demikian Surat Pernyataan Tanggung Jawab Mutlak ini saya buat dengan penuh kesadaran dan tanpa ada paksaan dari pihak manapun untuk dapat dipergunakan sebagaimana mestinya.</p>
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
                        <div class="materai-box">
                            MATERAI<br>
                            Rp 10.000
                        </div>
                        <p><?= htmlspecialchars($data['tempat_pernyataan']) ?>, <?= date('d F Y', strtotime($data['tanggal_pernyataan'])) ?></p>
                        <p>Yang membuat pernyataan,</p>
                        <div class="signature-space"></div>
                        <p class="bold underline"><?= htmlspecialchars($data['nama_pegawai']) ?></p>
                        <p>NIP. <?= htmlspecialchars($data['nip_pegawai']) ?></p>
                    </td>
                </tr>
            </table>
        </div>
        
        <div class="mt-30">
            <p><strong>Catatan:</strong></p>
            <p style="font-size: 10pt;">1. Surat pernyataan ini harus ditandatangani di atas materai Rp 10.000,-</p>
            <p style="font-size: 10pt;">2. SPTJM ini mengikat secara hukum dan menjadi dasar pertanggungjawaban mutasi</p>
            <p style="font-size: 10pt;">3. Pernyataan yang tidak benar dapat berakibat pada pembatalan mutasi dan sanksi sesuai peraturan</p>
        </div>
    </div>
</body>
</html>