<?php

namespace Core;

use Interfaces\BasicStaticInterface;

use Core\Observer;
use Core\Context;

use Drivers\ComponentDriver;
//use Drivers\EventDriver;
//use Drivers\BehaviorDriver;
use Classes\QueryBuilder;
use Classes\Response;

/**
 * Set, Get global vars
 */
class Globals implements BasicStaticInterface {
    static $vars = [];
    static $actionList = [];
    static $started = false;
    static $classes = [];
    static $observer;
    static $db;
    static $query_builder;
    static $componentDriver;


    public static function set($k, $v)  { self::$vars["globals.{$k}"] = $v; }
    public static function get($k)      { return self::$vars["globals.{$k}"]; }
    public static function delete($k)   { unset(self::$vars["globals.{$k}"]); }

    public static function start($class = []){
        if(!self::$started){
            self::$observer = new Observer();
            self::$componentDriver = new ComponentDriver();
        }

        if(is_array($class)){
            self::instanceClasses($class);
        }else
            self::instanceClass($class);
        
    }

    public static function instanceClass($class) {
        $className = is_array($class) ? $class[0] : $class;
        if(is_array($class)){
            unset($class[0]);//Remove the class name
            $classParameters = $class;
        }

        if(class_exists($className))
            self::$classes[$class] = call_user_func_array([$className, '__construct'], $classParameters);
    }

    public static function instanceClasses(Array $classes){
        foreach($classes as $class)
            self::instanceClass($class);
    }

    public static function callMethod(String $class, String $method, Array $parameters){
        if(!self::$classes[$class]){
            throw new \Exception(t('Erro: Method "%s" not found', $class));
        }
        return call_user_func_array([$class, $method], $parameters);
    }

    public static function getAllVars(){
        $vars = [];
        foreach(array_merge(self::$vars, Context::$context) as $var => $val){
            $vars[$var] = $val;
        }
        $vars = array_merge(self::$observer->getAllComponentsVars(), $vars);

        return is_array($vars) ? $vars : $vars;
    }

}