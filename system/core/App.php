<?php

namespace Core;

use Core\Core;

class App extends Core{
    private $errors = [];
    public static $module = 'AppCore';
    public static $userId = false;
    static $USER_ID;

    function __construct($cfg = []){
        $this->run();

        //self::$userId = self::getUserId();

        if($this->getErrors())
            $this->view($this->getErrors());

        // foreach($config as $k => $v)
        //     Globals::set($k, $v);

        parent::__construct($cfg);

    }

    private function compile($app){
        // $this->compileInterface($app);
        // $this->compileCore($app);
        // $this->compileFuncs($app);
        // $this->compileClass($app);
        // $this->compileEntities($app);
        // $this->compileIncludes($app);
    }

    function getFiles($path){
        $fileList = [];
        if(is_dir($path)){
            $files = scandir($path);
            foreach ($files as $file)
                if ($file !== '.' && $file !== '..')
                    if (is_file($path . '/' . $file)) $fileList[] = $file;
        }
        return $fileList;
    }

    public function compileInterface($app){
        $path = $app ? INTERFACE_APP_PATH : INTERFACE_PATH;
        $files = $this->getFiles($path);
        foreach($files as $f){
            require_once $path.$f;
        }
    }

    public function compileCore($app){
        $path = $app ? CORE_APP_PATH : CORE_PATH;
        $files = $this->getFiles($path);
        foreach($files as $f){
            require_once $path.$f;
        }
    }

    public function compileFuncs($app){
        $path = $app ? FUNCS_APP_PATH : FUNCS_PATH;
        $files = $this->getFiles($path);
        foreach($files as $f){
            require_once $path.$f;
        }
    }

    public function compileClass($app){
        $path = $app ? CLASS_APP_PATH : CLASS_PATH;
        $files = $this->getFiles($path);
        foreach($files as $f){
            require_once $path.$f;
            $className = explode('.',$f);
            $className = $className[0];
            if(!class_exists($className))
                self::dieWithBackTrace(printf(__("Classe %s não definida ou não existe no arquivo"), $className).": ".$path.$f);
            $this->$className = new $className();
        }
    }
    
    public function compileIncludes($app){
        $path = $app ? INCLUDES_APP_PATH : INCLUDES_PATH;
        $files = $this->getFiles($path);
        foreach($files as $f){
            require_once $path.$f;
        }
    }

    /**
     * It works like migrations
     */
    public function compileEntities(){
        $path = ENTITIES_PATH;
        $ext = ENTITIES_EXTENSION;
        $files = $this->getFiles($path);
        foreach($files as $f){
            //use the Entity::class to compile all entities on this page
            //require_once $path.$f;
        }
    }

    /**
     * It works like lines of code. can set variables, call functions and events
     */
    public function compileActions(){
        $path = ACTIONS_PATH;
        $ext = ACTIONS_EXTENSION;
        $files = $this->getFiles($path);
        foreach($files as $f){
            //use the Entity::class to compile all entities on this page
            //require_once $path.$f;
        }
    }

    public function getErrors(){
        if($this->errors)
            print_r($this->errors);
    }

    public function view($content){
        // if(Context::get('api'))
        //     echo is_array($content) ? arrayToJson($content) : $content;
        // else
        //     echo $content;
    }

    public static function dieWithBackTrace($string = []){
        // p(print_r(Core::backTrace(), true));
        // p($string);
        // die;
    }

    /**
     * Unique Id of user client or general ID NULL
     */
    public static function getUserId(){
        return self::$USER_ID ?: (env('cache_by_browser') ? (md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['LOCAL_ADDR'].$_SERVER['LOCAL_PORT'].$_SERVER['REMOTE_ADDR'])) : 'NULL'); 
    }
}