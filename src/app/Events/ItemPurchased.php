<?php

namespace App\Events;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ItemPurchased
{
    use Dispatchable, SerializesModels;

    public $item;
    public $buyer;  

    public function __construct(Item $item, User $buyer)
    {
        $this->item = $item;
        $this->buyer = $buyer;  
    }
}
