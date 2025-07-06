<?php

namespace Tests\Feature;

use App\Models\ChatRoom;
use App\Models\Item;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_buyer_sees_rating_modal_button_when_not_rated()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
        ]);
        $chatRoom = ChatRoom::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($buyer)->get(route('chatroom.show', $chatRoom->id));

        $response->assertStatus(200);
        $response->assertSee('取引を完了する'); 
        $response->assertSee('id="rating-modal"'); 
        $response->assertSee('class="modal hidden"'); 
    }

    public function test_seller_sees_rating_modal_immediately_when_not_rated()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
        ]);
        $chatRoom = ChatRoom::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($seller)->get(route('chatroom.show', $chatRoom->id));

        $response->assertStatus(200);
        $response->assertSee('id="rating-modal"');
        $response->assertSee('今回の取引相手はどうでしたか？'); 
        $response->assertSee('class="modal hidden"'); 
    }


    public function test_modal_not_shown_if_already_rated()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
        ]);
        $chatRoom = ChatRoom::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        Rating::create([
            'from_user_id' => $buyer->id,
            'to_user_id' => $seller->id,
            'item_id' => $item->id,
            'score' => 4,
        ]);

        $response = $this->actingAs($buyer)->get(route('chatroom.show', $chatRoom->id));

        $response->assertStatus(200);
        $response->assertDontSee('取引を完了する'); 
        $response->assertDontSee('今回の取引相手はどうでしたか？'); 
    }
}
