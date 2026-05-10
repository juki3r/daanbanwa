<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Website Not Found</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            color: #343a40;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .container {
            background: #ffffff;
            padding: 50px 40px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
        }

        .icon {
            font-size: 80px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 36px;
            margin-bottom: 15px;
            color: #dc3545;
        }

        p {
            font-size: 18px;
            color: #6c757d;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        a {
            display: inline-block;
            padding: 12px 24px;
            background: #0d6efd;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: background 0.3s ease;
        }

        a:hover {
            background: #0b5ed7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🌐</div>
        <h1>Website Not Found</h1>
        <p>
            Sorry, the website you are looking for does not exist,
            has been removed, or is temporarily unavailable.
        </p>
        {{-- Logout --}}
        <div class="mt-auto pt-3 border-top">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}"
                class="nav-link d-flex align-items-center gap-2 text-danger"
                onclick="event.preventDefault(); this.closest('form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Go back home</span>
                </a>
            </form>
        </div>
    </div>
</body>
</html>