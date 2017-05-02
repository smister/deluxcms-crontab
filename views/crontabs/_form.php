<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deluxcms\crontab\models\Crontabs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="crontabs-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList([1 => '系统类型', 2 => 'php类型']) ?>

    <?= $form->field($model, 'min')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hour')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'day')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'month')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'week')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'command')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([1 => '开启', 0 => '禁用']) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
