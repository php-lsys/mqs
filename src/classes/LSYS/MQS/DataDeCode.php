<?php
namespace LSYS\MQS;
interface DataDeCode{
    /**
     * 加载消息到处理对象
     * @param string $topic
     * @param string $msg
     * @return $this
     */
    public function loadMsg(string $topic,string $msg);
    /**
     * 根据当前加载信息返回处理消费处理类名
     * @return string|NULL
     */
    public function findClass():?string;
    /**
     * 解析当前加载消息内容
     * @return array
     */
    public function deCode():array;
}