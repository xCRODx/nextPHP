<?php 

namespace Core;
use Core\ObjectCore;
use Core\Event;
use Core\Component;

use SplObserver;
use SplSubject;

//O campo X do component A atualizou, estava configurado para disparar o evento Z
//Faz um loop e identifica se houve algum disparo
final class Observer implements SplSubject {
    public $events = array();
    public $components = [];
    public $max_loop = 10; // The max actions that a unique object can take in a runtime loop
    private $objLoop = [];//Count the update time that a component has beens called.

    function __construct(Array $params = []){
        $this->max_loop = isset($params['max_loop']) ? $params['max_loop'] : $this->max_loop;
    }

    public function attach(SplObserver $object) : void{
        if ($object instanceof ObjectCore) {
            $className = get_class($object);
            if($className === 'Component'){
                $objectUUID = $object->getUUID();
                $this->components[$objectUUID] = $object;
            }else if($className === 'Event'){
                $this->events[$object->getName()] = $object;
            }
        }
    }

    public function detach(SplObserver  $object) : void{
        if ($object instanceof ObjectCore) {
            $className = get_class($object);
            if($className === 'Component'){
                $objectUUID = $object->getUUID();

                //Can only execute triggers if the component is actually enabled
                if(isset($this->components[$objectUUID])){
                    if($this->components[$objectUUID]->isEnabled()){
                        if(isset($this->components[$objectUUID]->events['beforedestory']))
                            $this->components[$objectUUID]->events['beforedestory']->fireEvent($this->components[$objectUUID]);
                        $this->components[$objectUUID]->actualState = 'destroyed';
                        $this->components[$objectUUID]->statesPassed['destroyed'] = true;
                        $this->components[$objectUUID]->uuid = null;
                        $this->components[$objectUUID]->name = null;
                        $this->components[$objectUUID]->image = [];
                        $this->components[$objectUUID]->disble();
                    
                        if(isset($this->components[$objectUUID]->events['afterdestroy']))
                            $this->components[$objectUUID]->events['afterdestroy']->fireEvent($this->components[$objectUUID]);
                    }
                    unset($this->components[$objectUUID]);
                }

            }
        }
        //remove this from cache
    }
    
    public function create(ObjectCore &$object){
        $className = get_class($object);
        if($className === 'Component'){
            $objectUUID = (String) $object->getUUID() ?: uniqid();
            if(!isset($this->components[$objectUUID])){
                //If object doesn't exists, its will be created 
                $this->components[$objectUUID] = $object;
            }
            if($this->components[$objectUUID]->isEnabled()){
                //Can only execute triggers if the component is actually enable
                $this->objLoop[$objectUUID] = 1;

                if(isset($this->components[$objectUUID]->events['beforecreate']))
                    $this->components[$objectUUID]->events['beforecreate']->fireEvent($this->components);

                $this->components[$objectUUID]->setUUID($objectUUID);
                $this->components[$objectUUID]->setState('created');
                
                if(isset($this->components[$objectUUID]->events['aftercreate']))
                    $this->components[$objectUUID]->events['aftercreate']->fireEvent($this->components);
            }
        }
    }

    public function setAlias(ObjectCore $object, String $alias){
        $uuid = $object->getUUID();
        if(isset($this->components[$uuid])){
            $object = $this->$this->components[$uuid];
        }
        $this->components[$alias] = $object;
        $this->components[$alias]->setUUID($alias);

    }

    public function updateAttribute(Object $object, $attribute, $val = null){
        $className = get_class($object);
        if($className === 'Component'){
            $objectUUID = $object->getUUID();
            $this->objLoop[$objectUUID]++;
            $this->components[$objectUUID]->updateAttribute($attribute, $val);
        }
        $this->notify();
    }

    public function notify() : void {
        //get this event and fire it
        //pass by all components and fire it
    }

    public function getAllComponentsVars(){
        $vars = [];

        foreach($this->components as $component){
            $alias = $component->getAlias();
            $cVars = $component->getAllVars();
            foreach($cVars as $var => $val){
                $vars["component.{$alias}.{$var}"] = $val;
            }
        }
    }

    public function getAllComponentsToCache(Array $components = []){
        $list = [];
        if(count($components)){
            foreach($components as $component){
                $list[$component->getName()] = $component->getBaseObject();
            }
        }elseif(count($this->components)){
            foreach($this->components as $uuid => $component){
                $list[$uuid] = $component->getBaseObject();
            }
        }
        return $list;
    }

    public function update(\SplSubject $subject): void
    {
       /*printf(
           "%s has been notified of \"%s\"\n", 
            $this->components->email, 
            $subject->video->title
        );
        */
    }

}