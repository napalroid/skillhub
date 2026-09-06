<!DOCTYPE html>
<html>
<head>
    <title>Test Navigation</title>
</head>
<body>
    <h1>Test Dropdown Navigation</h1>
    
    @php
        // Mock authenticated user
        $user = new App\Models\User();
        $user->id = 1;
        $user->name = 'Test User';
        $user->email = 'test@example.com';
        
        app()->instance('Illuminate\Contracts\Auth\Authenticatable', $user);
    @endphp
    
    @include('layouts.navigation')
    
    <hr>
    
    <h2>Route Test</h2>
    <p>services.my: {{ route('services.my') }}</p>
    <p>route('services.my') exists: {{ \Illuminate\Support\Facades\Route::has('services.my') ? 'YES' : 'NO' }}</p>
    
    <h2>Auth Test</h2>
    <p>Auth::check: {{ Auth::check() ? 'YES' : 'NO' }}</p>
    <p>Auth::user()->name: {{ Auth::check() ? Auth::user()->name : 'No user' }}</p>
</body>
</html>
