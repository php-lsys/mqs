消息队列消息的发送&&消费封装
===
1. 发送消息
```
<?php
use LSYS\MQS\Sender\SendCall;
use LSYS\MQS\MQSender;
use LSYS\MQS\DataCode\Simple;
use LSYS\DI\SingletonCallback;
require_once  __DIR__."/Bootstarp.php";
//公共部分 注册发送DI
\LSYS\MQS\DI::set(function (){
    return (new \LSYS\MQS\DI)->mq_sender(new SingletonCallback(function(){
        //你的具体的消息队列服务器,这里使用REDIS
        $redismq=\LSYS\Redis\DI::get()->redisMQ();
        return new MQSender(new SendCall(function($topic,$msg,$dealy)use($redismq){
          //根据你实际的MQ服务器进行发送
            return $redismq->push($topic,$msg,$dealy);
        }),new Simple("MQ"));
    }));  
});
//具体的调用部分
var_dump(\LSYS\MQS\DI::get()->mq_sender()->send(user_runer1::class,[uniqid(),uniqid()]));
```
2. 消费消息
```
<?php
use LSYS\MQS\DataCode\Simple;
use LSYS\MQS\MQListen;
require_once  __DIR__."/Bootstarp.php";
$topics=["MQ"];//消费主题,创建消费监听对象
foreach ($topics as $k=>$topic){
    $listen[$topic]=new MQListen(new Simple($topic));
    switch ($topic){
        case 'MQ':
            $listen[$topic]->set_runer($topic,[user1::class,user2::class]);//设置指定主题的执行类
        break;
        default: unset($topics[$k]);
    }
}
$redismq=\LSYS\Redis\DI::get()->redisMQ();//得到REDIS MQ对象 在redis上实现
while (true){
    $data=$redismq->pop($topics,FALSE,$ack);//从MQ里取消息
    if(count($data)!=2)continue;
    list($topic,$msg)=$data;
    $result=$listen[$topic]->exec($topic, $msg);//执行消费,上面绑定的,执行错误返回异常对象
    $error=false;
    foreach ($result as $v){
        if ($v instanceof Exception)$error=true;
    }
    if(!$error)$redismq->ack($topics,$ack,$data);//未发送异常,确认消费
}
```
