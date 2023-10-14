<?php
namespace Core;
use Core\Resources;
class View {
    public static $metaHtml = '';
    public static $linksHtml = '';
    public static $cssResources = '';
    public static $jsResources = '';
    public static $jsVars = '';
    public static $cssResourcesFooter = '';
    public static $jsResourcesFooter = '';
    public static $visualComponents = [];//rendered visual components - Can be targeted by events and other components

    public static function drawMeta(){
        foreach(Resources::$meta as $m){
            $metas = '';
            foreach($m as $k => $v)
                $metas .= $k.' = "'.$v.'"';

            self::$metaHtml .= "<meta {$metas}>";
        }
    }
    
    public static function drawLinks(){
        foreach(Resources::$links as $l){
            $links = '';
            foreach($l as $k => $v)
                $links .= $k.' = "'.$v.'"';

            self::$linksHtml .= "<link {$links}>";
        }
    }

    public function drawCssResources(){
        foreach(Resources::$css as $c => $resource){
            if($resource['url'])
                $content = '<link rel="stylesheet" href="'.$resource['url'].'">';
            else
                $content = "<script>{$resource['body']}</script>";
            
            if($resource['footer']){
                self::$cssResourcesFooter .= $content;
            }else{
                self::$cssResources .= $content;
            }
        }
    }

    public function drawJsResources(){
        foreach(Resources::$js as $j => $resource){
            if($resource['url'])
                $content = '<script src="'.$resource['url'].'">';
            else
                $content = "<script>{$resource['body']}</script>";

            if($resource['footer']){
                self::$jsResourcesFooter .= $content;
            }else{
                self::$jsResources .= $content;
            }
        }
    }

    public static function addJsVars($k, $v){
        if(is_array($v))
            $v = arrayToJson($v);
        elseif(is_bool($v))
            $v = $v ? 'true' : 'false';
        elseif(is_int($v))
            $v = (int) $v;
        elseif(is_string($v))
            $v = "'{$v}'";
        else
            App::dieWithBackTrace(__("Erro ao definir variavel pública") . "var {$k} (".gettype($v).")");

        self::$jsVars .= "{$v};";
    }

    public static function drawVisualComponent($component, $father = 'body'){
        
    }

}