    <!DOCTYPE html>
    <html lang="id">

    <head>
        <meta charset="utf-8">
        <title>Pemberitahuan Langganan Baru</title>
    </head>

    <body style="font-family: Arial, sans-serif; background:#f7f7f7; padding:20px;">
        <div style="max-width:600px;margin:auto;background:#fff;border-radius:8px;padding:20px;">
            <h2 style="color:#00509d;">Langganan Baru Dikonfirmasi âœ…</h2>
            <p>Halo Admin,</p>
            <p>Ada perusahaan yang baru saja berlangganan di <strong>Areakerja.com</strong>.</p>

            <table style="width:100%;margin:15px 0;">
                <tr>
                    <td style="width:40%;">Nama Perusahaan:</td>
                    <td><strong>{{ $nama_perusahaan ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>Email Akun:</td>
                    <td><strong>{{ $email_perusahaan ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>Tanggal Pembayaran:</td>
                    <td><strong>{{ $tanggal }}</strong></td>
                </tr>
                <tr>
                    <td>Total Pembayaran:</td>
                    <td><strong>{{ $jumlah }}</strong></td>
                </tr>
                <tr>
                    <td>Berlaku Hingga:</td>
                    <td><strong>{{ $expired }}</strong></td>
                </tr>
            </table>

            <p>Silakan periksa sistem untuk memverifikasi status langganan perusahaan tersebut.</p>

            <hr style="margin-top:20px;">
            <p style="font-size:12px;color:#999;">Email ini dikirim otomatis oleh sistem Areakerja.com</p>
        </div>
    </body>

    </html>

