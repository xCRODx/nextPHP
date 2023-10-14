<?php 
namespace Includes;

class PluginInclude{
    public $name, $actionList, $version, $info;

    function __construct($name, $actionList, $version, $info = []){
        $this->name = $name;
        $this->actionList = $actionList;
        $this->version = $version;
        $this->info = $info;
    }

    public function getName() {
        return $this->name;
    }

    public function getActionList() {
        return $this->actionList;
    }

    public function getVersion() {
        return $this->version;
    }
}