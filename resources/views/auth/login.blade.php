<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carpir Admin - Iniciar sesión</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: sans-serif; margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #1a1a1a; color: #fff; }
        .login-box { background: #252525; padding: 2rem; border-radius: 8px; width: 100%; max-width: 360px; }
        h1 { margin-top: 0; font-size: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        label { display: block; margin-bottom: 0.25rem; }
        input { width: 100%; padding: 0.5rem; border: 1px solid #444; border-radius: 4px; background: #333; color: #fff; }
        button { width: 100%; padding: 0.75rem; background: #c00; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        button:hover { background: #a00; }
        .error { color: #f66; font-size: 0.9rem; margin-bottom: 1rem; }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Carpir Admin</h1>
        <p>Iniciar sesión</p>
        @if($errors->any())
        <div class="error">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="username">Usuario</label>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password" required>
            </div>
            <button type="submit">Iniciar sesión</button>
        </form>
    </div>
</body>
</html>
