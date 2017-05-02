<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel deluxcms\crontab\models\CrontabsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Crontabs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="crontabs-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建Crontab', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('编辑系统Crontab', ['system-crontab-list'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('将所有Crontab写入系统', ['write-crontabs'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('开启PHP守护进程', ['start-phpdeamon'], ['class' => 'btn btn-success']) ?>
    </p>
<?php Pjax::begin(); ?>    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'description',
            [
                'attribute' => 'type',
                'filter' => [1 => '系统类型', 2 => 'PHP类型'],
                'content' => function ($model) {
                    return $model->type == 1 ? '系统类型' : 'PHP类型';
                }
            ],
            'min',
            'hour',
            'day',
            'month',
            'week',
            'command',
            [
                'attribute' => 'status',
                'filter' => [1 => '开启', 0 => '禁用'],
                'content' => function ($model) {
                    return $model->status == 1 ? '开启' : '禁用';
                }
            ],
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<?php Pjax::end(); ?></div>
