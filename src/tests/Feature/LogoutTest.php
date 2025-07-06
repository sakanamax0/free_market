<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;


    public function ログイン中のユーザーがログアウトするとログインページにリダイレクトされる()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest(); 
    }


    public function ログアウト後はマイページにアクセスできない()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout');

        $response = $this->get('/mypage');

        $response->assertRedirect('/login'); 
    }
}
