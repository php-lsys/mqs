<?php
namespace LSYS\MQS;
class MQSender{
    protected $encoder;
    protected $sender;
    public function __construct(Sender $sender,DataEnCode $encodeer){
        $this->sender=$sender;
        $this->encoder=$encodeer;
    }
    /**
     * 对外统一发送方法
     * @param string $class
     * @param array $args
     * @param int $dealy
     * @return bool
     */
    public function send(string $class,array $args,int $dealy=0):bool{
        $topic=$this->encoder->findTopic($class);
        $msg=$this->encoder->msg($topic,$class,$args);
        return $this->sender->send($topic,$msg,$dealy);
    }
}