<?php

class Server {

    private $serv;

    public function __construct($config) {
        if (!extension_loaded('swoole')) {
            die('swoole extension not found.');
        }
        $this->serv = new swoole_server($config['server_addrss'], $config['server_port'], SWOOLE_BASE, SWOOLE_SOCK_TCP);
        $this->setConfig($config['server_config']);
        $this->serv->on('start', array($this, 'onStart'));
        $this->serv->on('connect', array($this, 'onConnect'));
        $this->serv->on('receive', array($this, 'onReceive'));
        $this->serv->on('close', array($this, 'onClose'));
    }
    
    public function setConfig($config) {
        $this->serv->set($config);
    }

    public function run() {
        $this->serv->start();
    }

//    主进程内的回调函数
    public function onStart($serv) {
        echo "Client:Connect.\n";
    }

//    Worker进程内的回调函数
    public function onConnect($serv, $fd) {
        $serv->send($fd, "Hello {$fd}!");
    }

    public function onReceive($serv, $fd, $from_id, $data) {
        $task_id = $serv->task("Async");
        $serv->send($fd, 'Swoole: '.$data);
    }

    public function onClose($serv, $fd) {
        echo "Client {$fd} close connection\n";
    }
    
    public function onFinish($serv, $task_id, $data) {
        echo "Client {$fd} close connection\n";
    }
    
//    task_worker进程内的回调函数
    public function onWorkerStart($serv, $worker_id) {
        
    }
    
    public function onTask($serv, $task_id, $from_id, $data) {
        return $data;
    }
}


$server_config = include 'config.php';
$server = new Server($server_config);
$server->run();