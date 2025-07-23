<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pernyataan Disiplin</title>
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
        }
        
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        
        .content {
            margin: 30px 0;
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
        
        .signature-right {
            width: 100%;
            text-align: right;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>SURAT PERNYATAAN</h1>
    </div>
    
    <div class="content">
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
                <td class="label">Tempat/Tanggal Lahir</td>
                <td class="colon">:</td>
                <td><?= htmlspecialchars($data['tempat_lahir']) ?>, <?= date('d F Y', strtotime($data['tanggal_lahir'])) ?></td>
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
            <tr>
                <td class="label">Alamat</td>
                <td class="colon">:</td>
                <td><?= htmlspecialchars($data['alamat_pegawai']) ?></td>
            </tr>
        </table>
        
        <div class="body-text mt-20">
            <p>Dengan ini menyatakan dengan sesungguhnya bahwa:</p>
            
            <div class="indent">
                <p>1. Saya tidak pernah dijatuhi hukuman disiplin tingkat sedang dan berat berdasarkan Peraturan Pemerintah Nomor 53 Tahun 2010 tentang Disiplin Pegawai Negeri Sipil selama menjadi Pegawai Negeri Sipil terhitung sejak <?= date('d F Y', strtotime($data['periode_mulai'])) ?> sampai dengan <?= date('d F Y', strtotime($data['periode_selesai'])) ?>.</p>
                
                <p>2. Apabila di kemudian hari ternyata pernyataan saya ini tidak benar, maka saya bersedia menerima sanksi sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.</p>
                
                <p>3. Pernyataan ini saya buat dengan penuh kesadaran dan tanggung jawab tanpa ada paksaan dari pihak manapun.</p>
            </div>
            
            <p>Demikian surat pernyataan ini saya buat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
        </div>
        
        <div class="signature">
            <table class="signature-table">
                <tr>
                    <td style="width: 50%;">
                        <div class="materai-box">
                            MATERAI<br>
                            Rp 10.000
                        </div>
                    </td>
                    <td class="signature-right">
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
            <p style="font-size: 10pt;">2. Hukuman disiplin yang dimaksud adalah hukuman disiplin tingkat sedang dan berat sesuai PP No. 53 Tahun 2010</p>
            <p style="font-size: 10pt;">3. Pernyataan yang tidak benar dapat dikenakan sanksi pidana dan/atau administratif</p>
        </div>
    </div>
</body>
</html>