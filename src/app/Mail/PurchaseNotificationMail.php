<?php

namespace App\Mail;

use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $buyerName;

    /**
     * Create a new message instance.
     *
     * @param Item $item
     * @param string $buyerName
     */
    public function __construct(Item $item, string $buyerName)
    {
        $this->item = $item;
        $this->buyerName = $buyerName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('商品が購入されました')
            ->markdown('emails.purchase_notification')
            ->with([
                'item' => $this->item,
                'buyerName' => $this->buyerName,
                'message' => '該当商品の購入者チャット画面へアクセスして、商品購入者の評価をしてください。',
            ]);
    }
}
