<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

//    admin after login
    public function admin(){
        return view('admin.home');
    }
//    admin custom logout
    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();
        $notification=array('messege' => 'You are logged out!', 'alert-type' => 'success');
        return redirect()->route('admin.login')->with($notification);
    }

}
