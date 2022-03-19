# [AnimeScape](https://www.animescape.link/)
IDを作成し，ログインすることで各アニメの点数や感想をつけることができる統計サイトです．
評価指標として中央値を採用しており，平均値よりも信頼のおけるデータを提供しています．
また，他人のアカウントをお気に入りユーザーとして登録することができ，お気に入りユーザー内での点数評価の統計を取ることで，次に視聴するアニメを決定する情報として使用することができます．

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
* CircleCI
* ShangriLa Anime API
* Python
	* Selenium

# AWS構成図
![animescape2 diagram drawio](https://user-images.githubusercontent.com/73135261/159098588-71cb46a8-715d-4c5f-9ad4-3936b8751fbe.png)

# 機能一覧
* ユーザー登録，ログイン機能
* 得点，感想投稿機能
* ランキング機能
* 検索機能
* お気に入りユーザー登録機能

# ライセンス
This code is under the [MIT license](https://opensource.org/licenses/MIT).
