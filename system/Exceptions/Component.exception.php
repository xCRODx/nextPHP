<?php
namespace Exceptions;
use Core\Component;
class Component_Exception extends \Exception{
    function __construct(Component $component) {

    }
}