<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model deluxcms\crontab\models\Crontabs */

$this->title = '编辑系统Crontab';
$this->params['breadcrumbs'][] = ['label' => 'Crontab列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crontabs-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('还原系统Crontab', ['restore-crontabs'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="crontabs-form">
        <?php $form = ActiveForm::begin(); ?>
        <div class="form-group field-crontabs-system <?= $status?>">
            <textarea id="crontabs-system" class="form-control" name="system-list" style="min-height:400px;"><?=Yii::$app->crontabManager->getSystemCrontabList()?></textarea>
            <div class="help-block danger"><?= $status == 'has-success' ? '编辑成功' : ($status == 'has-error' ? '编辑出错' : '')?></div>
        </div>
        <div class="form-group">
            <?= Html::submitButton('编辑', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
