<?php

namespace Tests\Feature;

use App\Models\ChatRoom;
use App\Models\Item;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ChatMessageTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_send_text_message()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
        ]);

        $chatRoom = ChatRoom::factory()->create([
            'item_id' => $item->id,
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
        ]);

        $this->actingAs($buyer)
            ->post(route('chatroom.send', $chatRoom), [
                'content' => 'こんにちは！',
            ])
            ->assertRedirect(route('chatroom.show', $chatRoom));

        $this->assertDatabaseHas('messages', [
            'chat_room_id' => $chatRoom->id,
            'sender_id' => $buyer->id,
            'content' => 'こんにちは！',
        ]);
    }

    public function test_authenticated_user_can_send_image_message()
    {
        Storage::fake('public');

        $seller = User::factory()->create();
        $buyer = User::factory()->create();

        $item = Item::factory()->create([
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
        ]);

        $chatRoom = ChatRoom::factory()->create([
            'item_id' => $item->id,
            'seller_id' => $seller->id,
            'buyer_id' => $buyer->id,
        ]);

        $image = UploadedFile::fake()->image('photo.jpg');

        $this->actingAs($buyer)
            ->post(route('chatroom.send', $chatRoom), [
                'image' => $image,
            ])
            ->assertRedirect(route('chatroom.show', $chatRoom));

        $this->assertDatabaseHas('messages', [
            'chat_room_id' => $chatRoom->id,
            'sender_id' => $buyer->id,
        ]);

        Storage::disk('public')->assertExists('chat_images/' . $image->hashName());
    }

    public function test_guest_cannot_send_message()
    {
        $chatRoom = ChatRoom::factory()->create();

        $this->post(route('chatroom.send', $chatRoom), [
            'content' => 'テストメッセージ',
        ])->assertRedirect(route('login'));
    }
}
