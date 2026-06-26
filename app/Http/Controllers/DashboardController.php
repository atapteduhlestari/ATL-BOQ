<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalUsers' => User::count(),
            'totalProducts' => Product::count(),
            'activities' => Activity::latest()->take(10)->get(),
        ];
        
        return view('pages.dashboard', $data);
    }
}