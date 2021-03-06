<?php
namespace LSYS\MQS\DataCode;
use LSYS\MQS\DataDeCode;
use LSYS\MQS\DataEnCode;
class Simple implements DataDeCode,DataEnCode{
    protected $topic;
    protected $namespace;
    protected $de_topic;
    protected $de_msg;
    /**
     * 内置基本消息编码跟解码实现
     * @param string|array $topic $topic=['class_name'=>'quque_name'] OR $topic='queue_name'
     * @param string $namespace
     */
    public function __construct($topic='MQ',string $namespace=''){
        $this->topic=$topic;
        $this->namespace=empty($namespace)?'':"\\".$namespace."\\";
    }
    public function loadMsg(string $topic, string $msg)
    {
        $this->de_topic=$topic;
        $this->de_msg=$msg;
        return $this;
    }
    public function findClass():?string{
        $msg=$this->de_msg;
        //sss:{}
        $match=[];
        if(preg_match("/^([a-z][a-z0-9_]+):[\{\[]/si",$msg,$match)){
            return $this->namespace.$match[1];
        }
        return null;
    }
    public function deCode():array{
        $msg=$this->de_msg;
        $msg=substr($msg,strpos($msg,":")+1);
        $msg=json_decode($msg,true);
        if(is_array($msg))return $msg;
        return [];
    }
    public function findTopic(string $class):string{
        if (is_array($this->topic)&&isset($this->topic[$class])) return $this->topic[$class];
        if (is_array($this->topic))return array_shift($this->topic);
        return strval($this->topic);
    }
    public function msg(string $topic,string $class,array $args):string{
        $method=new \ReflectionMethod($class,'__construct');
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
        }
        return basename(str_replace("\\","/",$class)).":".json_encode($args);
    }
}