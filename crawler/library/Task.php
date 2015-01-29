<?php
namespace Crawler\Library;

class Task {

    public function __construct() {
        
    }
    
    public function onTask($serv, $task_id, $from_id, $data) {
        return $data;
    }
    
    public function onFinish($serv, $task_id, $data) {
        echo "Client {$fd} close connection\n";
    }

}