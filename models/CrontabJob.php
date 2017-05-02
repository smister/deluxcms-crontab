<?php
namespace deluxcms\crontab\models;

use yii\base\Model;

/**
 * @author smsiter
 */
class CrontabJob extends Model
{
    /**
     * @var 系统类型
    */
    const TYPE_SYSTEM = 1;

    /**
     * @var php类型
    */
    const TYPE_PHP = 2;

    /**
     * 类型[1=系统类型],[2=php类型]
    */
    public $type;

    /**
     * @var 分钟
     */
    public $min = '*';

    /**
     * @var 小时
     */
    public $hour = '*';

    /**
     * @var 天
     */
    public $day = '*';

    /**
     * @var 月
     */
    public $month = '*';

    /**
     * @var 星期
     */
    public $week = '*';

    /**
     * @var 命令
     */
    public $command;

    public function init()
    {
        parent::init();
        if (empty($this->type)) { //默认系统类型
            $this->type = self::TYPE_SYSTEM;
        }
    }

    public function rules()
    {
        return [
            ['type', 'in', 'range' => [1, 2]],
            ['command', 'required'],
            [['min', 'hour', 'day', 'month', 'week'], 'safe']
        ];
    }

    /**
     * 读取数据
    */
    public function getData()
    {
        $data = $this->getAttributes();
        unset($data['type']);
        return $data;
    }
}