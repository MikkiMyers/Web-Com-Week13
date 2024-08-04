<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />
    <title>My - Controllers</title>
</head>
<body>
    <header id="app-cmp-main-header">
        <h1> My DATABASE @section('title-container')@yield('tite')@show</h1>
        <nav>
            <ul>
                <ol>
                    <a href="{{ route('products.list') }}"> Product </a>
                    <a href="{{ route('shops.list') }}"> Shop </a>
                </ol>
            </ul>
        </nav>
    </header>

    <div id="main-content">
        @yield('content')
    </div>

    <footer id="app-cmp-main-footer">
        &#xA9; Copyright Week-06, 2024  Nutcha's Database
    </footer>
</body>
</html>