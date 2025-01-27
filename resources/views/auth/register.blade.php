@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-md w-96">
        <h2 class="text-xl font-bold mb-4 text-center">Daftar Akun</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="name" 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                       value="{{ old('name') }}" required autofocus>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                       value="{{ old('email') }}" required>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" id="password" 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                       required>
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" 
                       required>
                @error('password_confirmation')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 flex items-center justify-between">
                <a href="{{ route('login') }}" class="text-sm text-purple-700 hover:underline">Sudah punya akun?</a>
            </div>

            <button type="submit" 
                    class="w-full bg-purple-700 text-white py-2 rounded hover:bg-purple-600">Daftar</button>
        </form>
    </div>
</div>
@endsection
