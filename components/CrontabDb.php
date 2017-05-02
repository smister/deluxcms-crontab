<?php
namespace deluxcms\crontab\components;

use deluxcms\crontab\models\Crontabs;
use yii\base\Component;

class CrontabDb extends Component implements CrontabContainerInterface
{
    public function getCrontabList()
    {
        return Crontabs::find()->select('type, min, hour, month, week, command')->where(['status' => 1])->asArray()->all();
    }   
}