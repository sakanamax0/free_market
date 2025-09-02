# FREE\_MARKET アプリケーション

## 環境構築手順

### 1. Dockerビルド

```bash
git clone git@github.com:sakanamax0/free_market.git
cd free_market
docker-compose up -d --build
```

### 2. Laravel環境構築

```bash
docker-compose exec php bash
composer install
cp .env.example .env
# .envファイルの環境変数を適宜変更してください
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

---

## 3. 開発環境アクセス

* トップページ
  [http://localhost/](http://localhost/)

* ユーザー登録ページ
  [http://localhost/register](http://localhost/register)

* phpMyAdmin
  [http://localhost:8080/](http://localhost:8080/)

---

## 一般ユーザーログイン情報（seed済み）

```text
| 種別           | メールアドレス       | パスワード |
|----------------|----------------------|------------|
| 一般ユーザー①  | test1@gmail.com       | password   |
| 一般ユーザー②  | test2@gmail.com       | password   |
| 一般ユーザー③  | test3@gmail.com       | password   |
```

---

## 使用技術・実行環境

```text
| 技術     | バージョン   |
|----------|--------------|
| PHP      | 7.4.9        |
| Laravel  | 8.83.8       |
| jQuery   | 3.7.1.min.js |
| MySQL    | 8.0.26       |
| nginx    | 1.21.1       |
```

---

## ER図

![ER図](free_market.drawio.png)

---

## 再クローンによる検証推奨

このリポジトリを GitHub に push した後、
**一度別の場所に clone して、以下の手順でマイグレーションまで正常に進むか確認してください。**

```bash
git clone git@github.com:sakanamax0/free_market.git
cd free_market
docker-compose up -d --build
docker-compose exec php bash
composer install
cp .env.example .env
# 必要に応じて STRIPE_KEY などを設定
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

---

### 住所登録の方法について

本アプリでは、住所情報は `addresses` テーブルに保存されます。
初めて商品購入する際、購入画面から住所登録ができます。

また、マイページのプロフィール編集画面からも住所を登録・更新可能です。

### 「購入した商品」タブの表示について修正

これまで「購入した商品」タブには商品が表示されませんでしたが、
購入情報を管理するための `sold_items` テーブルと `SoldItem` モデルを導入し、
マイページで購入済み商品が正しく表示されるように修正しました。

表示の取得元は以下の通りです：

* 購入済み商品 → `sold_items` テーブル（購入者ごとに記録）
* 表示される商品データ → リレーションを使って `items` テーブルから取得

また、取引中の商品タブでは、購入済みの商品であっても、
取引履歴としてチャットが存在する場合は引き続き表示されます。

### .envの設定について

`.env.example` には、Dockerコンテナの構成に合わせて以下のように設定されています：

```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass
```

※ これらは `docker-compose.yml` で定義されたDBサービス設定と一致しています。

## Docker起動後の権限設定について

初回起動時に、Laravelの`storage`と`bootstrap/cache`ディレクトリの権限調整が必要になる場合があります。

以下のコマンドを実行してください。

```bash
docker-compose exec php bash
cd /var/www
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
exit
```

### Stripe（テスト決済）を利用する場合

本番用やテスト用の Stripe API キーは `.env` に設定してください。  
`.env.example` には以下のようなダミー値が入っています：

```env
STRIPE_SECRET=sk_test_your_test_key_here
STRIPE_KEY=pk_test_your_test_key_here


### テストユーザーの画像に権限付与必要な場合

以下のコマンドを実行してください。
# WSL 側ターミナルでプロジェクトのディレクトリに移動
# storage と bootstrap/cache に書き込み権限を付与
```bash
sudo chmod -R 777 storage bootstrap/cache
```