<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - Bookphoria</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            width: 100%;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background-color: #FF5722;
            padding: 30px 20px;
            text-align: center;
            color: white;
        }

        .header h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .form-content {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
            font-size: 14px;
        }

        input[type="password"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.2s ease;
        }

        input[type="password"]:focus {
            outline: none;
            border-color: #FF5722;
        }

        .password-hint {
            margin-top: 5px;
            font-size: 12px;
            color: #666;
        }

        .button {
            width: 100%;
            background-color: #FF5722;
            color: white;
            padding: 14px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .button:hover {
            background-color: #E64A19;
        }

        .button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .error {
            color: #dc3545;
            font-size: 13px;
            margin-top: 8px;
        }

        .success {
            color: #28a745;
            font-size: 13px;
            margin-top: 15px;
            padding: 10px;
            background-color: #d4edda;
            border-radius: 4px;
            border-left: 3px solid #28a745;
        }

        .password-match {
            margin-top: 5px;
            font-size: 12px;
        }

        .match-success {
            color: #28a745;
        }

        .match-error {
            color: #dc3545;
        }

        @media (max-width: 480px) {
            .container {
                margin: 10px;
            }
            
            .form-content {
                padding: 25px 20px;
            }
            
            .header {
                padding: 25px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Reset Password</h2>
            <p>Buat password baru untuk akun Anda</p>
        </div>
        
        <div class="form-content">
            <form id="reset-form" action="{{ route('reset-password') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <input type="password" name="password" id="password" required>
                    <div class="password-hint">Minimal 8 karakter</div>
                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required>
                    <div id="password-match" class="password-match"></div>
                </div>
                
                <button type="submit" class="button">Ubah Password</button>
                
                @if (session('status'))
                    <div class="success">{{ session('status') }}</div>
                @endif
                @if (session('error'))
                    <div class="error">{{ session('error') }}</div>
                @endif
            </form>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const matchDiv = document.getElementById('password-match');

        confirmInput.addEventListener('input', function() {
            const password = passwordInput.value;
            const confirm = this.value;

            if (confirm.length === 0) {
                matchDiv.textContent = '';
                matchDiv.className = 'password-match';
                return;
            }

            if (password === confirm) {
                matchDiv.textContent = 'Password cocok';
                matchDiv.className = 'password-match match-success';
            } else {
                matchDiv.textContent = 'Password tidak cocok';
                matchDiv.className = 'password-match match-error';
            }
        });

        passwordInput.addEventListener('input', function() {
            if (confirmInput.value.length > 0) {
                confirmInput.dispatchEvent(new Event('input'));
            }
        });
    </script>
</body>
</html>