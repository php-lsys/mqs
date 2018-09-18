<?php
namespace LSYS\MQS;
class MQSender{
    protected $encoder;
    protected $sender;
    public function __construct(Sender $sender,DataEnCode $encodeer){
        $this->sender=$sender;
        $this->encoder=$encodeer;
    }
    public function send($class,array $args,$dealy=0){
        $topic=$this->encoder->find_topic($class);
        $msg=$this->encoder->msg($class,$args);
        return $this->sender->send($topic,$msg,$dealy);
    }
}