<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;

class AddressController extends Controller
{
    public function edit($item_id)
    {
        $address = Address::where('user_id', auth()->id())->first();

        return view('address', [
            'address' => $address,
            'item_id' => $item_id
        ]);
    }

    public function update(Request $request, $item_id)
    {
        $request->validate([
            'zipcode' => 'required|string|max:8',
            'details' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        $address = Address::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'zipcode' => $request->zipcode,
                'details' => $request->details,
                'building' => $request->building,
            ]
        );

        return redirect()->route('purchase.show', ['item_id' => $item_id])
            ->with('success', '住所が更新されました。');
    }
}
