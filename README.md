# deluxcms-crontab
 该插件主要用户yii2管理crontab定期执行任务，用数据库的方式管理crontab，并且可将php的执行的进程，接管到一个php中，而不需要启动多个php执行进程

# 安装
1、下载deluxcms-contrab组件
---------------------------
```
compser require deluxcms/deluxcms-crontab
```

2、配置config/main-local.php文件,添加crontab模块
--------------------------
```
'modules' => [
        'crontab' => [//管理模块
            'class' => 'deluxcms\crontab\Module',
        ],
],
```

3、导入数据库
--------------------------
```
php /path/yii migrate --migrationPath=@vendor/deluxcms/crontab/migrations
```

4、开启exec等权限
-----------------
编辑php.ini,禁用掉disable_functions获将disable_functions内的exec,shell_exec去掉
```
;disable_functions = passthru,exec,system,chroot,scandir,chgrp,chown,shell_exec,proc_open,proc_get_status,popen,ini_alter,ini_restore,dl,openlog,syslog,readlink,symlink,popepassthru,stream_socket_server
```
5、如果开启服务（www）没有操作crontab的权限，赋予权限
-----------------------------------------------------
visudo进行添加一下内容，如果需要限制更多的权限，请参考sudo的使用
```
www     ALL=(ALL:ALL) NOPASSWD:ALL
```
6、配置crontab
----------------
配置config/main-local.php文件，添加crontabManager
```
...
'components' => [
        ...
        'crontabManager' => [
            'class' => 'vendor\deluxcms\crontab\components\CrontabManager', //设置manager类
            //'binCrontab' => 'sudo crontab',//设置系统的crontab执行路径
            'crontainerClass' => [ //设置crontab获取的类
                ['class' => '\vendor\deluxcms\crontab\components\CrontabDb'],
                [ //这个是执行类我们可以动态添加命令进去
                    'class' =>'\vendor\deluxcms\crontab\components\CrontabList',
                    'crontabs' =>[
                        '*/2 * * * * ls /tmp',  //格式1
                        [   //格式2
                            'type' => 1, //1系统类型，2php类型
                            'min' => '*',
                            'hour' => '*',
                            'day' => '*',
                            'month' => '*',
                            'week' => '*',
                            'command' => 'ls'
                        ]
                    ]
                ]
            ],
            'phpDeamonConfig' => [ //设置phpdeamon
                //'class' => ''
                'command' => 'php /home/wwwroot/deluxcms/yii phpdeamon &' //执行phpdeamon的脚本
            ],
        ]
        ...
 ],
...
]
```
8、在console命令执行模式，添加PhpdeamonController.php
如果这里的相对改变了，请改变上面配置文件phpDeamonConfig的文件
```
<?php
namespace console\controllers;

use yii\console\Controller;

class PhpdeamonController extends Controller
{
    public function actions()
    {
        return [
            'index' => [
                'class' => 'vendor\deluxcms\crontab\actions\PhpDeamonAction'
            ]
        ];
    }
}
```

