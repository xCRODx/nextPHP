<?php 
namespace Core;

use Core\Event;
use Core\ObjectCore;

class Action {
    public $eventAction;
    function __construct($event){
        $this->eventAction = $event;
    }
    public function fire(Event &$event, ObjectCore &$objectBase){
        $vars = $event->getLocalVar();
        $params = $event->params;

    }

}