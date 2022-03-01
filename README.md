# [AnimeScape](https://www.animescape.link/)
IDを作成し，ログインすることで各アニメの点数や感想をつけることができる統計サイトです．
評価指標として中央値を採用しており，平均値よりも信頼のおけるデータを提供しています．
また，他人のアカウントをお気に入りユーザーとして登録することができ，お気に入りユーザー内での点数評価の統計を取ることができます．

# 使用技術
* PHP
* Laravel
	* guzzlehttp/guzzle
* MySQL
* Nginx
* AWS
	* EC2
	* RDS
	* Route 53
* Docker/Docker-compose
* ShangriLa Anime API
* Python
	* Selenium

# AWS構成図
![animescape diagram drawio](https://user-images.githubusercontent.com/73135261/156163870-de6d6bc1-f082-4ef6-a8c8-a9c22e561a1f.png)

# 機能一覧
* ユーザー登録，ログイン機能
* 得点，感想投稿機能
* ランキング機能
* 検索機能
* お気に入りユーザー登録機能

# ライセンス
This code is under the [MIT license](https://opensource.org/licenses/MIT).
