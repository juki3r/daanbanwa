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
    font-family: 'Segoe UI', sans-serif;
    background: #f4f6f9;
}

/* HEADER */
.top-nav {
    background: #0f172a;
    padding: 15px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.brand {
    color: #fff;
    font-weight: 700;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.brand img {
    width: 35px;
}

.login-btn {
    background: #1d4ed8;
    color: #fff;
    padding: 8px 18px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
}

.login-btn:hover {
    background: #1e40af;
    color: #fff;
}

/* MAIN */
.wrapper {
    min-height: calc(100vh - 60px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 30px;
}

.portal-card {
    background: #fff;
    max-width: 950px;
    width: 100%;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
    padding: 50px;
    text-align: center;
}

/* LOGO */
.logo {
    width: 90px;
    margin-bottom: 15px;
}

/* TEXT */
.gov {
    font-size: 12px;
    letter-spacing: 2px;
    color: #64748b;
    font-weight: 600;
}

.title {
    font-size: 42px;
    font-weight: 800;
    color: #0f172a;
}

.subtitle {
    color: #475569;
    font-size: 18px;
    margin-bottom: 25px;
}

.section-title {
    font-size: 22px;
    font-weight: 700;
    margin-top: 20px;
    color: #0f172a;
}

.desc {
    color: #475569;
    font-size: 16px;
    line-height: 1.7;
    max-width: 750px;
    margin: 0 auto 25px;
}

/* SERVICES */
.services {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 25px;
    text-align: left;
    margin-top: 20px;
}

.services h5 {
    font-weight: 700;
    margin-bottom: 15px;
}

.services ul {
    margin: 0;
    padding-left: 18px;
    color: #475569;
}

.services li {
    margin-bottom: 8px;
}

/* FOOTER */
.footer {
    margin-top: 25px;
    font-size: 13px;
    color: #94a3b8;
}

/* MOBILE WARNING */
.warning {
    display: none;
    height: 100vh;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 20px;
}

.warning-card {
    background: #fff;
    padding: 40px;
    border-radius: 14px;
    border: 1px solid #e5e7eb;
    max-width: 450px;
}

@media (max-width: 1000px) {
    .wrapper { display: none; }
    .warning { display: flex; }
}
</style>
</head>

<body>

<!-- TOP BAR -->
<div class="top-nav">
    <div class="brand">
        <img src="{{asset('images/logo.png')}}" alt="">
        Barangay Daan Banwa
    </div>
    <a href="/login" class="login-btn">Login</a>
</div>

<!-- MAIN -->
<div class="wrapper">
    <div class="portal-card">

        <img src="{{asset('images/logo.png')}}" class="logo" alt="Logo">

        <div class="gov">REPUBLIC OF THE PHILIPPINES</div>
        <div class="title">Barangay Daan Banwa</div>
        <div class="subtitle">Estancia, Iloilo</div>

        <div class="section-title">Barangay Service Portal</div>

        <p class="desc">
            Welcome to the digital service platform of Barangay Daan Banwa.
            This portal provides residents with secure access to public
            announcements, community reports, barangay services, and official
            local updates in one centralized platform.
        </p>

        <div class="services">
            <h5>Public Services Available</h5>
            <ul>
                <li>Barangay announcements and notices</li>
                <li>Resident requests and reports</li>
                <li>Community concerns and updates</li>
                <li>Secure access to barangay records</li>
            </ul>
        </div>

        <div class="footer">
            Barangay Application System
        </div>

    </div>
</div>

<!-- MOBILE WARNING -->
<div class="warning">
    <div class="warning-card">
        <h3>💻 Device Not Supported</h3>
        <p class="mt-3 text-muted">
            Please use a desktop or laptop to access this system.
        </p>
    </div>
</div>

</body>
</html>