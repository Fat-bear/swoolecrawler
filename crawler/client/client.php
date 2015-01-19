<?php

class Client {

    private $client;

    public function __construct() {
        if (!extension_loaded('swoole')) {
            die('swoole extension not found.');
        }
        $this->client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);

        $this->client->on('connect', array($this, 'onConnect'));
        $this->client->on('receive', array($this, 'onReceive'));
        $this->client->on('close', array($this, 'onClose'));
        $this->client->on('error', array($this, 'onError'));
    }

    public function connect($config) {
        $fp = $this->client->connect($config['connect_addrss'], $config['connect_port'] , 1);
        if( !$fp ) {
            echo "Error: {$fp->errMsg}[{$fp->errCode}]\n";
            return;
        }
    }

    public function onReceive($cli, $data ) {
        echo "Get Message From Server: {$data}\n";
    }

    public function onConnect($cli) {
        $cli->send("hello world\n");
    }

    public function onClose($cli) {
        echo "Client close connection\n";
    }

    public function onError($cli) {
        echo "connect fail\n";
    }

    public function send($data) {
        $this->client->send( $data );
    }

    public function isConnected() {
        return $this->client->isConnected();
    }
}


$client_config = include 'config.php';
$cli = new Client($client_config);
$cli->connect();