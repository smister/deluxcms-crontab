<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deluxcms\crontab\models\CrontabsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="crontabs-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'min') ?>

    <?= $form->field($model, 'hour') ?>

    <?php // echo $form->field($model, 'day') ?>

    <?php // echo $form->field($model, 'month') ?>

    <?php // echo $form->field($model, 'week') ?>

    <?php // echo $form->field($model, 'command') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
