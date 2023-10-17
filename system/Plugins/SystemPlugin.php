<?php 
namespace Components;
use Core\Action;
use Interfaces\PluginInterface;
use Includes\PluginInclude;

class SystemPlugin extends Action implements PluginInterface {

    static $name = 'System';
    static $functionList = [
        
    ];

    static $version = 'v01';

    static $info = [
        'author' => 'Cleydson Rodrigues',
        'email' => 'cleydsonr2@gmail.com',
        'url' => 'http://github.com/xCRODx'
    ];

    public static function getInfoData() :PluginInclude{
        return new PluginInclude(self::$name, self::getFunctionList(), self::$version, self::$info);
    }

    public static function getFunctionList(): array {
        $functionList = [];
        foreach(self::$functionList as $function){
            self::getActionByName(self::$name, $function);
        }
        return $functionList;
    }

    static function getActionByName($plugin, $function): array{
        return parent::getAllActions($plugin)[$function] ?: [];
    }

    static function to_print($params){

    }
}