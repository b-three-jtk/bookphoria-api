<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #FF5722; /* PrimaryOrange sesuai theme Bookphoria */
            padding: 20px;
            text-align: center;
            color: white;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #fff;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 0 0 8px 8px;
        }
        .button {
            display: inline-block;
            background-color: #FF5722;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #777;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bookphoria</h1>
        </div>
        <div class="content">
            <h2>Halo {{ $userName ?? 'Pengguna' }},</h2>
            
            <p>Anda menerima email ini karena kami menerima permintaan reset password untuk akun Bookphoria Anda.</p>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" class="button">Reset Password</a>
            </div>
            
            <p>Link reset password ini akan kedaluwarsa dalam {{ $count }} menit.</p>
            
            <p>Jika Anda tidak meminta reset password, abaikan email ini dan tidak ada tindakan lebih lanjut yang diperlukan.</p>
            
            <p>Salam,<br>Tim Bookphoria</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Bookphoria. All rights reserved.</p>
            <p>Jika Anda memiliki kesulitan mengklik tombol "Reset Password", salin dan tempel URL berikut ke browser web Anda: {{ $url }}</p>
        </div>
    </div>
</body>
</html>