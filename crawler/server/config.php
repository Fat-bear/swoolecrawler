<?php

return array(
    'server_addrss' => '127.0.0.1',
    'server_port' => 9501,
    'server_config' => array(
        'worker_num' => 8, //指定启动的worker进程数
        'daemonize' => true, //设置程序进入后台作为守护进程运行
        'log_file' => '/var/www/swoole/swoole.log', //指定日志文件路径
        'heartbeat_check_interval' => 60, //设置心跳检测间隔，每60秒遍历一次
        'heartbeat_idle_time' => 600, //设置某个连接允许的最大闲置时间，如果600秒内未向服务器发送任何数据，此连接将被强制关闭
        'open_eof_check' => true, //打开EOF检测功能
        'package_eof' => "\r\n", //设置EOF字符串
    )
);

