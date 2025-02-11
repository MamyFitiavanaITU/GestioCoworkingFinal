<?php
namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;

class OptionController extends Controller
{
    public function listeOption()
    {
        $options = Option::all();
        return view('listeOption', compact('options'));
    }
}
