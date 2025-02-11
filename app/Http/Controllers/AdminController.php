<?php

namespace App\Http\Controllers;

use App\Models\Admin; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('loginAdmin'); 
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'motdepasse' => 'required|min:6',
        ]);
    
        $admin = Admin::where('email', $request->email)->first();
        if ($admin && $request->motdepasse == $admin->password) {
            Session::put('admin_id', $admin->id);
            return redirect()->route('reservations.liste.total');
        }
    
        return back()->with('error', 'Identifiants incorrects');
    }
    
    public function logout()
    {

        Session::forget('admin_id');
        return redirect()->route('admin.login')->with('success', 'Déconnexion réussie');
    }
}
