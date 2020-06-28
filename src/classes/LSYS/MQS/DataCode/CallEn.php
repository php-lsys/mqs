<?php
namespace LSYS\MQS\DataCode;
use LSYS\MQS\DataEnCode;
class CallEn implements DataEnCode{
    protected $topic;
    protected $en_call;
    /**
     * 回调方式实现消息编码
     * @param array|string $topic
     * @param callable $en_call($class,$data,$topic)
     */
    public function __construct($topic,callable $en_call){
        $this->topic=$topic;
        $this->en_call=$en_call;
    }
    public function findTopic(string $class):string{
        if (is_array($this->topic)&&isset($this->topic[$class])) return $this->topic[$class];
        if (is_array($this->topic))return array_shift($this->topic);
        return strval($this->topic);
    }
    public function msg(string $topic,string $class,array $args):string{
        $method=new \ReflectionMethod($class,'__construct');
        $map=[];
        /**
         * @var \ReflectionParameter $v
         */
        foreach ($method->getParameters() as $k=>$v){
            if (!$v->isOptional()&&!array_key_exists($k, $args)){
                throw new \InvalidArgumentException("Uncaught ArgumentCountError: Too few arguments to function ".$class."::__construct(), ".$k." passed");
            }
            if (array_key_exists($k, $args)&&(is_resource($args[$k])||is_object($args[$k]))){
                throw new \InvalidArgumentException("Uncaught ArgumentCountError: arguments can't be resource or object,your give is :".(is_object($args[$k])?"object":"resource"));
            }
            $map[$v->getName()]=$args[$k];
        }
        return (string)call_user_func_arr($this->en_call,$class,$map,$topic);
    }
}