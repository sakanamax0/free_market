環境構築

Dockerビルド

git clone git@github.com:sakanamax0/free_market00.git

docker-compose up-d-build

Laravel境構築

dockar-compose exec php bash

composer Install

cp envexample env、環境変数を適宣変更

php artisan key generate

php artisan migrate

php artisan db:seed

開発環境

お問い合わせ：http://localhost/

ユーザー登録：http://localhost/register

phpMyAdmin:http://localhost:8080/

使用技術(実行環境)

PHP 8.2.11

Laravel 8.83.8

jquery 3.7.1.min.js

MySQL 8.0.26

nginx 1.21.1

![ER図](./free_market.drawio.svg)
