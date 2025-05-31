<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use App\Models\Like;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        Item::factory()->create(['name' => 'Apple Watch']);
        Item::factory()->create(['name' => 'Apple iPhone']);
        Item::factory()->create(['name' => 'Samsung Galaxy']);
        Item::factory()->create(['name' => 'Apple AirPods']);
    }

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        $response = $this->get(route('index', [
            'keyword' => 'Apple',
            'tab' => 'recommend',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Apple Watch');
        $response->assertSee('Apple iPhone');
        $response->assertSee('Apple AirPods');
        $response->assertDontSee('Samsung Galaxy');
    }

    /** @test */
    public function マイリスト検索でも検索状態が保持されている()
    {
        $this->actingAs($this->user);

        $itemApple = Item::where('name', 'Apple Watch')->first();
        Like::create(['user_id' => $this->user->id, 'item_id' => $itemApple->id]);

        $response = $this->get(route('index', [
            'tab' => 'mylist',
            'keyword' => 'Apple',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Apple Watch');
        $response->assertDontSee('Apple iPhone');
        $response->assertDontSee('Samsung Galaxy');
        $response->assertDontSee('Apple AirPods');
    }
}
