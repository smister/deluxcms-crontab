<?php
namespace deluxcms\crontab\components;
use deluxcms\crontab\models\CrontabJob;

/**
 * @author smsiter
 */
class CrontabHelper
{
    /**
     * 解析多条crontab的数组
     *
     * @param array $crontabs 多条crontab
     */
    public static function parseCrontabStrs($crontabs, $type = CrontabJob::TYPE_SYSTEM)
    {
        $result = [];
        if (!is_array($crontabs)) return $result;
        foreach ($crontabs as $crontab) {
            $parseData = self::parseCrontabStr($crontab, $type);
            if ($parseData) {
                $result[] = $parseData;
            }
        }
        return $result;
    }

    /**
     * 解析crontab字符串，为详细的分，时，日，月，周，命令
     * 格式* * * * * 命令
     *    1-3 * * * * 命令
     *    *\/10 * * * * 命令
     *    2-10\/10 * * * * 命令
     *    1,2,3 * * * * 命令
     * 解析后：
     * Array
     *(
     *  [mins] => 2,3,4
     *  [hours] => *\/2
     *  [days] => 1,3-8/3
     *  [months] => *
     *  [weeks] => *
     *  [command] => ls -la
     *)
     * @param string $crontab 一条crontab
     * @param bool $details 是否详细解析时间成为数组形式, 默认为否
     */
    public static function parseCrontabStr($crontab, $type = CrontabJob::TYPE_SYSTEM)
    {
        $crontabData = explode(' ', trim($crontab), 6);
        if (count($crontabData) == 6) {
            $crontabJobs = ['type' => $type];
            list($crontabJobs['min'], $crontabJobs['hour'], $crontabJobs['day'], $crontabJobs['month'], $crontabJobs['week'], $crontabJobs['command']) = $crontabData;
            foreach ($crontabJobs as $value) {
                if (empty($value)) {
                    return false;
                }
            }
            return $crontabJobs;
        }
        return false;
    }

    /**
     * 为挂载的程序添加重定向
     */
    public static function redirectOutputByBackend($command)
    {
        $command = trim($command);
        $andPos = (int)strrpos($command, '&');
        if (($andPos + 1) == strlen($command)) {
            if (strpos($command, '2>') === false && strpos($command, '>') === false) {
                $command = substr($command, 0, $andPos) . ' > /dev/null 2>&1 &';
            }
        }
        return $command;
    }
}
