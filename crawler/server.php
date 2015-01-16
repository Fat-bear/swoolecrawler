<?php

class Server {

    private $serv;

    public function __construct() {
        $this->serv = new swoole_server("127.0.0.1", 9501, SWOOLE_BASE, SWOOLE_SOCK_TCP);
        $this->serv->set(array(
            'worker_num' => 8,
            'daemonize' => true, //是否作为守护进程
            'log_file' => '/var/www/swoole/swoole.log',
            'heartbeat_check_interval' => 60,//每60秒遍历一次
            'heartbeat_idle_time' => 600,//如果600秒内未向服务器发送任何数据，此连接将被强制关闭
            'open_eof_check' => true, //打开EOF检测
            'package_eof' => "\r\n", //设置EOF
            'package_max_length' => 8192
        ));
        $this->serv->on('start', array($this, 'onStart'));
        $this->serv->on('connect', array($this, 'onConnect'));
        $this->serv->on('receive', array($this, 'onReceive'));
        $this->serv->on('close', array($this, 'onClose'));
    }

    public function run() {
        $this->serv->start();
    }

    public function onStart($serv) {
        echo "Client:Connect.\n";
    }

    public function onConnect($serv, $fd) {
        $serv->send($fd, "Hello {$fd}!");
    }

    public function onReceive($serv, $fd, $from_id, $data) {
        $serv->send($fd, 'Swoole: '.$data);
    }

    public function onClose($serv, $fd) {
        echo "Client {$fd} close connection\n";
    }
}

$server = new Server();
$server->run();