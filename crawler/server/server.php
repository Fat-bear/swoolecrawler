<?php

class Server {

    private $serv;

    public function __construct($config) {
        if (!extension_loaded('swoole')) {
            die('swoole extension not found.');
        }
        $this->serv = new swoole_server($config['server_addrss'], $config['server_port'], SWOOLE_BASE, SWOOLE_SOCK_TCP);
        $this->setConfig($config['server_config']);
        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));
        $this->serv->on('Finish', array($this, 'onFinish'));
        $this->serv->on('WorkerStart', array($this, 'onWorkerStart'));
        $this->serv->on('Task', array($this, 'onTask'));
        $this->serv->on('Timer', array($this, 'onTimer'));
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
        $serv->addtimer(1000);
    }
    
    public function onTask($serv, $task_id, $from_id, $data) {
        return $data;
    }
    
    public function onTimer($serv, $interval) {
        if ($interval == 1000) {
            
        }
    }
}


$server_config = include 'config.php';
$server = new Server($server_config);
$server->run();