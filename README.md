# [AnimeScape](https://www.animescape.link/)
IDを作成し，ログインすることで各アニメの点数や感想をつけることができる統計サイトです．
評価指標として中央値を採用しており，平均値よりも信頼のおけるデータを提供しています．
また，他人のアカウントをお気に入りユーザーとして登録することができ，お気に入りユーザー内での点数評価の統計を取ることができます．
加えて，レコメンド機能によって、各ユーザーごとのおすすめアニメを提案し，次に視聴するアニメを決定する情報として使用することができます．
![www animescape link_](https://user-images.githubusercontent.com/73135261/168007018-7d59d4b4-b8be-40a7-a07f-fdad67a28d3f.png)

# テスト用アカウント
ログインID： testuser
パスワード： testuserpassword

# 使用技術
* PHP 8.1.3
* Laravel 9.2.0
	* guzzlehttp/guzzle
	* PHP_CodeSniffer
	* Larastan
* JavaScript
* Vue.js 2.6.12
	* axios
* MySQL 8.0
* Nginx
* AWS
	* EC2
	* RDS
	* Route 53
* Docker/Docker-compose
* CircleCI CI/CD
* PHPUnit
* Jest
* Python3
	* Selenium
* Google Analytics

# AWS構成図
![animescape2 diagram drawio](https://user-images.githubusercontent.com/73135261/159098588-71cb46a8-715d-4c5f-9ad4-3936b8751fbe.png)

## CircleCI CI/CD
Githubのリモートmainブランチにpushすると，PHPUnit，Jest，PHP_CodeSniffer，Larastanが自動で実行され，成功した場合，EC2に自動でSSH接続し，デプロイを行います．この際，composer，npmのインストール，ビルド，マイグレーションが行われます．

# 機能一覧
* ユーザー登録，ログイン機能
* 得点，感想投稿機能
* ランキング機能
* 検索機能
* お気に入りユーザー，声優登録機能（非同期処理）
* 統計機能
* レコメンド機能

# ライセンス
This code is under the [MIT license](https://opensource.org/licenses/MIT).
