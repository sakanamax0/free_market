# 環境構築手順

## 1. Dockerビルド

```bash
git clone git@github.com:sakanamax0/free_market00.git
docker-compose up -d --build
```

## 2. Laravel環境構築

```bash
docker-compose exec php bash
composer install
cp .env.example .env
# .envファイルの環境変数を適宜変更してください
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan storage:link
```



## 3. 開発環境アクセス

- お問い合わせページ  
  http://localhost/

- ユーザー登録ページ  
  http://localhost/register

- phpMyAdmin  
  http://localhost:8080/

---

## 一般ユーザーログイン情報

```markdown
| 種別       | メールアドレス       | パスワード |
|------------|----------------------|------------|
| 一般ユーザー | test@gmail.com        | password   |
```

---

## 使用技術・実行環境

| 技術     | バージョン   |
|----------|--------------|
| PHP      | 8.2.11       |
| Laravel  | 8.83.8       |
| jQuery   | 3.7.1.min.js |
| MySQL    | 8.0.26       |
| nginx    | 1.21.1       |

---

## ER図

![ER図](free_market.drawio.png)
