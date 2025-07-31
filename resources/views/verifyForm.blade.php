
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>IC Registration</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f0f2f5;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
            }
            .register-container {
                background-color: #fff;
                padding: 40px;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 400px;
                text-align: center;
            }
            h1 {
                color: #333;
                margin-bottom: 30px;
            }
            .form-group {
                margin-bottom: 20px;
                text-align: left;
            }
            label {
                display: block;
                margin-bottom: 8px;
                color: #555;
                font-weight: bold;
            }
            input[type="text"] {
                width: calc(100% - 20px);
                padding: 10px;
                border: 1px solid #ddd;
                border-radius: 4px;
                font-size: 16px;
                box-sizing: border-box;
            }
            button[type="submit"] {
                background-color: #28a745;
                color: white;
                padding: 12px 20px;
                border: none;
                border-radius: 4px;
                font-size: 18px;
                cursor: pointer;
                width: 100%;
                transition: background-color 0.3s ease;
            }
            button[type="submit"]:hover {
                background-color: #218838;
            }
            .error-message {
                color: red;
                margin-top: 10px;
                font-size: 14px;
            }
            .success-message {
                color: green;
                margin-top: 10px;
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <div class="register-container">
            <h1>Welcome, {{ $student_registration->s_name }}</h1>
            @if (session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif
            <p>For the first time login need password for verify. if forget password please request from admin</p>
            <form action="{{ route('register.verifyForm.function',$student_registration->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="password">password</label>
                    <input type="text" id="password" name="password" value="" required autofocus>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </body>
    </html>
</body>
</html>