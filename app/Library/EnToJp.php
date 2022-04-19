<?php

namespace App\Library;

class EnToJp
{
    private array $jp_label = [];
    private String $english;

    /**
     * コンストラクタでenglishを設定
     * @param string $english
     * @return void
     */
    public function __construct($english)
    {
        $this->english = $english;
    }

    /**
     * 日本語ラベルの設定
     * @param array $jp_label
     * @return void
     */
    public function setJpLabel($jp_label)
    {
        $this->jp_label = $jp_label;
    }

    /**
     * englishの設定
     * @param string $english
     * @return void
     */
    public function setEnglish($english)
    {
        $this->english = $english;
    }

    /**
     * ラベルを取得
     *
     * @return string
     */
    public function getJpLabel()
    {
        return $this->jp_label[$this->english];
    }

    /**
     * englishを取得
     *
     * @return string
     */
    public function getEnglish()
    {
        return $this->english;
    }
}