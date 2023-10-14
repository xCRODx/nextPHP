<?php 
namespace Core;

use Interfaces\ObjectCore_Interface;
use Core\Event;

/**
 * Its could be a component or even a event, state
 */
class ObjectCore implements ObjectCore_Interface{

    public $domain; //Is the original or initial source from this object when the instantiate  - Its used for compare
    public $image; // Is the final source from this object
    public $name;
    public $object;
    public $events = [];

    public $actualState;
    public $statesPassed = [];

    public $uuid;

    function __construct($object = []) {
        if($object['type'] == 'object'){
            $this->statesPassed = $object['image']['statesPassed'] ?: [];
            $this->setState($object['image']['state'] ?: 'initial');
            
            $this->domain = $object['domain'] ?: [];
            $this->image = $object['image'] ?: [];
            $this->uuid = isset($object['uuid']) ? $object['uuid'] : (isset($object['image']['alias']) ? $object['image']['alias'] : null);
            $this->name = $object['name'];
            $this->events = $object['events'];
             
        }
    }

    public function getName(){
        return $this->name;
    }

    public function getUUID(){
        return $this->uuid;
    }

    public function setUUID($uuid){
        $this->uuid = $uuid;
    }
    public function getAlias(){
        return $this->image['alias'] ?: $this->getUUID();
    }

    public function setState($state){
        $this->image['statesPassed'][] = $state;
        $this->image['actualState'] = $state;
    }

    public function getState(){
        return $this->actualState;
    }

    function setNewProp($propertie, $val){
        $this->image['properties'][$propertie] = $val;
    }

    function getNewProp($propertie){
        return $this->image['properties'][$propertie];
    }

    function getOldProp($propertie){
        return $this->domain['properties'][$propertie];
    }

    function setOldProp($propertie, $val){
        $this->domain[$propertie] = $val;
    }

    function updateAttribute($prop, $val){
        $this->setOldProp($prop, $this->getNewProp($prop));//backup the old prop
        $this->setNewProp($prop, $val);
    }

    
    /*
    * Return the variables and the content from this component
    */
    public function getBaseObject(){
        return classToArray($this);
    }

}