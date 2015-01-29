<?php
namespace Crawler\Server;

use Crawler\Library as Lib;

class Server {

    private $serv;

    public function __construct($config) {
        if (!extension_loaded('swoole')) {
            die('Swoole extension not found.');
        }
        $this->serv = new \swoole_server($config['server_addrss'], $config['server_port'], SWOOLE_BASE, SWOOLE_SOCK_TCP);
        $this->setConfig($config['server_config']);
        
        $log = new Lib\Log();
        $task = new Lib\Task();
        $timer = new Lib\Timer();

        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));
        $this->serv->on('WorkerStart', array($this, 'onWorkerStart'));
        $this->serv->on('Finish', array($task, 'onFinish'));
        $this->serv->on('Task', array($task, 'onTask'));
        $this->serv->on('Timer', array($timer, 'onTimer'));
    }
    
    public function setConfig($config) {
        $this->serv->set($config);
    }

    public function run() {
        $this->serv->start();
    }

//    主进程内的回调函数
    public function onStart($serv) {
        echo "Ready Client Connect.\n";
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

//    task_worker进程内的回调函数
    public function onWorkerStart($serv, $worker_id) {
        echo "$worker_id\n";
        if($worker_id == 0) {
            $serv->addtimer(1000);
        }
    }

}

require __DIR__.'/../library/Common.php';
$server_config = include 'config.php';
$server = new Server($server_config);
$server->run();