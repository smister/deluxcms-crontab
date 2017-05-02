<?php
namespace deluxcms\crontab\components;

use deluxcms\crontab\models\CrontabJob;
use Yii;
use yii\base\Component;

/**
 * crontab管理类
*/
class CrontabManager extends Component
{
    /**
     * @var 所有获取crontab的类
    */
    public $crontainerClass = [];

    /**
     * @var 系统的crontab
    */
    public $systemCrontab;

    /**
     * @var 执行系统的crontab
    */
    public $binCrontab = 'sudo crontab';

    /**
     * @var 实例化的crontab类
    */
    protected $crontabContainer = [];

    /**
     * @var php守护进程
     */
    protected $phpDeamon;

    /**
     * @var php守护进程类
    */
    public $phpDeamonConfig = [
        'class' => '',
        'command' => 'php yii phpdeamon'
    ];

    public function init()
    {
        parent::init();
        foreach ($this->crontainerClass as $class) {
            $crontaner = Yii::createObject($class);
            if ($crontaner instanceof CrontabContainerInterface) {
                $this->crontabContainer[get_class($crontaner)] = $crontaner;
            }
        }

        //设置默认系统进程
        if ($this->systemCrontab === null) {
            $this->systemCrontab = CrontabSystem::className();
        }

        //添加默认的system
        $this->crontabContainer[$this->systemCrontab] = Yii::createObject([
            'class' => $this->systemCrontab,
            'binCrontab' => $this->binCrontab,  //执行crontab
        ]);


        //设置php守护进程
        if (empty($this->phpDeamonConfig['class'])) {
            $this->phpDeamonConfig['class'] = PhpDeamon::className();
        }

        $this->phpDeamonConfig['manager'] = $this;
        $this->phpDeamon = Yii::createObject($this->phpDeamonConfig);
    }

    /**
     * 将所有的crontab写入系统中
     *
     * @param bool $phpDeamon 是否开启php监听进程
     */
    public function writeToSystem($phpDeamon = false)
    {
        $sysCrontabs = [];
        $crontabList = $this->getCrontabList();
        $phpDeamonStr = $this->phpDeamon->getDeamonStr();
        $this->phpDeamon->killPhpDeamon();
        foreach ($crontabList as $type => $crontabs) {
            if ($phpDeamon && $type == CrontabJob::TYPE_PHP) continue;
            foreach ($crontabs as $crontab) {
                //转化后的crontab
                $cStr = "{$crontab['min']} {$crontab['hour']} {$crontab['day']} {$crontab['month']} {$crontab['week']} {$crontab['command']}";
                if (in_array($cStr, $sysCrontabs) || (!$phpDeamon && $phpDeamonStr == $cStr)) {//如果没有开启php守护进程就过滤掉原有存在的
                    continue;
                }
                $sysCrontabs[] = $cStr;
            }
        }

        //存在，追加phpdeamon
        if ($phpDeamon) $sysCrontabs[] = $phpDeamonStr;

        $crontabStr = implode("\r\n", array_unique($sysCrontabs));

        //写入系统
       return $this->write($crontabStr);
    }

    /**
     * 还原系统的crontab
    */
    public function restoreSystemCrontab()
    {
        $this->phpDeamon->killPhpDeamon();
        $systemCrontabs = $this->getCrontab()->getCrontabList();
        $crontabList = $this->getCrontabList(true);

        $normalCrontabs = [ //默认把phpdeamon加入
            $this->phpDeamon->getDeamonStr()
        ];

        //整合非系统的crontab
        foreach ($crontabList as $type => $crontabs) {
            foreach ($crontabs as $crontab) {
                $normalCrontabs[] = "{$crontab['min']} {$crontab['hour']} {$crontab['day']} {$crontab['month']} {$crontab['week']} {$crontab['command']}";
            }
        }

        //过滤获取系统原用的cron
        $sysCrontabs = [];
        foreach ($systemCrontabs as $key => $crontab) {
            $tmpCrontabStr = "{$crontab['min']} {$crontab['hour']} {$crontab['day']} {$crontab['month']} {$crontab['week']} {$crontab['command']}";
            if (in_array($tmpCrontabStr, $normalCrontabs)) {
                continue;
            }
            $sysCrontabs[] = $tmpCrontabStr;
        }

        $crontabStr = implode("\r\n", array_unique($sysCrontabs));

        return $this->write($crontabStr);
    }


    /**
     * 直接讲字符串写入
    */
    public function write($crontabStr)
    {
        return $this->getCrontab()->write($crontabStr);
    }

    /**
     * 获取所有crontab的数据
     *
     * @param bool $ignoreSystem 是否忽略系统的crontab
    */
    public function getCrontabList($ignoreSystem = false)
    {
        $crontabs = [];
        foreach ($this->crontabContainer as $className => $crontabContainer) {
            if ($ignoreSystem && $className == $this->systemCrontab) continue;  //忽略系统级别的
            $cronbs = $crontabContainer->getCrontabList();
            foreach ($cronbs as $cronb) {
                $crontabJob = new CrontabJob();
                $crontabJob->setAttributes($cronb);
                if ($crontabJob->validate()) {
                    $crontabs[$crontabJob->type][] = $crontabJob->getData();
                }
            }
        }
        return $crontabs;
    }


    /**
     * 只读系统中的crontab
    */
    public function getSystemCrontabList($parseArray = false)
    {
        return implode("\r\n", $this->getCrontab()->getCrontabList($parseArray));
    }

    /**
     * 获取系统默认crontab
    */
    public function getCrontab()
    {
        return $this->crontabContainer[$this->systemCrontab];
    }


    /**
     * 执行phpdeamon
    */
    public function runPhpdeamon()
    {
        $this->phpDeamon->run();
    }

}