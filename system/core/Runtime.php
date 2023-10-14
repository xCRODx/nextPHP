<?php
namespace Core;
use Core\Core;
/**
 * Controll the flush of aplication.
 * It says when the app started and says when will finish the aplication.
 */
class Runtime {

    static $actualState = 1;
    static $lastState = false;


    static function setState($i){
        if(State::$runtime[($i+1)])
            self::$actualState = $i;
        else
            self::$actualState = count(State::$runtime);
    }

    public static function start(){
        self::$actualState = 1;
    }

    static function nextState(){
        if(!((count(State::$runtime)) > self::$actualState))
            self::$actualState++;
    }

    static function getState(){
        return self::$actualState;
    }

    static function getStateName(){
        return State::$runtime[(self::$actualState+1)];
    }

    static function isLastState(){
        return self::$actualState >= (count(State::$runtime));
    }

}