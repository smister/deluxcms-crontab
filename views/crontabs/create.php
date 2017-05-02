<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model deluxcms\crontab\models\Crontabs */

$this->title = '创建Crontab';
$this->params['breadcrumbs'][] = ['label' => 'Crontab列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crontabs-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
