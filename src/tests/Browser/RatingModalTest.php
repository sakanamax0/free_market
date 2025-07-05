<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\ChatRoom;
use App\Models\Rating;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class RatingModalTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_buyer_can_open_rating_modal()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
        ]);
        $chatRoom = ChatRoom::factory()->create([
            'item_id' => $item->id,
        ]);

        $this->browse(function (Browser $browser) use ($buyer, $chatRoom) {
            $browser->loginAs($buyer)
                ->visit(route('chatroom.show', $chatRoom->id))
                ->assertSee('取引を完了する') 
                ->press('取引を完了する')     
                ->pause(500) 
                ->assertVisible('#rating-modal') 
                ->assertSee('今回の取引相手はどうでしたか？');
        });
    }
}
