<?php
namespace LSYS\MQS;
/**
 * @method \LSYS\MQS\MQSender mq_sender() 
 */
class DI extends \LSYS\DI{
    /**
     * @return static
     */
    public static function get(){
        $di=parent::get();
        !isset($di->mq_sender)&&$di->mq_sender(new \LSYS\DI\VirtualCallback(\LSYS\MQS\MQSender::class));
        return $di;
    }
}


