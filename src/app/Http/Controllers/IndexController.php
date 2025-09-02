<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->input('tab', 'recommend');

        if ($tab === 'mylist') {
           
            $items = Item::whereIn('id', auth()->user()->likes()->pluck('item_id'))->get();
        } else {
           
            $items = Item::all();
        }

        return view('auth.index', compact('items'));
    }
}
