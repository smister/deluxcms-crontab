<?php
namespace deluxcms\crontab\components;

use deluxcms\crontab\models\CrontabJob;
use yii\base\Component;

/**
 * php的守护进程
 */
class PhpDeamon extends Component
{
    public $manager;
    public $command = 'php yii phpdeamon';

    /**
     * 获取一个守护进程
    */
    public function getDeamon()
    {
        $crontabJob = new CrontabJob();
        $crontabJob->command = $this->command;
        return $crontabJob->getData();
    }

    /**
     * 格式化Deamon
    */
    public function getDeamonStr()
    {
        $crontab = $this->getDeamon();
        return "{$crontab['min']} {$crontab['hour']} {$crontab['day']} {$crontab['month']} {$crontab['week']} {$crontab['command']}";
    }

    /**
     * 监听phpdeamon
    */
    public function run()
    {
        $crontabList = $this->manager->getCrontabList();
        $phpProcesses = $crontabList[CrontabJob::TYPE_PHP];

        //'min' => 'i' , 'hour' => 'G' , 'day' => 'j' , 'month' => 'n' , 'week' => 'w'
        list($dateData['min'], $dateData['hour'], $dateData['day'], $dateData['month'], $dateData['week'])  = explode('-', date('i-G-j-n-w'));

        foreach ($phpProcesses as $phpProcess) {//遍历执行php
            $status = true;
            $detailData = $this->parseDetailDate($phpProcess);
            foreach ($dateData as $dateKey => $dateDate) {
                if (!isset($detailData[$dateKey]) || !in_array($dateDate, $detailData[$dateKey])) {
                    $status = false;
                    break;
                }
            }

            if ($status && !$this->processRunning($phpProcess['command'])) {//检测通过
                $retData[] = shell_exec(CrontabHelper::redirectOutputByBackend($phpProcess['command']));
            }
        }
    }


    /**
     * check the &tab
     */
    protected function processRunning($command)
    {
        $command = $this->filterBackendSign($command);
        @exec("ps -ef |grep '{$command}' |grep -v grep", $commandLines, $errCode);
        if ($errCode === 0 && count($commandLines) > 0) {
            return true;
        }
        return false;
    }

    /**
     * 出去命令的&符号数据和重定向等标示
     */
    protected function filterBackendSign($command)
    {
        $command = trim($command);
        if ((($pos = strpos($command, '>')) !== false) || (($pos = strpos($command, '2>')) !== false) ||  (($pos = strrpos($command, '&')) !== false && ($pos + 1) == strlen($command))) {
            $command = trim(substr($command, 0, $pos));
        }
        return $command;
    }

    /**
     * 解析命令时间
     */
    protected function parseDetailDate($crontabJob)
    {
        $crontabJob = [
            'min' => $this->parseMins($crontabJob['min']),
            'hour' => $this->parseHours($crontabJob['hour']),
            'day' => $this->parseDays($crontabJob['day']),
            'month' => $this->parseMonths($crontabJob['month']),
            'week' => $this->parseWeeks($crontabJob['week']),
        ];

        foreach ($crontabJob as $value) {
            if (empty($value)) {
                return false;
            }
        }
        return $crontabJob;
    }

    /**
     * 详细解析星期数据
     */
    protected function parseWeeks($week)
    {
        //0是星期天
        return $this->parseDate($week, 0, 6);
    }

    /**
     * 详细解析月份
     */
    protected function parseMonths($month)
    {
        return $this->parseDate($month, 1, 12);
    }

    /**
     * 详细解析天
     */
    protected function parseDays($day)
    {
        $BeginDate=date('Y-m-01', strtotime(date("Y-m-d")));
        $endDay = date('d', strtotime("$BeginDate +1 month -1 day"));
        return $this->parseDate($day, 1, $endDay);
    }

    /**
     * 详细解析小时数据
     */
    protected function parseHours($hours)
    {
        return $this->parseDate($hours, 0, 23);
    }

    /**
     * 详细解析分钟
     */
    protected function parseMins($minutes)
    {
        return $this->parseDate($minutes, 0, 59);
    }

    /**
     * 解析具体格式
    */
    protected function parseDate($date, $min, $max)
    {
        $result = [];
        if ($date == '*') {
            $result = $this->parseAll($min, $max);
        } elseif (strpos($date, ',') !== false) {
            $result = $this->parseComma($date, $min, $max);
        } elseif (strpos($date, '/') !== false) {
            $result = $this->parseDivide($date, $min, $max);
        } elseif (strpos($date, '-') !== false) {
            $result = $this->parseCrossbar($date, $min, $max);
        }
        return array_unique($result);
    }

    /**
     * 解析*
     */
    protected function parseAll($min, $max)
    {
        $result = [];
        for ($i = $min; $i <= $max; $i++) {
            $result[] = $i;
        }
        return $result;
    }

    /**
     * 解析逗号
     *
     * @param string $date 解析字符串
     * @param int $min 时间范围最小值
     * @param int $max 时间范围最大值
     * @param int $divisor 被除数
     */
    protected function parseComma($date, $min, $max)
    {
        $result = [];
        $dateData = explode(',', $date);
        foreach ($dateData as $d) {
            if (strpos($d, '/') !== false) {
                $result = array_merge($result, self::parseDivide($d, $min, $max));
            } elseif (strpos($d, '-') !== false) {
                $result = array_merge($result, self::parseCrossbar($d, $min, $max));
            }elseif ($d >= $min && $d <= $max) {
                $result[] = $d;
            }
        }
        return $result;
    }

    /**
     * 解析-
     */
    protected function parseCrossbar($date, $min, $max)
    {
        $result = [];
        $dateData = explode('-', $date);
        if (count($dateData) != 2 || !is_numeric($dateData[0]) || !is_numeric($dateData[1]) || $dateData[0] < $min || $dateData[1] > $max) return [];
        list($dateMin, $dateMax) = $dateData;
        for ($i = $dateMin; $i <= $dateMax; $i++) {
            $result[] = $i;
        }
        return $result;
    }

    /**
     * 解析除号
     *
     * @param string $date 时间
     * @param int $min 时间范围最小值
     * @param int $max 时间范围最大值
     */
    protected function parseDivide($date, $min, $max)
    {
        $result = [];
        $dateData = explode('/', $date);
        if (count($dateData) != 2 || !is_numeric($dateData[1]) || $dateData[1] < 0) return [];
        list($dateStr, $divisor) = $dateData;;
        if (is_numeric($divisor)) {
            $numArr = [];
            if ($dateStr == '*') {
                $numArr = self::parseAll($min, $max);
            } elseif (strpos($dateStr, '-') !== false) {
                $numArr = self::parseCrossbar($dateStr, $min, $max);
            }

            if ($divisor == 0) $result =  $numArr;
            elseif ($numArr) { //不为空时
                $leftStart = $numArr[0];
                foreach ($numArr as $value) {
                    if (($value - $leftStart) % $divisor == 0) {
                        $result[] = $value;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * 删掉守护进程
    */
    public function killPhpDeamon()
    {
        $command = explode(' ', $this->command);
        if (isset($command[2])) {
            exec( "sudo kill -9 `ps -ef | grep yii | grep '{$command[2]}' | awk '{print $2}'`", $commandLines, $errCode); //删掉守护进程
            if ($errCode === 0) {
                return true;
            }
        }
        return false;
    }
}