<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\SoldItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_全商品を取得できる()
    {
        $user = User::factory()->create();
        $items = Item::factory()->count(3)->create();

        $response = $this->actingAs($user)->get(route('index'));

        $response->assertStatus(200);
        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_購入済み商品は_Sold_と表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();
        SoldItem::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->actingAs($user)->get(route('index'));

        $response->assertStatus(200);
        $response->assertSee('SOLD OUT');
    }

    public function test_自分が出品した商品は表示されない()
    {
        $user = User::factory()->create();

        // 他人の商品
        $otherItem = Item::factory()->create();

        // 自分の商品
        $myItem = Item::factory()->create([
            'seller_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get(route('index'));

        $response->assertStatus(200);
        $response->assertSee($otherItem->name);
        $response->assertDontSee($myItem->name);
    }
}
