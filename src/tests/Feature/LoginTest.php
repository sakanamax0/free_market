<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function メールアドレスが入力されていない場合バリデーションエラーが表示される()
    {
        $response = $this->post(route('login.post'), [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function パスワードが入力されていない場合バリデーションエラーが表示される()
    {
        $response = $this->post(route('login.post'), [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function 入力情報が間違っている場合バリデーションエラーが表示される()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correct_password'),
        ]);

        $response = $this->from(route('login'))
                         ->post(route('login.post'), [
                             'email' => 'test@example.com',
                             'password' => 'wrong_password',
                         ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function 正しい情報が入力された場合ログイン処理が実行される()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login.post'), [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('index'));
        $this->assertAuthenticatedAs($user);
    }
}
