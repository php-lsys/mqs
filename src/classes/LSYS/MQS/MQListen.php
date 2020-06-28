<?php
namespace LSYS\MQS;
class MQListen{
    protected $decodeer;
    protected $class=[];
    public function __construct(DataDeCode $decodeer){
        $this->decodeer=$decodeer;
    }
    /**
     * 注册消费者
     * @param string $topic
     * @param array $class
     */
    public function setRuner(string $topic,array $class){
        $this->class[$topic]=$class;
        return $this;
    }
    /**
     * 根据注册的消费者返回消费者对象列表
     * @param string $topic
     * @param string $runer_msg
     * @return ?[]
     */
    public function exec(string $topic,string $runer_msg):?array{
        $this->decodeer->loadMsg($topic, $runer_msg);
        $interface=$this->decodeer->findClass();
        if(!$interface||!interface_exists($interface,true))return null;
        $class=isset($this->class[$topic])?$this->class[$topic]:[];
        $classs=[];
        foreach ($class as $_class){
            if(is_subclass_of($_class, $interface))$classs[]=$_class;
        }
        foreach($classs as $k=>$class){
            try{
                if(is_subclass_of($class, RunerLevel::class))$level=$class::getLevel();
                else $level=0;
                $classs[$k]=array($class,$level);
            }catch(\Exception $e){
                $classs[$k]=$e;
            }
        }
        usort($classs,function($val,$val1){
            if($val[1]>$val1[1])return -1;
            if($val[1]<$val1[1])return 1;
            return 0;
        });
        $args=(array)$this->decodeer->deCode($topic,$runer_msg);
        foreach($classs as $k=>$v){
            if(is_array($v)){
                list($class)=$v;
                try{
                    $classs[$k]=(new \ReflectionClass($class))->newInstanceArgs($args);
                }catch(\Exception $e){
                    $classs[$k]=$e;
                }
            }
        }
        return $classs;
    }
}