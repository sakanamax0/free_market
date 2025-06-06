<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\SoldItem;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    public function test_全商品が表示される()
    {
        $items = Item::factory()->count(3)->create();

        $response = $this->get('/products?tab=recommend');

        foreach ($items as $item) {
            $response->assertSee($item->name);
        }
    }

    public function test_自分の出品商品は表示されない()
    {
        $user = User::factory()->create();
        $myItem = Item::factory()->create(['seller_id' => $user->id]);
        $otherItem = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/products?tab=recommend');

        $response->assertDontSee($myItem->name);
        $response->assertSee($otherItem->name);
    }

    public function test_購入済み商品は_SOLDと表示される()
    {
        $user = User::factory()->create(); // ← ユーザーを先に作成
        $item = Item::factory()->create(['is_sold' => true]);
    
        // sold_items テーブルにレコードを追加（user_id が必須）
        \App\Models\SoldItem::create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($user);
    
        $response = $this->get('/products?tab=recommend');
    
        $response->assertSee('SOLD');
    }
    
}
