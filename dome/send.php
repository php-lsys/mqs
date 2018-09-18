<?php
use LSYS\MQS\Sender\SendCall;
use LSYS\MQS\MQSender;
use LSYS\MQS\DataCode\Simple;
use LSYS\DI\SingletonCallback;
require_once  __DIR__."/Bootstarp.php";

\LSYS\MQS\DI::set(function (){
    return (new \LSYS\MQS\DI)->mq_sender(new SingletonCallback(function(){
        $redismq=\LSYS\Redis\DI::get()->redis_mq();
        return new MQSender(new SendCall(function($topic,$msg,$dealy)use($redismq){
            return $redismq->push($topic,$msg,$dealy);
        }),new Simple("MQ"));
    }));  
});


var_dump(\LSYS\MQS\DI::get()->mq_sender()->send(user_runer1::class,[uniqid(),uniqid()]));