<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
    //Password Change
    public function PasswordChange(){
        return view('admin.profile.password_change');
    }
    //Password Update
    public function PasswordUpdate(Request $request){
        $validated = $request->validate([
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);
        $current_password=Auth::user()->password;   //login user password get
        $oldpass=$request->old_password;    //old password get from input field
        $new_password=$request->pasword;    //new password get for new password
        if (Hash::check($oldpass,$current_password)){   //checking old password and current user password same or not
            $user=User::findorfail(Auth::id());     //current user data get
            $user->password=Hash::make($request->password);     //current user password hashing
            $user->save();  //FINALLY SAVE THE password
            Auth::logout(); //logout the admin user and redirect admin login panel
            $notification=array('messege' => 'Yyour Password Changed!', 'alert-type' => 'success');
            return redirect()->route('admin.login')->with($notification);
        }else{
            $notification=array('messege' => 'Old Password Does not Match!', 'alert-type' => 'error');
            return redirect()->back()->with($notification);
        }
    }

}
