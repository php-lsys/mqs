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



//回调方式实现自定义消息编码示例
// \LSYS\MQS\DI::set(function (){
//     return (new \LSYS\MQS\DI)->MQSender(new SingletonCallback(function(){
//         $redismq=\LSYS\Redis\DI::get()->redisMQ();
//         return new MQSender(new SendCall(function($topic,$msg,$dealy)use($redismq){
//             return $redismq->push($topic,$msg,$dealy);
//         }),new \LSYS\MQS\DataCode\CallEn('MQ', function($class,$data,$topic){
//             $map=[
//                 user_runer1::class=>'1'
//             ];
//             $data=['type'=>$map[$class]??0,'data'=>$data];
//             return json_encode($data,JSON_UNESCAPED_UNICODE);
//         }));
//     }));
// });
// $topics=["MQ"];
// foreach ($topics as $k=>$topic){
//     $listen[$topic]=new \LSYS\MQS\MQListen(new \LSYS\MQS\DataCode\CallDe($topic,function ($topic,$msg) {
//         $msg=json_decode($msg);
//         $map=[
//             '1'=>user_runer1::class
//         ];
//         if(!is_array($msg)||!isset($msg['type'])||!isset($map[$msg['type']]))return;
//         return [$map[$msg['type']],$msg['data']??[]];
//     }));
//     switch ($topic){
//         case 'MQ':
//             $listen[$topic]->setRuner($topic,[user1::class,user2::class]);
//             break;
//         default: unset($topics[$k]);
//     }
// }
