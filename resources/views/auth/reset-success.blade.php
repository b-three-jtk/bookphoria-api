<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset Success - Bookphoria</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 50px 40px;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #28a745, #20c997);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            font-size: 40px;
            color: white;
            animation: checkmark 0.6s ease-in-out;
        }

        @keyframes checkmark {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        .success-title {
            color: #28a745;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 15px;
        }

        .success-message {
            color: #6c757d;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 35px;
        }

        .button {
            display: inline-block;
            background-color: #FF5722;
            color: white;
            padding: 14px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .button:hover {
            background-color: #E64A19;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255, 87, 34, 0.3);
        }

        .secondary-link {
            display: block;
            color: #6c757d;
            font-size: 14px;
            text-decoration: none;
            margin-top: 15px;
            transition: color 0.2s ease;
        }

        .secondary-link:hover {
            color: #FF5722;
        }

        .brand {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            color: #adb5bd;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .container {
                padding: 40px 25px;
                margin: 10px;
            }
            
            .success-title {
                font-size: 24px;
            }
            
            .success-message {
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">
            ✓
        </div>
        
        <h1 class="success-title">Password Berhasil Diubah!</h1>
        
        <p class="success-message">
            Password Anda telah berhasil diperbarui. Sekarang Anda dapat login menggunakan password baru untuk mengakses akun Bookphoria Anda.
        </p>
        
        <div class="brand">
            Bookphoria - Your Digital Library
        </div>
    </div>
</body>
</html>