<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Barangay Portal</title>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #ffffff;
}

.container {
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-box {
    width: 100%;
    max-width: 320px;
    text-align: center;
}

.icon {
    font-size: 34px;
    color: #2563eb;
}

.title {
    font-size: 22px;
    font-weight: 600;
    margin-bottom: 20px;
    color: #111827;
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

.status {
    font-size: 12px;
    color: #6b7280;
    margin-bottom: 10px;
}
</style>
</head>

<body>

<div class="container">
    <form class="form-box" method="POST" action="/login">

        <div class="icon">
            <i class="bi bi-shield-lock"></i>
        </div>

        <div class="title">Login</div>

        <div class="status"></div>

        <input type="text" name="phone" placeholder="Phone" class="input" required>

        <input type="password" name="password" placeholder="Password" class="input" required>

        <button type="submit">Log in</button>

    </form>
</div>

</body>
</html>