<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoogleMapsController extends Controller
{
    public function index()
    {
        return view('google-autocomplete');
    }
}