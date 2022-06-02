# [AnimeScape](https://www.animescape.link/)
IDを作成し，ログインすることで各アニメの点数や感想をつけることができる批評サービスです．
評価指標として中央値を採用しており，平均値よりも信頼のおけるデータを提供しています．
主な機能として，以下の5つを紹介します．

1.【統計機能】

ユーザーから収集したレビュー情報を元に，様々な条件に基づいた統計情報を生成する機能です．この機能を用いて、ユーザーは自分の好みに応じて視聴するアニメを探すことができます．

2.【視聴管理機能】

視聴したアニメや視聴予定アニメ等を管理することができる機能です．

3.【お気に入りユーザーの登録機能】

お気に入りユーザーを登録する機能です．この機能を用いることで，知人等と視聴アニメの共有を行うことができます．また，お気に入りユーザー内での統計情報を取れるので，知り合い内での統計に基づいて視聴するアニメを探すことができます．

4.【レコメンド機能】

各ユーザーにおすすめのアニメを提案する機能です．ユーザーの得点のつけ方に近い他ユーザーを割り出し，そのユーザーの評価が高いアニメをおすすめとして提案します．

5.【データ登録、削除、変更申請機能】

アニメ等のデータを登録、削除、変更申請を行うことができる機能です．サービスの管理人がすべてのデータを管理するのは負担が大きく，現実的に難しいため，ユーザーにデータの登録、削除、変更の協力してもらうことで，半自動的にデータの更新を行うことを可能としています。
![www animescape link_ (3)](https://user-images.githubusercontent.com/73135261/171683929-8e4cb111-7552-49df-b5cc-861f72e1067c.png)

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
* パスワード再設定機能
* 得点，感想投稿機能
* ランキング機能
* 検索機能
* お気に入りユーザー，声優登録機能（非同期処理）
* 統計機能
* ページネーション機能
* データ登録，削除，変更申請機能
* レコメンド機能

# ライセンス
This code is under the [MIT license](https://opensource.org/licenses/MIT).
