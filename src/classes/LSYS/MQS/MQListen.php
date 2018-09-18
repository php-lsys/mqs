<?php
namespace LSYS\MQS;
class MQListen{
    protected $decodeer;
    protected $class=[];
    public function __construct(DataDeCode $decodeer){
        $this->decodeer=$decodeer;
    }
    public function set_runer($topic,array $class){
        $this->class[$topic]=$class;
    }
    public function exec($topic,$runer_msg){
        $interface=$this->decodeer->find_class($topic,$runer_msg);
        if(!$interface||!interface_exists($interface,true))return null;
        $class=isset($this->class[$topic])?$this->class[$topic]:[];
        $classs=[];
        foreach ($class as $_class){
            if(is_subclass_of($_class, $interface))$classs[]=$_class;
        }
        foreach($classs as $k=>$class){
            try{
                if(is_subclass_of($class, RunerLevel::class))$level=$class::get_level();
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
        $args=(array)$this->decodeer->decode($topic,$runer_msg);
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