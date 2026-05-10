<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Barangay Daan Banwa Portal</title>
    <link rel="shortcut icon" href="{{asset('images/logo.png')}}" type="image/x-icon">

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />

    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #f4f7fb, #e9eef8);
            min-height: 100vh;
        }

        /* DESKTOP WELCOME PAGE */
        .desktop-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* TOP NAV */
        .top-nav {
            padding: 20px 40px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
        }

        .login-btn {
            background: #1e3a8a;
            color: #ffffff;
            text-decoration: none;
            padding: 10px 22px;
            border-radius: 10px;
            font-weight: 600;
            transition: 0.3s;
        }

        .login-btn:hover {
            background: #162d6d;
            color: #ffffff;
        }

        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .welcome-card {
            background: #ffffff;
            width: 100%;
            max-width: 900px;
            border-radius: 24px;
            padding: 60px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.08);
            border: 1px solid #dce3ed;
            text-align: center;
        }

        .logo {
            width: 110px;
            height: 110px;
            object-fit: contain;
            margin-bottom: 24px;
        }

        .gov-label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 1.5px;
            margin-bottom: 10px;
        }

        .title {
            font-size: 48px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 20px;
            color: #475569;
            margin-bottom: 30px;
            font-weight: 500;
        }

        .heading {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 20px;
        }

        .description {
            font-size: 17px;
            line-height: 1.8;
            color: #475569;
            max-width: 760px;
            margin: 0 auto 35px;
        }

        .service-card {
            background: #f8fafc;
            border: 1px solid #dce3ed;
            border-radius: 18px;
            padding: 30px;
            text-align: left;
            max-width: 700px;
            margin: 0 auto;
        }

        .service-card h5 {
            font-weight: 700;
            margin-bottom: 20px;
            color: #0f172a;
        }

        .service-item {
            margin-bottom: 12px;
            color: #475569;
            font-size: 15px;
        }

        .footer-text {
            margin-top: 30px;
            font-size: 13px;
            color: #64748b;
            font-weight: 500;
        }

        /* MOBILE/TABLET WARNING */
        .device-warning {
            display: none;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: #f8fafc;
        }

        .warning-card {
            background: #ffffff;
            max-width: 500px;
            width: 100%;
            border-radius: 20px;
            padding: 50px 35px;
            text-align: center;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }

        .warning-icon {
            font-size: 70px;
            margin-bottom: 20px;
        }

        .warning-title {
            font-size: 30px;
            font-weight: 800;
            color: #dc2626;
            margin-bottom: 15px;
        }

        .warning-text {
            font-size: 16px;
            color: #475569;
            line-height: 1.8;
        }

        /* Show warning on screens <= 1024px (tablet and phone) */
        @media (max-width: 1024px) {
            .desktop-page {
                display: none;
            }

            .device-warning {
                display: flex;
            }
        }
    </style>
</head>
<body>

    <!-- DESKTOP VERSION -->
    <div class="desktop-page">
        <!-- Top Right Login -->
        <div class="top-nav">
            <a href="/login" class="login-btn">Login</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="welcome-card">
                <!-- Replace with your logo -->
                <img src="{{asset('images/main.png')}}" alt="Barangay Logo" class="logo">

                <div class="gov-label">REPUBLIC OF THE PHILIPPINES</div>
                <h1 class="title">Barangay Daan Banwa</h1>
                <div class="subtitle">Estancia, Iloilo</div>

                <h2 class="heading">Barangay Service Portal</h2>

                <p class="description">
                    Welcome to the digital service platform of Barangay Daan Banwa.
                    This portal provides residents with secure access to public
                    announcements, community reports, barangay services, and official
                    local updates in one centralized platform.
                </p>

                <div class="service-card">
                    <h5>Public Services Available</h5>

                    <div class="service-item">• Barangay announcements and notices</div>
                    <div class="service-item">• Resident requests and reports</div>
                    <div class="service-item">• Community concerns and updates</div>
                    <div class="service-item">• Secure access to barangay records</div>
                </div>

                <div class="footer-text">
                    Barangay Application
                </div>
            </div>
        </div>
    </div>

    <!-- MOBILE / TABLET WARNING -->
    <div class="device-warning">
        <div class="warning-card">
            <div class="warning-icon">💻</div>
            <h1 class="warning-title">Device Not Supported</h1>
            <p class="warning-text">
                This system is designed for desktop and laptop computers only.
                <br><br>
                Please use a <strong>PC or Laptop</strong> to access the
                Barangay Daan Banwa Service Portal.
            </p>
        </div>
    </div>

</body>
</html>