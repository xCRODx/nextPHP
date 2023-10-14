<?php 
namespace Classes;

class Response {
    public $header;
    public $status;
    public $body;

    function __construct(Array $params = array()) {
        //Set the headers
        if(isset($params['header']) && count($params['header'])){
            foreach($params['header'] as $header){
                header($header);
            }
        }else{
            foreach(getConfig('base_header_params') as $header){
                header($header);
            }
        }
        
    }

    public function setBody($body) {
        $this->body = $body;
    }

    

}