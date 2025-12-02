<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\User;


class AdminController extends Controller
{
    public function dashboard()
{
    $totalUsers = User::count();   // ✅ TOTAL USER COUNT
    return view('dashboard', compact('totalUsers'));
}

    public function index()
    {
        $userId = auth()->id();

        $recentLibrary = \App\Models\Catalogue::where('user_id', $userId)->orderBy('created_at', 'DESC')->take(8)->get();

        $favoriteCount = Favorite::where('user_id', $userId)->count();
        $catalogCount = Catalogue::where('user_id', $userId)->count();

        return view('dashboard', compact('recentLibrary', 'favoriteCount', 'catalogCount'));
    }
}
