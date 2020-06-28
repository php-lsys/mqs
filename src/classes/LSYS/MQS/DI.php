<?php
namespace LSYS\MQS;
/**
 * @method \LSYS\MQS\MQSender MQSender() 
 */
class DI extends \LSYS\DI{
    /**
     * @return static
     */
    public static function get(){
        $di=parent::get();
        !isset($di->MQSender)&&$di->MQSender(new \LSYS\DI\VirtualCallback(\LSYS\MQS\MQSender::class));
        return $di;
    }
}


