<?php
// ホーム
Breadcrumbs::for('home', function ($trail) {
    $trail->push('ホーム', route('index.show'));
});

// アニメリスト
Breadcrumbs::for('anime_list', function ($trail) {
    $trail->parent('home');
    $trail->push('アニメリスト', route('anime_list.show'));
});

// アニメ
Breadcrumbs::for('anime', function ($trail, $anime) {
    $trail->parent('anime_list');
    $trail->push($anime->title, route('anime.show', ['anime_id' => $anime->id]));
});

// 制作会社リスト
Breadcrumbs::for('company_list', function ($trail) {
    $trail->parent('home');
    $trail->push('制作会社リスト', route('company_list.show'));
});

// 制作会社
Breadcrumbs::for('company', function ($trail, $company) {
    $trail->parent('company_list');
    $trail->push($company->name, route('company.show', ['company_id' => $company->id]));
});

// 声優リスト
Breadcrumbs::for('cast_list', function ($trail) {
    $trail->parent('home');
    $trail->push('声優リスト', route('cast_list.show'));
});

// 声優
Breadcrumbs::for('cast', function ($trail, $cast) {
    $trail->parent('cast_list');
    $trail->push($cast->name, route('cast.show', ['cast_id' => $cast->id]));
});

// クリエイターリスト
Breadcrumbs::for('creater_list', function ($trail) {
    $trail->parent('home');
    $trail->push('クリエイターリスト', route('creater_list.show'));
});

// クリエイター
Breadcrumbs::for('creater', function ($trail, $creater) {
    $trail->parent('creater_list');
    $trail->push($creater->name, route('creater.show', ['creater_id' => $creater->id]));
});

// タグリスト
Breadcrumbs::for('tag_list', function ($trail) {
    $trail->parent('home');
    $trail->push('タグリスト', route('tag_list.show'));
});

// タグ
Breadcrumbs::for('tag', function ($trail, $tag) {
    $trail->parent('tag_list');
    $trail->push($tag->name, route('tag.show', ['tag_id' => $tag->id]));
});

// ランキングインデックス
Breadcrumbs::for('statistics_index', function ($trail) {
    $trail->parent('home');
    $trail->push('ランキングメニュー', route('statistics_index.show',));
});

// アニメランキング
Breadcrumbs::for('anime_statistics', function ($trail) {
    $trail->parent('statistics_index');
    $trail->push('アニメランキング', route('anime_statistics.show',));
});

// 声優ランキング
Breadcrumbs::for('cast_statistics', function ($trail) {
    $trail->parent('statistics_index');
    $trail->push('声優ランキング', route('cast_statistics.show',));
});

// 会社ランキング
Breadcrumbs::for('company_statistics', function ($trail) {
    $trail->parent('statistics_index');
    $trail->push('会社ランキング', route('company_statistics.show',));
});

// アニメの追加履歴
Breadcrumbs::for('add_anime_log', function ($trail) {
    $trail->parent('home');
    $trail->push('アニメの追加履歴', route('add_anime_log.show',));
});

// 声優の追加履歴
Breadcrumbs::for('add_cast_log', function ($trail) {
    $trail->parent('home');
    $trail->push('声優の追加履歴', route('add_cast_log.show',));
});

// クリエイターの追加履歴
Breadcrumbs::for('add_creater_log', function ($trail) {
    $trail->parent('home');
    $trail->push('クリエイターの追加履歴', route('add_creater_log.show',));
});

// 要望フォーム
Breadcrumbs::for('contact', function ($trail) {
    $trail->parent('home');
    $trail->push('要望フォーム', route('contact.show',));
});

// 更新履歴
Breadcrumbs::for('update_log', function ($trail) {
    $trail->parent('home');
    $trail->push('更新履歴', route('update_log.show',));
});

// このサイトについて
Breadcrumbs::for('site_information', function ($trail) {
    $trail->parent('home');
    $trail->push('このサイトについて', route('site_information.show',));
});

// プライバシーポリシー
Breadcrumbs::for('privacy_policy', function ($trail) {
    $trail->parent('home');
    $trail->push('プライバシーポリシー', route('privacy_policy.show',));
});
