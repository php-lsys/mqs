<?php
namespace LSYS\MQS;
interface DataDeCode{
    public function find_class($topic,$msg);
    public function decode($topic,$msg);
}