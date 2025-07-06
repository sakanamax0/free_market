<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\ChatRoom;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ChatRoomViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_chatroom_page_can_be_accessed_by_seller()
    {

        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create(['seller_id' => $seller->id]);
        $chatRoom = ChatRoom::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
        ]);


        $response = $this->actingAs($seller)
                         ->get(route('chatroom.show', $chatRoom->id));

        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee($buyer->name);  
    }

    public function test_chatroom_page_can_be_accessed_by_buyer()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();
        $item = Item::factory()->create(['seller_id' => $seller->id]);
        $chatRoom = ChatRoom::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
            'item_id' => $item->id,
        ]);


        $response = $this->actingAs($buyer)
                         ->get(route('chatroom.show', $chatRoom->id));

        $response->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee($seller->name);
    }

    public function test_guest_cannot_access_chatroom_page()
    {
        $chatRoom = ChatRoom::factory()->create();

        $response = $this->get(route('chatroom.show', $chatRoom->id));
        $response->assertRedirect('/login');  
    }
}
