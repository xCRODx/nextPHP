<?php 
namespace Drivers;
use Core\Cache;
use Core\Session;
use Core\Component;

class ComponentDriver{
    public $base_components = [];
    public $components = [];

    function __construct(){
        $components = Cache::get('components') ?: [];
        if(count($components)){
            foreach($components as $uuid => $component){
                $this->components[$uuid] = new Component($component);
                
                if($this->components[$uuid]->getState() == 'destroyed'){
                    unset($this->components[$uuid]);
                }else{
                    if($uuid && !$this->components[$uuid]->getUUID())
                        $this->components[$uuid]->uuid = $uuid;
                }
            }
        }

        //get components from cache and instantiate it
        $base_components = Session::get('base_components') ?: [];
        if(count($base_components))
            foreach($base_components as $name => $component)
                $this->base_components[$name] = new Component($component);
        
        if(env('environment') == 'development' || !count($this->base_components))
            $this->getAllAppComponents();
        
    }

    public function getAll(){
        return ['components' => $this->components, 'base_components' => $this->base_components];
    }

    private function getAllAppComponents(){
        $files = getAllFiles(COMPONENTS_APP_PATH, [COMPONENTS_EXTENSION]);
        foreach($files as $file_dir => $fname){
            if(!is_file($file_dir))
                continue;
            
            $component = file_get_contents($file_dir);
            $component = json_decode($component, true);
            $this->base_components[pathinfo($file_dir, PATHINFO_DIRNAME).'/'.pathinfo($file_dir, PATHINFO_FILENAME)] = new Component($component);
        }
        return $this->base_components;
        
    }

    function getComponentsCached(){

    }

}
