<?php
/**
 * Created by PhpStorm.
 * User: BC
 * Date: 22.07.2015
 * Time: 14:43
 */
namespace app\components;

use yii\base\Widget;

class FirstWidget extends Widget{

    public $a;
    public $b;

    public function init(){
        parent::init();
        if($this->a === null){
            $this->a=0;
        }
        if($this->b === null){
            $this->b=0;
        }
    }

    public function run(){
        $c = $this->a + $this->b;

        return $this->render(
            'first',
            ['c' => $c]
        );
    }
}