<?php
namespace LSYS\MQS;
interface Sender{
    public function send($topic,$msg,$dealy);
}