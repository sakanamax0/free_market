<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product.show', compact('product'));
    }

    
    public function create()
    {
        return view('sell.index');
    }

   
    public function index(Request $request)
    {
        $tab = $request->query('tab'); 

        if ($tab === 'mylist') {
            
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login');
            }

            $likedProductIds = Like::where('user_id', $user->id)->pluck('item_id');
            $items = Product::whereIn('id', $likedProductIds)
                ->with('orders') 
                ->get();

        } else {
            
            $items = Product::with('orders')->get();
        }

        
        foreach ($items as $item) {
            $item->sold_out = $item->orders()->exists();
        }

        return view('auth.index', compact('items'));
    }
}
