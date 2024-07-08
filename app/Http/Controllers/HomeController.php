<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index() {
        if(Auth::user()->usertype ==='admin') {
            return view('admin.home');
        } else {
            return view('dashboard');
        }
    }
    public function page() {
        if(Auth()->check()) {
            return view('adminpage');
        }
        return to_route('login');
    }
}
