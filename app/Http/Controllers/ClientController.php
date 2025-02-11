<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;


class ClientController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'numerotelephone' => 'required|string|regex:/^\+?[0-9\- ]{7,15}$/',
        ]);

        $client = Client::firstOrCreate(
            ['numerotelephone' => $request->numerotelephone]
            // ['nomClient' => null] 
        );

        Log::debug('numéro', [
            'numero' => $request->numerotelephone,
            'clientId' => $client->id,
        ]);

        Session::put('client_id', $client->id);
    
        return redirect()->route('listeEsapce');
    }
    public function logout()
    {
        Session::forget('client_id');
        return redirect()->route('client.login');
    }

    public function listeClient()
    {
        $clients = Client::all();
        return view('listeclient', compact('clients'));
    }


}
