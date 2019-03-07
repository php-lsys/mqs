<?php
namespace LSYS\MQS;
interface DataDeCode{
    public function findClass($topic,$msg);
    public function decode($topic,$msg);
}