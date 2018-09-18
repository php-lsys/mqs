<?php
namespace LSYS\MQS\Sender;
use LSYS\MQS\Sender;
class SendCall implements Sender{
    public function __construct(callable $callback){
        $this->callback=$callback;
    }
    public function send($topic,$msg,$dealy){
        return call_user_func_array($this->callback,func_get_args());
    }
}