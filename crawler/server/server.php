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


$server_config = include 'config.php';
$server = new Server($server_config);
$server->run();