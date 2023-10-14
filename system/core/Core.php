<?php
namespace Core;

use Core\Context;
use Core\Runtime;
use Core\Session;

class Core {
    public static $backTraceRoute = [];
    public static $entities = [];
    public static $events = [];
    public static $actions = [];
    public static $components = [];
    public static $response = [];//body, status, headers
    public $observer;

    function __construct($cfg = []){
        Session::start();
        Runtime::start();
        Globals::start();
        //$this->response = new Response();
            if(env('environment') == 'development')
                clearErrorLog();
            //First get all cached entities and so 
            //start event driver and loads all events list
            //$this->cacheDriver = new Cache();
            $this->components = Globals::$componentDriver->getAll();

            foreach($this->components['components'] as $component){
                Globals::$observer->register($component);
            }

            //$event = (new Event())->loadAll();
            $this->events = Cache::get('events') ?: [];

            $this->run();
           // p(get_object_vars($this));
        
    }
    public function run(){
        $main_evt = getConfig('main_event');
        p($main_evt);

        Runtime::nextState();
    }

    public function backTrace(){
        return self::$backTraceRoute;
    }

    public function setBackTrace($module = '', $function = '', $object = '', $vars = []){
        //$trace = sprintf(__("Erro no módulo '%s' no objeto '%s' ao realizar a ação '%s' utilizando os parametros '%s'"), $module, $object, $function, arrayToJson($vars));
        $trace = print_r([
            $module, $function, $object, $vars
        ], true);
        $trace .= br.bench();
        self::$backTraceRoute[] = $trace;

        //if(env('environment') == 'development' || env('backtrace_in_production'))
          //  App::dieWithBackTrace();
    }

    function update($params = []){
        
        //Get Observer runnning and see if all events and components have been processed

        if(Runtime::isLastState()){
            Runtime::$lastState = true;
            if(!Runtime::$lastState)
                $this->finish();
        }
    }


    /**
     * Its called when has no more events to execute. And all events from this runtime was executed successfully
     */
    function finish(){

        // updates the cache with all the components. Base and Instancied Components
        Cache::set('base_components',Globals::$observer->getAllComponentsToCache($this->components['base_components']));
        Session::set('components',Globals::$observer->getAllComponentsToCache());

        $headers = Context::get('headers');
        $content = Context::get('content');

    }

}