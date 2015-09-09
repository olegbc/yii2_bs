<?php
/**
 * Created by PhpStorm.
 * User: BC
 * Date: 22.07.2015
 * Time: 15:03
 */
namespace app\components;

use yii\base\Widget;

class SecondWidget extends Widget{

    public function init(){
        parent::init();
        ob_start();
    }

    public function run(){
        $content = ob_get_clean();
        return $this->render(
            'second',
            [
                'content' => $content
            ]
        );
    }
}