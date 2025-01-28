<?php

namespace App\Http\Controllers;

use App\Models\University; // Import model jika diperlukan
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        // Contoh: Ambil data universitas untuk admin
        $universities = University::all();

        // Kirim data ke view admin dashboard
        return view('admin.dashboard', compact('universities'));
    }

    public function userDashboard()
    {
        // Contoh: Data spesifik untuk user (bisa disesuaikan)
        $user = Auth::user();
        $universities = University::all();

        // Kirim data ke view user dashboard
        return view('user.dashboard', compact('universities'));
    }
}
