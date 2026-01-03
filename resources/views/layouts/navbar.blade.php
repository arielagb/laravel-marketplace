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
            @if(Auth::user()->role === 'seller')
                <a href="sellers.shop.create_shop">Create a shop</a>
            @elseif(Auth::user()->role === 'buyer')
                <a href="buyers.basket">Basket</a>
            @endif
        @endauth
 
        @guest
            <a href="auth.login">Login</a>
            <a href="auth.register">Register</a>
            <a href="products.index">Products</a>
        @endguest
    </header>
    <main>
        @yield('maincontent')
    </main>
</body>
</html>