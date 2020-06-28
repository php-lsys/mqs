<?php
namespace LSYS\MQS\DataCode;
use LSYS\MQS\DataDeCode;
class CallDe implements DataDeCode{
    protected $topic;
    protected $de_call;
    protected $de_class;
    protected $de_msg;
    /**
     * 回调方式实现消息解码
     * @param array|string $topic
     * @param callable $de_call($topic,$msg):[$class,array $msg]
     */
    public function __construct($topic,callable $de_call){
        $this->topic=$topic;
        $this->de_call=$de_call;
    }
    public function loadMsg(string $topic, string $msg)
    {
        $res=call_user_func($this->de_call,$topic,$msg);
        if(!is_array($res)||!isset($res[0])||!isset($res[1]))return $this;
        $this->de_class=$res[0];
        $this->de_msg=$res[1];
        return $this;
    }
    public function findClass():?string{
        return $this->de_class;
    }
    public function deCode():array{
        if(!is_array($this->de_msg))return [];
        return $this->de_msg;
    }
}