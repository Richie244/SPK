<?php

// app/Http/Middleware/LogoutMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class LogoutMiddleware
{
    public function handle($request, Closure $next)
    {
        // Hapus sesi otomatis
        Auth::logout();
        return redirect()->route('login');
    }
}
