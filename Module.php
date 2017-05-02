<?php

namespace deluxcms\crontab;

/**
 * post module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'deluxcms\crontab\controllers';

    public $defaultRoute = 'crontabs';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
