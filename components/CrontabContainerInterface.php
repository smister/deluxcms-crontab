<?php
namespace deluxcms\crontab\components;

/**
 * @author smsiter
 */
interface CrontabContainerInterface
{
    /**
     * 读取系统的crontab错误
    */
    public function getCrontabList();
}