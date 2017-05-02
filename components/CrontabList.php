<?php
namespace deluxcms\crontab\components;

use yii\base\Component;

class CrontabList extends Component implements CrontabContainerInterface
{
    public $crontabs = [];

    /**
     * 获取crontab列表
    */
    public function getCrontabList()
    {
        $crontabs = [];
        foreach ($this->crontabs as $crontab) {
            if (is_array($crontab)) {
                $crontabs[] = $crontab;
            } elseif (is_string($crontab)) {
                $crontabs[] = CrontabHelper::parseCrontabStr($crontab);
            }
        }

        return $crontabs;
    }

    /**
     * 添加一个crontab
     * 格式
     * 1、数组 = [
     *    'type' => ‘类型[1=系统],[2=php]’,
     *    'min' => '分钟',
     *    'hour' => '小时',
     *    'day' => '天',
     *    'month' => '月',
     *    'week' => '周',
     *    'command' => '命令'
     * ]
     * 2、字符串跟crontab格式一直 , 默认为系统类型
     * @param mix $crontab crontab相关数据
     * @param mix $key 下标
    */
    public function add($crontab, $key = null)
    {
        if ($key === null) {
            $this->crontabs[] = $crontab;
        } else {
            $this->crontabs[$key] = $crontab;
        }
    }

    /**
     * 删除一个crontab
     *
     * @param string $key 键值
    */
    public function remove($key)
    {
        if (isset($this->crontabs[$key])) {
            unset($this->crontabs[$key]);
        }
    }

    /**
     * 删除所有
    */
    public function destory()
    {
        $this->crontabs = [];
    }

}