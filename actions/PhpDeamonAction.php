<?php
namespace deluxcms\crontab\actions;

use Yii;
use yii\base\Action;

class PhpDeamonAction extends Action
{
    public function run()
    {
        Yii::$app->crontabManager->runPhpdeamon();
    }
}