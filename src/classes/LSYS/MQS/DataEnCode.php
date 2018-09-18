<?php
namespace LSYS\MQS;
interface DataEnCode{
    public function find_topic($class);
    public function msg($class,array $args);
}