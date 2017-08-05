<?php
session_start();
require "conf.inc.php";

spl_autoload_register(function ($class){
    if(file_exists("core/".$class.".class.php")){
        include "core/".$class.".class.php";
    }
    else if(file_exists("models/".$class.".class.php")){
        include "models/".$class.".class.php";
    }
});

$route = new Routing();
