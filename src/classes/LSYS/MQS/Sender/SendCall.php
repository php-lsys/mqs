<?php
namespace LSYS\MQS\Sender;
use LSYS\MQS\Sender;
class SendCall implements Sender{
    /**
     * 通过回调方法处理发送消息
     * @param callable $callback
     */
    public function __construct(callable $callback){
        $this->callback=$callback;
    }
    /**
     * {@inheritDoc}
     * @see \LSYS\MQS\Sender::send()
     */
    public function send(string $topic,string $msg,int $dealy):bool{
        return (bool)call_user_func_array($this->callback,func_get_args());
    }
}