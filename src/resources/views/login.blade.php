<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> 
</head>
<body>
    
    <header style="display: flex; justify-content: space-between; align-items: center; padding: 10px 20px; background-color: #f8f9fa;">
        <div>
            <h1 style="margin: 0;">COACHTECH</h1>
        </div>
    </header>

    
    <main style="max-width: 400px; margin: 50px auto; text-align: center;">
        
        <h2 style="font-size: 2em; margin-bottom: 20px;">ログイン</h2>

        
        <form method="POST" action="{{ route('login') }}" style="display: flex; flex-direction: column; gap: 15px;">
            @csrf

            
            <div>
                <input 
                    type="text" 
                    name="email" 
                    placeholder="ユーザー名/メールアドレス" 
                    value="{{ old('email') }}" 
                    required 
                    style="width: 100%; padding: 10px; font-size: 1em; border: 1px solid #ccc; border-radius: 5px;">
                @error('email')
                    <p style="color: red; font-size: 0.9em;">{{ $message }}</p>
                @enderror
            </div>

           
            <div>
                <input 
                    type="password" 
                    name="password" 
                    placeholder="パスワード" 
                    required 
                    style="width: 100%; padding: 10px; font-size: 1em; border: 1px solid #ccc; border-radius: 5px;">
                @error('password')
                    <p style="color: red; font-size: 0.9em;">{{ $message }}</p>
                @enderror
            </div>

           
            <div>
                <button 
                    type="submit" 
                    style="width: 100%; padding: 10px; font-size: 1em; background-color: red; color: white; border: none; border-radius: 5px;">
                    ログインする
                </button>
            </div>
        </form>

        
        <div style="margin-top: 20px;">
            <a href="{{ route('register') }}" style="color: blue; text-decoration: none;">会員登録はこちら</a>
        </div>
    </main>
</body>
</html>
