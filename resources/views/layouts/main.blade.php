<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="{{ asset('css/output.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
    <script>
        function showConfirmation() {
            document.getElementById('confirmationModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('confirmationModal').style.display = 'none';
        }

        function confirmDelete() {
            document.getElementById('deleteForm').submit();
        }

        window.onpageshow = function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        };
    </script>
</head>

<body>
    <header>
        <div class="container">
            <h1>
                @section('title-container')@yield('title')@show
            </h1>

            @auth
            <nav class=" app-cmp-user-panel">
                <span>{{ \Auth::user()->name }}</span>
                <a href="{{ route('logout') }}">Logout</a>
            </nav>
            @endauth

            <nav>
                <ul>
                    <li><a href="{{ route('products.list') }}">Product</a></li>
                    <li><a href="{{ route('shops.list') }}">Shop</a></li>
                    <li><a href="{{ route('categories.list') }}">Category</a></li>
                    <li>
                        <div class="dropdown-content" id="userDropdown">
                            @if (Auth::check())
                                @if (Auth::user()->role === 'ADMIN')
                                    <a href="{{ route('users.list') }}">Manage Users</a>
                                @else
                                    <a href="{{ route('users.self', Auth::user()->id) }}">Account</a>
                                @endif
                            @else
                                <p>You are not logged in.</p>
                                <a href="{{ route('login') }}">Login</a>
                            @endif
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <div class="container">
            

            <!-- แสดงข้อความข้อผิดพลาด -->
            @error('error')
                <div class="app-cmp-notification">
                    <span class="app-cl-warn">{{ $message }}</span>
                </div>
            @enderror

            @yield('content')
        </div>
    </main>
</body>

<footer>
    <div class="container">
        &#xA9; Copyright : Nutcha Parichanon, Database
    </div>
</footer>

</html>

