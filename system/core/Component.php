<?php 
namespace Core;
use Core\ObjectCore;

//Components can have all type of events inside. Its keeped alive after runtime execution.
class Component extends ObjectCore{
    function __construct($component = []){
        parent::__construct($component);
    }
    
}