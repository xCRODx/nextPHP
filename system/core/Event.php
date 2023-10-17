<?php

namespace Core;

use Core\ObjectCore;
use Core\Interpreter;
use Core\Action;

use Exceptions\Event_Exception;

class Event extends ObjectCore{

    public $eventList = [];
    public $params = [];
    public $origin;//UUID Origin

    private $localVars = [];
    private $returnType = null;
    private $returnVal = [];  //can be any type of variable: string, array, integer, float
    
    public $break = false;

    function __construct($event, $driverCache = null) {
        parent::__construct($event);
        //Needs a driver to get the data from cache or session
        //$cache = CacheDriver::init();
        // In cache saves the global events
        // In Session saves the components events

        //on construct gets all events and components from cache
        $this->eventList = $event['events'];
        $this->params = $event['params'];
    }

    public function setOrigin(Object &$object){
        $this->origin = $object->getUUID();
    }

    public function fireEvent(ObjectCore &$baseObject){
        $vars = $this->localVars;
        $params = $this->params;

        //Execute the action to insert into a var such value and return to the las driver
        foreach($this->eventList as $index => $event){
            if($this->break)
                return;
            //$action = new Action($baseObject, $event, $vars, $params);
            $action = new Action($event);
            $action->fire($this, $baseObject);
        }
    }

    public function interpreter($origin, $event, $vars, $params){
        Interpreter::eventFire($this, $origin, $event, $vars, $params);
    }

    public function getVar($var){
        $allVars = $this->getAllVars();
        $val = Interpreter::replaceVars($var, $allVars);
        return $val;
    }

    public function getAllVars(){
        return array_merge(Globals::getAllVars(), $this->localVars);
    }

    /**
     * structure of variables: {{:component.UUID.{local?}.varname}} | {{:local.varname}} | {{:global.varname}} | {{:context.varname}}
     */
    public function setVar($var, $val){
        $detail = explode('.', $var);
        $plugin = $detail[0];
        $varname = end($detail);
        
        $val = $this->getVar($val);

        switch(strtolower($plugin)){
            case 'componente':
                $uuid = $detail[1];
                $prop = $detail[2];
                if($prop == 'properties')
                    Globals::$observer->components[$uuid]->setNewProp($varname, $val);

                break;
            case 'local':
                $this->setLocalVar($varname, $val);
                break;
            case 'global':
                Globals::set($varname, $val);
                break;
            case 'context':
                Context::set($varname, $val);
                break;
            default:
                throw new Event_Exception($this, ['var' => $var, 'val' => $val], __FUNCTION__);
        }
    }

    public function getLocalVar($var = null){
        return is_null($var) ? $this->localVars : (isset($this->localVars["local.".$var]) ? $this->localVars["local.".$var] : null);
    }

    public function setLocalVar($var, $val){
        $this->localVars["local.".$var] = $val;
    }


}
