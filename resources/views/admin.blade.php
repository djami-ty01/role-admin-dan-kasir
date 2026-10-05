<!DOCTYPE html>
<html>
<head>
    <title>Halaman Admin</title>
</head>
<body>

    <h1>Selamat Datang di Halaman Admin</h1>
    <p>Ini adalah halaman admin Laravel.</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>

</body>
</html>