<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login | Kiyozumi CF</title>
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            border: 2px solid #c0392b;
            padding: 30px;
            width: 350px;
            border-radius: 8px;
            text-align: center;
        }
        .login-box h1 {
            margin-bottom: 20px;
            color: #c0392b;
        }
        .login-box input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #c0392b;
            background-color: #000;
            color: #fff;
            border-radius: 4px;
        }
        .login-box button {
            width: 100%;
            padding: 10px;
            background-color: #c0392b;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 4px;
        }
        .login-box button:hover {
            background-color: #e74c3c;
        }
        .error {
            color: #e74c3c;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="login-box">
    <img src="https://kiyozumi.cl/logo.png" width="40%" />
    <?php if (isset($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="/auth/login">
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Ingresar</button>
    </form>
</div>

</body>
</html>
