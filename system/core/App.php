<?php

namespace Core;

use Core\Core;

use Exception;
use Exceptions\Component_Exception;
use Exceptions\Event_Exception;
use Exceptions\DB_Exception;

class App extends Core{
    private $errors = [];
    public static $module = 'AppCore';
    public static $userId = false;
    static $USER_ID;

    function __construct($cfg = []){

        try{
            if($this->getErrors())
                $this->view($this->getErrors());
            
            parent::__construct($cfg);

        }catch(Exception $e){

        }catch(Event_Exception $e){

        }catch(Component_Exception $e){
            //($e->getMessage);
        }catch(DB_Exception $e){
            Globals::$classes['db']->rollback();
        }finally{
            $this->finish();
        }

    }

    public function getErrors(){
        return isset($this->errors) ? $this->errors : [];
    }

    public function view($content){
        
    }

    public static function dieWithBackTrace($string = []){
        // p(print_r(Core::backTrace(), true));
        // p($string);
        // die;
    }

    /**
     * Unique Id of user client or general ID NULL
     */
    public static function getUserId(){
        return self::$USER_ID ?: (env('cache_by_browser') ? (md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['LOCAL_ADDR'].$_SERVER['LOCAL_PORT'].$_SERVER['REMOTE_ADDR'])) : 'NULL'); 
    }
}