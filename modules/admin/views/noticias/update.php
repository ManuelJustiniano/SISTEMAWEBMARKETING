<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Noticias */

$this->title = 'Noticia: ' . $model->titulo;
$this->params['breadcrumbs'][] = ['label' => 'Noticias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->idnoticia, 'url' => ['view', 'id' => $model->idnoticia]];
$this->params['breadcrumbs'][] = 'Update';
?>
<section class="content-header">
    <h1><?= Html::encode($this->title) ?></h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</section>