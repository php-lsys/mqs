<?php
namespace LSYS\MQS;
interface DataEnCode{
    /**
     * 根据对应消费者类名找到消费主题队列名
     * @param string $class
     * @return string
     */
    public function findTopic(string $class):string;
    /**
     * 根据对象转为MQ消息
     * @param string $topic
     * @param string $class
     * @param array $args
     * @return string
     */
    public function msg(string $topic,string $class,array $args):string;
}