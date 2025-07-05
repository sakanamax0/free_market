<?php

namespace App\Listeners;

use App\Events\ItemPurchased;
use App\Mail\PurchaseNotificationMail;
use Illuminate\Support\Facades\Mail;

class SendPurchaseNotification
{
    public function handle(ItemPurchased $event)
    {
        $item = $event->item;
        $buyer = $event->buyer;  
        $seller = $item->seller; 

        if ($seller && $seller->email) {
            Mail::to($seller->email)->send(new PurchaseNotificationMail($item, $buyer->name));
        }
    }
}