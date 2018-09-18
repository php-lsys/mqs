<?php
use LSYS\MQS\Runer;
use LSYS\MQS\RunerLevel;
include_once __DIR__."/../vendor/autoload.php";
LSYS\Config\File::dirs(array(
    __DIR__."/config",
));
interface user_runer1 extends Runer{
    public function __construct($arg1,$arg2=1);
}
class user1 implements user_runer1{
    public function __construct($arg1,$arg2=1){
        print_r(func_get_args());
    }
}
class user2 implements user_runer1,RunerLevel{
    public static function get_level(){
        return 1;
    }
    public function __construct($arg1,$arg2=1){
        print_r(func_get_args());
    }
}
