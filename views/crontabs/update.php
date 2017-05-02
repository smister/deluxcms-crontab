<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model deluxcms\crontab\models\Crontabs */

$this->title = '更新Crontab: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Crontab列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="crontabs-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
