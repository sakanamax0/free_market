<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 名前が入力されていない場合バリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'username' => '',          // ←ここを変更
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['username']);  // ←ここも変更
    }

    /** @test */
    public function メールアドレスが入力されていない場合バリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'username' => 'テストユーザー',  // ←ここも
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function パスワードが入力されていない場合バリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'username' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function パスワードが7文字以下の場合バリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'username' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function パスワードと確認用が一致しない場合バリデーションエラーになる()
    {
        $response = $this->post('/register', [
            'username' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function 正常に登録されるとログイン画面にリダイレクトされる()
    {
        $response = $this->post('/register', [
            'username' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/login'); // 登録後にログイン画面へリダイレクトされる前提
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }
}
