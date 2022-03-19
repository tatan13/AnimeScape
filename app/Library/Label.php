<?php
namespace App\Library;

class Label
{
    private array $label = [];
    private $num;

    public function __construct($num)
    {
        $this->num = $num;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function setNum($label)
    {
        $this->num = $num;
    }

    public function getLabel()
    {
        return $this->label[$this->num];
    }

    public function getNum()
    {
        return $this->num;
    }
}
