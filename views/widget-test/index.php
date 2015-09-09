<?php
use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
if(isset($_GET['id'])) {
    echo '<p>' . $_GET['id'] . '</p>';
}
echo Html::a(
    'Передать сюда id = 123',
    Url::to(['widget-test/index','id' => '123'])
);
echo "<br />";
echo Html::a(
    'Найти статьи за 2015 год',
    Url::to([
        'main/search',
        'search' => 'статья',
        'year' => '2015'
    ])
);
