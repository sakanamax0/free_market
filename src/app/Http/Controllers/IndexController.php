<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class IndexController extends Controller
{
    public function index()
    {
        
        $items = Item::all();

        
        return view('auth.index', compact('items'));
    }
}
