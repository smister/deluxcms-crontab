<?php
namespace deluxcms\crontab\components;

use yii\base\Component;
use yii\base\ErrorException;

/**
 * @author smsiter
 */
class CrontabSystem extends Component implements CrontabContainerInterface
{
    public $binCrontab = 'sudo crontab';

    public function __construct(array $config = [])
    {
        parent::__construct($config);
        if (!function_exists('exec')) {
            throw new ErrorException("exec函数无法执行");
        }
    }


    /**
     * 获取cronttab列表
    */
    public function getCrontabList($parseArray = true)
    {
        $command = $this->binCrontab . " -l 2>&1";
        exec($command, $commandLines, $errCode);
        if ($errCode !== 0) {
            $outputStr = implode("\r\n", $commandLines);
            if (strpos($outputStr, 'no crontab') !== false) {
                return [];
            }
            throw new ErrorException("执行{$command}错误");
        }
        $commandLines = $this->filterInvaliableCrontab($commandLines);
        return $parseArray ? CrontabHelper::parseCrontabStrs($commandLines) : $commandLines;
    }

    /**
     * 过滤无效的数据
     *
     * @param array $crontabList 命令列表
     */
    protected function filterInvaliableCrontab($crontabList)
    {
        $crontabs = [];
        foreach ($crontabList as $crontab) {
            $crontab = trim($crontab);
            if (empty($crontab) || strpos($crontab, '#') === 0) {
                continue;
            }
            $crontabs[] = $crontab;
        }
        return $crontabs;
    }

    /**
     * 将crontab字符串入系统中
     *
     * @param string $crontabStr crontab命令
     */
    public function write($crontabStr)
    {
        $tmpFile = '/tmp/' .date('YmdHis') . mt_rand(1000, 9999) . '.tmp';
        $command = 'echo ' . escapeshellarg($crontabStr) . " > {$tmpFile} && " . $this->binCrontab . " {$tmpFile} && rm -rf {$tmpFile}";
        exec($command, $commandLines, $errCode);
        if ($errCode === 0) {
            return true;
        } else {
            return false;
        }
    }

}