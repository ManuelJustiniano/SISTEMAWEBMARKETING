<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$asset = yii\gii\GiiAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <div class="container-fluid page-container">
        <?php $this->beginBody() ?>
        <?php
        NavBar::begin([
            'brandLabel' => Html::img($asset->baseUrl . '/logo.png'),
            'brandUrl' => ['default/index'],
            'options' => ['class' => 'navbar-inverse navbar-fixed-top'],
        ]);
        echo Nav::widget([
            'options' => ['class' => 'nav navbar-nav navbar-right'],
            'items' => [
                ['label' => 'Home', 'url' => ['default/index']],
                ['label' => 'Help', 'url' => 'http://www.yiiframework.com/doc-2.0/guide-tool-gii.html'],
                ['label' => 'Application', 'url' => Yii::$app->homeUrl],
            ],
        ]);
        NavBar::end();
        ?>
        <div class="container content-container">
            <?= $content ?>
        </div>
        <div class="footer-fix"></div>
    </div>
    <footer class="footer">
        <div class="container">
            <p class="pull-left">A Product of <a href="http://www.yiisoft.com/">Yii Software LLC</a></p>
            <p class="pull-right"><?= Yii::powered() ?></p>
        </div>
    </footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
