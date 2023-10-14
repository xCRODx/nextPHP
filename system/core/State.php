<?php 
namespace Core;
class State {
    static $runtime = [
        'load',
        'running',
        'final'
    ];

    static $runtimePassed = [];

    static $components = [
        'initial' => 'initial',
        'created' => 'created',
        'destroyed' => 'destroyed'
    ];

    static $event = [
        'initial',
        'dispatched'
    ];

}
