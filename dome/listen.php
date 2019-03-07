<?php
use LSYS\MQS\DataCode\Simple;
use LSYS\MQS\MQListen;
require_once  __DIR__."/Bootstarp.php";

$topics=["MQ"];
foreach ($topics as $k=>$topic){
    $listen[$topic]=new MQListen(new Simple($topic));
    switch ($topic){
        case 'MQ':
            $listen[$topic]->setRuner($topic,[user1::class,user2::class]);
        break;
        default: unset($topics[$k]);
    }
}

$redismq=\LSYS\Redis\DI::get()->redisMQ();
while (true){
    $data=$redismq->pop($topics,FALSE,$ack);
    if(count($data)!=2)continue;
    list($topic,$msg)=$data;
    $result=$listen[$topic]->exec($topic, $msg);
    $error=false;
    foreach ($result as $v){
        if ($v instanceof Exception)$error=true;
    }
    if(!$error)$redismq->ack($topics,$ack,$data);
}