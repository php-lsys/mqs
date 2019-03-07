<?php
use LSYS\MQS\Sender\SendCall;
use LSYS\MQS\MQSender;
use LSYS\MQS\DataCode\Simple;
use LSYS\DI\SingletonCallback;
require_once  __DIR__."/Bootstarp.php";

\LSYS\MQS\DI::set(function (){
    return (new \LSYS\MQS\DI)->MQSender(new SingletonCallback(function(){
        $redismq=\LSYS\Redis\DI::get()->redisMQ();
        return new MQSender(new SendCall(function($topic,$msg,$dealy)use($redismq){
            return $redismq->push($topic,$msg,$dealy);
        }),new Simple("MQ"));
    }));  
});


var_dump(\LSYS\MQS\DI::get()->MQSender()->send(user_runer1::class,[uniqid(),uniqid()]));