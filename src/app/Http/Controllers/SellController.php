<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Storage;

class SellController extends Controller
{
    public function index()
    {
        return view('sell.index'); 
    }

    public function store(Request $request)
    {
    
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'condition' => 'required|integer',
        'description' => 'nullable|string',
        'price' => 'required|integer|min:1',
        'categories' => 'required|array', 
        'categories.*' => 'exists:categories,id', 
    ]);

    
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('public/images'); 
        $url = Storage::url($path);
        $request->session()->put('image_path', $path); 
    } else {
        $path = null;
        $url = null; 
    }

    
    $item = Item::create([
        'name' => $validatedData['name'],
        'image' => $path,
        'description' => $validatedData['description'] ?? null,
        'price' => $validatedData['price'],
        'condition' => $validatedData['condition'],
        'sold_out' => false,
        'img_url' => $url,
    ]);
     
    $item->categories()->attach($validatedData['categories']); 

   
    return redirect()->route('sell.index')->with('success', '商品が出品されました！');
    }
}
