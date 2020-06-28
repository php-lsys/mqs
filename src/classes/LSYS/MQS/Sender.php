<?php
namespace LSYS\MQS;
interface Sender{
    /**
     * 统一发送接口
     * @param string $topic
     * @param string $msg
     * @param int $dealy
     * @return bool
     */
    public function send(string $topic,string $msg,int $dealy):bool;
}