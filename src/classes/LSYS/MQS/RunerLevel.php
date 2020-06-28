<?php
namespace LSYS\MQS;
interface RunerLevel{
    /**
     * 消费者对象优先级
     * @return int
     */
    public static function getLevel():int;
}