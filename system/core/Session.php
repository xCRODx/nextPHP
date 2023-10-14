<?php 
namespace Core;

class Session {

    public static function start(){
        @session_start();
    }

    public static function get($k){
        return isset($_SESSION[$k]) ? $_SESSION[$k] : false;
    }

    public static function set($k, $v){
        $_SESSION[$k] = $v;
    }

    public static function destroy() {
        @session_destroy();
    }
}