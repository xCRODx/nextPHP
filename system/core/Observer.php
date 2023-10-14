<?php 
namespace Core;
use Core\ObjectCore;
use Core\Event;
use Core\Component;

//O campo X do component A atualizou, estava configurado para disparar o evento Z
//Faz um loop e identifica se houve algum disparo
class Observer{
    public $events = array();
    public $components = [];
    public $max_loop = 10; // The max actions that a unique object can take in a runtime loop
    private $objLoop = [];//Count the update time that a component has beens called.

    function __construct(Array $params = []){
        $this->max_loop = $params['max_loop'] ?: $this->max_loop;
    }

    public function register(ObjectCore $object){
        $className = get_class($object);
        if($className === 'Component'){
            $objectUUID = $object->getUUID();
            $this->components[$objectUUID] = $object;
        }else if($className === 'Event'){
            $this->events[$object->getName()] = $object;
        }
    }

    public function dispatch(Event $event){
        //get this event and fire it
    }

    
    public function create(ObjectCore &$object){
        $className = get_class($object);
        if($className === 'Component'){
            $objectUUID = $object->getUUID()  ?: uniqid();
            $this->objLoop[$objectUUID] = 1;
            
            if(isset($this->components[$objectUUID]->events['beforecreate']))
                $this->components[$objectUUID]->events['beforecreate']->fireEvent($this->components);
            $this->components[$objectUUID]->uuid = $objectUUID;
            $this->components[$objectUUID]->setState('created');
            
            if(isset($this->components[$objectUUID]->events['aftercreate']))
                $this->components[$objectUUID]->events['aftercreate']->fireEvent($this->components);
        }
    }


    public function destroy(ObjectCore $object){
        $className = get_class($object);
        if($className === 'Component'){
            $objectUUID = $object->getUUID();
            
            if(isset($this->components[$objectUUID]->events['beforedestory']))
                $this->components[$objectUUID]->events['beforedestory']->fireEvent($this->components[$objectUUID]);
            $this->components[$objectUUID]->actualState = 'destroyed';
            $this->components[$objectUUID]->statesPassed['destroyed'] = true;
            $this->components[$objectUUID]->uuid = null;
            $this->components[$objectUUID]->name = null;
            $this->components[$objectUUID]->image = [];
            if(isset($this->components[$objectUUID]->events['afterdestroy']))
                $this->components[$objectUUID]->events['afterdestroy']->fireEvent($this->components[$objectUUID]);
        }
        unset($this->components[$objectUUID]);
        //remove this from cache
    }

    public function updateAttribute(Object $object, $attribute, $val = null){
        $className = get_class($object);
        if($className === 'Component'){
            $objectUUID = $object->getUUID();
            $this->objLoop[$objectUUID]++;
            $this->components[$objectUUID]->updateAttribute($attribute, $val);
        }
    }

    public function getAllComponentsToCache(Array $components = []){
        $list = [];
        if(count($components)){
            foreach($components as $component){
                $list[$component->getName] = $component->getBaseObject();
            }
        }elseif(count($this->components)){
            foreach($this->components as $uuid => $component){
                $list[$uuid] = $component->getBaseObject();
            }
        }
        return $list;
    }

}