<?php
namespace LSYS\MQS;
interface DataEnCode{
    public function findTopic($class);
    public function msg($class,array $args);
}