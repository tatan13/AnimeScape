<?php

namespace App\Library;

class NumToLabel
{
    private array $label = [];
    private int $num;

    /**
     * コンストラクタでnumを設定
     * @param int $num
     * @return void
     */
    public function __construct($num)
    {
        $this->num = $num;
    }

    /**
     * ラベルの設定
     * @param array $label
     * @return void
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * numの設定
     * @param int $num
     * @return void
     */
    public function setNum($num)
    {
        $this->num = $num;
    }

    /**
     * ラベルを取得
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label[$this->num];
    }

    /**
     * numを取得
     *
     * @return int
     */
    public function getNum()
    {
        return $this->num;
    }
}
