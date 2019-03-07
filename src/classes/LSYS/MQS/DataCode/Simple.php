<?php
namespace LSYS\MQS\DataCode;
use LSYS\MQS\DataDeCode;
use LSYS\MQS\DataEnCode;
class Simple implements DataDeCode,DataEnCode{
    protected $topic;
    protected $namespace;
    public function __construct($topic='MQ',$namespace=''){
        $this->topic=$topic;
        $this->namespace=empty($namespace)?'':"\\".$namespace."\\";
    }
    public function findClass($topic,$msg){
        //sss:{}
        if(preg_match("/^([a-z][a-z0-9_]+):[\{\[]/si",$msg,$match)){
            return $this->namespace.$match[1];
        }
        return null;
    }
    public function decode($topic,$msg){
        $msg=substr($msg,strpos($msg,":")+1);
        $msg=json_decode($msg,true);
        if(is_array($msg))return $msg;
        return [];
    }
    public function findTopic($class){
        if (is_array($this->topic)&&isset($this->topic[$class])) return $this->topic[$class];
        if (is_array($this->topic))return array_shift($this->topic);
        return strval($this->topic);
    }
    public function msg($class,array $args){
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