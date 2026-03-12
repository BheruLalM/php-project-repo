<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRMS</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: white;
            font-family: "Times New Roman", Times, serif;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 3rem;
            border-radius: 1rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
        }
        .login-logo {
            font-size: 3rem;
            color: var(--accent-blue);
            margin-bottom: 1rem;
        }
        .login-title {
            font-size: 1.5rem;
            font-weight: bold;
            letter-spacing: 2px;
            margin-bottom: 0.5rem;
        }
        .login-subtitle {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-bottom: 2rem;
            text-transform: uppercase;
        }
        .form-group {
            text-align: left;
            margin-bottom: 1.5rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: var(--text-muted);
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid #334155;
            border-radius: 0.5rem;
            color: white;
            box-sizing: border-box;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 2px rgba(56, 189, 248, 0.2);
        }
        .login-btn {
            width: 100%;
            padding: 0.75rem;
            background: var(--accent-blue);
            color: #0f172a;
            border: none;
            border-radius: 0.5rem;
            font-weight: bold;
            font-size: 1rem;
            cursor: pointer;
            transition: 0.3s;
        }
        .login-btn:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
        }
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 1rem;
            background: rgba(239, 68, 68, 0.1);
            padding: 0.5rem;
            border-radius: 0.375rem;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-logo">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <div class="login-title">CRMS</div>
        <div class="login-subtitle">Law Enforcement Portal</div>

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Access Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="login-btn">Secure Login</button>

            @if($errors->has('email'))
                <div class="error-message">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first('email') }}
                </div>
            @endif
        </form>
    </div>

</body>
</html>
