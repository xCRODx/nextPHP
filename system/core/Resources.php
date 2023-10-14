<?php
namespace Core;
class Resources {
    
    static $meta = [];
    static $links = [];
    static $js = [];
    static $css = [];

    public static function addMeta($metaValues = [], $name = null){
        if(is_null($name))
            self::$meta[] = $metaValues;
        else
            self::$meta[$name] = $metaValues;
    }

    public static function getCss($css){

    }

    public static function getJs($js){

    }
}