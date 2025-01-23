@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-md w-96">
        <h2 class="text-xl font-bold mb-4 text-center">Login</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required autofocus>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            </div>
            <div class="mb-4 flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember" class="rounded border-gray-300">
                    <span class="ml-2 text-sm">Ingat Saya</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-sm text-purple-700 hover:underline">Lupa Password?</a>
            </div>
            <button type="submit" 
                    class="w-full bg-purple-700 text-white py-2 rounded hover:bg-purple-600">Login</button>
        </form>

        <!-- Tambahkan bagian baru untuk registrasi -->
        <div class="mt-4 text-center">
            <p>Belum punya akun? <a href="{{ route('register') }}" class="text-purple-700 hover:underline">Daftar Sekarang</a></p>
        </div>
    </div>
</div>
@endsection
