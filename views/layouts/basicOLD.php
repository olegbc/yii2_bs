<?php
use app\assets\AppAsset;
use yii\bootstrap\NavBar;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\Modal;
use app\components\AlertWidget;
use yii\helpers\Url;
/**
 * Created by PhpStorm.
 * User: BC
 * Date: 22.07.2015
 * Time: 12:30
 */
/*  @var $content string */
/* @var $this \yii\web\View */
AppAsset::register($this);
$this->beginPage();
?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <title><?= Yii::$app->name ?></title>
        <meta charset="<?= Yii::$app->charset ?>">
        <?= Html::csrfMetaTags() ?>
        <?= $this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width,initial-scale=1']); ?>
        <?= $this->head() ?>
    </head>
    <body>
    <?= $this->beginBody() ?>
    <div  class="wrap">
        <?php
        NavBar::begin(
            [
                'brandLabel' => 'Тестовое приложение'
            ]
        );

        $menuItems = [
            [
                'label' => 'Главная <span class="glyphicon glyphicon-home"></span>',
                'url' => ['/main/index']
            ],
            [
                'label' => 'O проекте <span class="glyphicon gluphicon-question-sign"></span>',
                'url' => ['#'],
                'linkOptions' => [
                    'data-toggle' => 'modal',
                    'data-target' => '#modal',
                    'style' => 'cursonr: pointer, outline: none;'
                ]
            ],
            [
                'label' => 'Из коробки <span class="glyphicon glyphicon-inbox"></span>',
                'items' => [
                    '<li class="dropdown-header">Расширения</li>',
                    '<li class="divider"></li>',
                    [
                        'label' => 'Перейти к просмотру',
                        'url' => ['/widget-test/index']
                    ],
                ]
            ],
        ];

        if (!Yii::$app->user->isGuest):
            ?>
            <div class="navbar-form navbar-right">
                <button class="btn btn-sm btn-default"
                        data-container="body"
                        data-toggle="popover"
                        data-trigger="focus"
                        data-placement="bottom"
                        data-title="<?= Yii::$app->user->identity['username'] ?>"
                        data-content="
                            <a href='<?= Url::to(['/main/profile']) ?>' data-method='post'>Мой профиль</a><br>
                            <a href='<?= Url::to(['/main/logout']) ?>' data-method='post'>Выход</a>
                        ">
                    <span class="glyphicon glyphicon-user"></span>
                </button>
            </div>
        <?php
        endif;

        if(Yii::$app->user->isGuest):
            $menuItems[] = ['label' => 'Регистрация', 'url' => ['/main/reg']];
            $menuItems[] = ['label' => 'Войти', 'url' => ['/main/login']];
        else:
            $menuItems[] = ['label' => 'Выйти('.Yii::$app->user->identity['username'].')',
                'url' => ['/main/logout'],
                'linkOptions' => ['data-method' => 'post']
            ];
        endif;

        echo Nav::widget([
            'items' => $menuItems,
            'activateParents' => true,
            'encodeLabels' => false,
            'options' => [
                'class' => 'navbar-nav navbar-right'
            ]
        ]);
        Modal::begin([
            'header' => '<h2>phpNt</h2>',
            'id' => 'modal'
        ]);
        echo 'Проект для продвинутых PHP разработчиков.';
        Modal::end();

        ActiveForm::begin(
            [
                'action' => ['/найти'],
                'method' => 'get',
                'options' => ['class' => 'navbar-form navbar-right']
            ]
        );
        echo '<div class="input-group input-group-sm">';
        echo Html::input(
            'type:text',
            'search',
            '',
            [
                'placeholder' => 'Найти ...',
                'class' => 'form-control'
            ]
        );

        echo '<span class="input-group-btn">';
        echo Html::submitButton(
            '<span class="glyphicon glyphicon-search"></span>',
            [
                'class' => 'btn btn-success',
                'onClick' => 'window.location.href = this.form.action + "-" + this.form.search.value.replace(/[^\w\а-яё\А-ЯЁ]+/g,"_") + ".html";'
            ]
        );
        echo '</span></div>';
        ActiveForm::end();

        NavBar::end();
        ?>
        <div class="container">
            <?= AlertWidget::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <span class="badge">
            <span class="glyphicon glyphicon-copyright-mark"></span>phpNT <?= date('Y') ?>
        </span>
        </div>
    </footer>
    <?= $this->endBody() ?>
    </body>
    </html>
<?=
$this->endPage();
