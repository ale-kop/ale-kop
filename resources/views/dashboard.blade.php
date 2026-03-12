<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
<h1>Welcome, {{ $user->name }}</h1>
<form method="POST" action="{{ url('/logout') }}">
    @csrf
    <button type="submit">
        <x-heroicon-o-arrow-left-start-on-rectangle class="w-6 h-6 text-gray-500"/>
        Logout
    </button>
</form>
</body>
</html>