<?php
namespace Exceptions;
use Core\Event;

class Event_Exception extends \Exception{
    function __construct(Event $component, $vars = [], $target = false) {

    }
}