<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css">
</head>
<form action="{{ route('authenticate') }}" method="post" class="login-form">
    @csrf
    <label class="form-label">
        E-mail <input type="text" name="email" required class="form-input" />
    </label><br />
    <label class="form-label">
        Password <input type="password" name="password" required class="form-input" />
    </label><br />
    <button type="submit" class="form-button">Login</button>
    @error('credentials')
        <div class="warn">{{ $message }}</div>
    @enderror
</form>