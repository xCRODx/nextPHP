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
    public $events = [];
    public $enabled = true;

    public $actualState;
    public $statesPassed = [];

    public $uuid;

    function __construct($object = []) {
        if($object['type'] == 'object') {
            $this->statesPassed = $object['image']['statesPassed'] ?: [];
            $this->setState($object['image']['state'] ?: 'initial');
            $this->enabled = (isset($object['enabled']) ? ($object['enabled'] === 'true' || $object['enabled'] === true ? true : false) : $this->enabled );
            $this->domain = $object['domain'] ?: [];
            $this->image = $object['image'] ?: [];
            $this->uuid = isset($object['uuid']) ? $object['uuid'] : (isset($object['image']['alias']) ? $object['image']['alias'] : null);
            $this->name = $object['name'];
            $this->events = $object['events'];
        }
    }

    public function enable() {
        $this->enabled = true;
    }

    public function disable(){
        $this->enabled = false;
    }

    public function isEnabled(): bool{
        return $this->enabled ? true : false;
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

    public function getAllvars(){
        $vars = [];
        $temp_obj = $this->getBaseObject();

        if(isset($temp_obj['domain'])) unset($temp_obj['domain']);
        if(isset($temp_obj['statesPassed'])) unset($temp_obj['statesPassed']);
        
        if(isset($temp_obj['image']['properties'])) {
            $local_vars = $temp_obj['image']['properties'];
            unset($temp_obj['image']);
        }

        foreach($temp_obj as $var => $val){
            $vars[$var] = $val;
        }

        foreach($local_vars as $var => $val){
            $vars["local.{$var}"] = $val;
        }

        return is_array($vars) ? $vars : [];
    }

    public function setState($state){
        $this->image['statesPassed'][] = $state;
        $this->image['actualState'] = $state;
    }

    public function getState(){
        return $this->actualState;
    }

    function setNewProp($prop, $val){
        $this->setOldProp($prop, $this->getNewProp($prop));
        $this->image['properties'][$prop] = $val;
    }

    function getNewProp($prop){
        return $this->image['properties'][$prop];
    }

    function getOldProp($prop){
        return $this->domain['properties'][$prop];
    }

    function setOldProp($prop, $val){
        $this->domain[$prop] = $val;
    }

    /*
    * Return the variables and the content from this component
    */
    public function getBaseObject(){
        return classToArray($this);
    }

}
