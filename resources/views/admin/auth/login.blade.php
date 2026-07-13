<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - SIRITA</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0b0f19;
            --text-color: #f8fafc;
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --card-bg: rgba(17, 24, 39, 0.7);
            --border-color: rgba(255, 255, 255, 0.08);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }

        /* Decorative glowing circles */
        .glowing-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            z-index: 1;
            opacity: 0.15;
        }

        .circle-1 {
            width: 400px;
            height: 400px;
            background: #6366f1;
            top: -100px;
            left: -100px;
        }

        .circle-2 {
            width: 500px;
            height: 500px;
            background: #a855f7;
            bottom: -150px;
            right: -150px;
        }

        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            backdrop-filter: blur(16px);
            padding: 3rem;
            border-radius: 24px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            z-index: 10;
            box-sizing: border-box;
        }

        .login-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-header h2 {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .login-header p {
            color: #94a3b8;
            margin: 0;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #94a3b8;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 0.9rem 1.2rem;
            font-size: 1rem;
            border-radius: 12px;
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid var(--border-color);
            color: white;
            outline: none;
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.25);
            background: rgba(30, 41, 59, 0.7);
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: var(--primary);
            border: none;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #f87171;
            padding: 1rem;
            border-radius: 12px;
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            list-style: none;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="glowing-circle circle-1"></div>
    <div class="glowing-circle circle-2"></div>

    <div class="login-card">
        <div class="login-header">
            <h2>SIRITA Admin</h2>
            <p>Silakan masuk ke panel kontrol administrator</p>
        </div>

        @if ($errors->any())
            <ul class="alert-error">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="admin" required autofocus>
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-submit">Masuk</button>
        </form>
    </div>
</body>
</html>
