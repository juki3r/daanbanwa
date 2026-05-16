<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Barangay Daan Banwa Portal</title>

<link rel="shortcut icon" href="{{asset('images/logo.png')}}" type="image/x-icon">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"/>

<style>
body {
    margin: 0;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f7fb;
    font-family: 'Segoe UI', sans-serif;
    color: #1f2937;
}

.container-box {
    text-align: center;
    max-width: 650px;
    padding: 20px;
}

.logo {
    width: 80px;
    margin-bottom: 15px;
}

.small-text {
    font-size: 12px;
    letter-spacing: 2px;
    color: #64748b;
    margin-bottom: 5px;
}

.title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 5px;
}

.subtitle {
    font-size: 15px;
    color: #64748b;
    margin-bottom: 20px;
}

.desc {
    font-size: 14px;
    color: #475569;
    line-height: 1.6;
    margin-bottom: 25px;
}

.btn-start {
    background: #1d4ed8;
    color: #fff;
    padding: 10px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    display: inline-block;
    transition: 0.2s;
}

.btn-start:hover {
    background: #1e40af;
    color: #fff;
}
</style>
</head>

<body>

<div class="container-box">

    <img src="{{asset('images/logo.png')}}" class="logo" alt="Logo">

    <div class="small-text">REPUBLIC OF THE PHILIPPINES</div>
    <div class="title">Barangay Daan Banwa</div>
    <div class="subtitle">Estancia, Iloilo</div>

    <div class="desc">
        Welcome to the Barangay Service Portal.  
        A centralized system for announcements, resident services, reports, and community updates.
    </div>

    <a href="/login" class="btn-start">Get Started</a>

</div>

</body>
</html>