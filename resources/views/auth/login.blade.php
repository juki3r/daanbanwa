<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Barangay Portal</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #ffffff;
}

/* FULL CENTER WRAPPER */
.container {
    height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

/* HEADER */
.header {
    text-align: center;
    margin-bottom: 25px;
    cursor: pointer;
    text-decoration: none;
    color: #111827;
}

.logo {
    width: 70px;
}

.small-text {
    font-size: 11px;
    letter-spacing: 2px;
    color: #64748b;
    margin-bottom: 2px;
}

.title {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 2px;
}

.subtitle {
    font-size: 11px;
    color: #64748b;
}

/* FORM */
.form-box {
    width: 100%;
    max-width: 320px;
    text-align: center;
}

.icon {
    font-size: 34px;
    color: #2563eb;
    margin-bottom: 10px;
}

.login-title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 20px;
}

.input {
    width: 100%;
    padding: 10px 0;
    margin-bottom: 18px;
    border: none;
    border-bottom: 1px solid #d1d5db;
    outline: none;
    font-size: 14px;
}

.input:focus {
    border-bottom: 1px solid #2563eb;
}

button {
    width: 100%;
    padding: 10px;
    border: none;
    background: #2563eb;
    color: white;
    font-size: 14px;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background: #1e40af;
}
</style>
</head>

<body>

<div class="container">

    <!-- CLICKABLE HEADER -->
    <a href="/" class="header">
        <img src="{{asset('images/logo.png')}}" class="logo" alt="Logo">

        <div class="small-text">REPUBLIC OF THE PHILIPPINES</div>
        <div class="title">Barangay Daan Banwa</div>
        <div class="subtitle">Estancia, Iloilo</div>
    </a>

    <!-- LOGIN FORM -->
    <form class="form-box" method="POST" action="/login">

        <div class="icon">
            <i class="bi bi-shield-lock"></i>
        </div>

        <div class="login-title">Login</div>

        <input type="text" name="phone" placeholder="Phone" class="input" required>

        <input type="password" name="password" placeholder="Password" class="input" required>

        <button type="submit">Log in</button>

    </form>

</div>

</body>
</html>