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

        /**
         * first it searches if the action is a reserved word.
         * then search the system components and plugins. if exists then executes the actions with parameters
         * then search in user components.
         * then search if its an php ntive function and execute passing all parameters
         * check the return type of the function and format the return to be exactly what espects. always check first [0] then [1], so throw exception
         * 
         */

    }

    public static function getAllActions($plugin = false) : array{
        $actions = [];
        if($plugin && isset(Globals::$actionList[$plugin]))
         $actions = Globals::$actionList[$plugin];
        return $actions;
    }

}