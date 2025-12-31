<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
</head>
<body>
    <header>
        @auth
        // The user is authenticated...
        @endauth

        @guest
            <a href="auth.login">Login</a>
            <a href="auth.register">Register</a>
        @endguest
    </header>
    <main>
        @yield('maincontent')
    </main>
</body>
</html>