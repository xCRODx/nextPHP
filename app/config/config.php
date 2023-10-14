<?php
global $config;
$config = [

    'aplication_language' => 'pt-br',//translate -> en, es, fr, it

    'events_laguage' => 'json',//json,yaml,csv,xml
    'components_language' => 'json',//json,yaml,csv,xml
    'entities_language' => 'json',//json,yaml,csv,xml

    //CACHE
    'cache_engine' => 'serialize',//json, serialize
    'cache_extension' => '',
    'cache_duration' => 'day',

    //LOG
    'log_extension' => 'txt',

    //COMMON
    'SYSTEM_VAR_STRUCTURE' => "{{:var}}",
    
    'default_date_format' => 'd/m/Y',
    'default_datetime_format' => 'd/m/Y h:i:s',
];
//json request 
//site->module->function
//return a object array from a ajax call
