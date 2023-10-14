<?php

namespace Core;

use Core\ObjectCore;
use Core\Interpreter;

class Event extends ObjectCore{

    public $eventList = [];
    public $origin;//UUID Origin

    private $localVars = [];
    private $returnType = null;
    private $returnVars = [];

    function __construct($event, $driverCache = null) {
        parent::__construct($event);
        //Needs a driver to get the data from cache or session
        //$cache = CacheDriver::init();
        // In cache saves the global events
        // In Session saves the components events

        //on construct gets all events and components from cache
    }

    public function setOrigin(Object &$object){
        $this->origin = $object->getUUID();
    }

    public function fireEvent(ObjectCore &$baseObject){
        //Execute the action to insert into a var such value and return to the las driver
        foreach($this->eventList as $evId => $event){

        }
    }

    public function getVar($var, $val){
        $allVars = array_merge(Globals::getAllVars(), [$var => $val]);
        $val = Interpreter::replaceVars($var, $allVars);
        $this->localVars[$var] = $val;
        
        return $val;
    }

    public function getLocalVar($var, $val){
        $this->localVars["local.".$var] = $val;
    }

}