<?php
use Core\Context;
function db_connect($engine = 'mysql'){
    
}

function getConfig($key = null){
    global $config;
    $config = $config ?: getConfigJSON();
    $config = array_change_key_case($config, CASE_LOWER);
    $key = strtolower($key);
    return (is_null($key) ? $config : (isset($config[$key]) ? $config[$key] : false ) );
}

function getConfigJSON(){
    $userAppConfigFile = APP_PATH.'config/config.json';
    $userAppConfigJSON = '';

    if(file_exists($userAppConfigFile))
        $userAppConfigJSON = file_get_contents($userAppConfigFile);

    $config = json_decode($userAppConfigJSON, true);
    return $config;
}

function env($key){
    global $_ENV;
    $env = count($_ENV) ? $_ENV : getEnvFile();
    $key = strtolower($key);
    if(isset($env[$key]))
        return (strtolower($env[$key]) == 'true' ? true : (strtolower($env[$key]) == 'false' ? false : $env[$key]) );
    return false;
}

function getEnvFile(){
    global $_ENV;
    $envAppFile = APP_PATH.'config/.env';
    $envSystemFile = COMMON_PATH.'.env';
    $envApp = $envSystem = '';

    if(file_exists($envSystemFile))
        $envSystem = file_get_contents($envSystemFile);
    if(file_exists($envAppFile))
        $envApp = file_get_contents($envAppFile);

    $_ENV = array_merge(envInterpreter($envSystem), envInterpreter($envApp));
    return $_ENV ?: [];
}

function envInterpreter($data){
    $e = explode("\r\n", $data);
    $env = [];
    foreach($e as $config){
        $config = trim($config);
        if(stripos($config, '#') === 0 || stripos($config, ';') === 0 || stripos($config, '//') === 0 || !$config)
            continue;

        $config = explode('=', $config);
        $k = trim($config[0]);
        unset($config[0]);
        $v = trim(implode('',$config));
        $env[$k] = $v;
    }
    return array_change_key_case($env, CASE_LOWER);
}

function response($res){
    if(is_array($res || Context::get('api')))
        echo arrayToJson($res);
    else
        echo $res;
}

function ssession($k, $v = null){
    if(session_status() !== PHP_SESSION_ACTIVE)
        response(__("Sessão não foi inciada corretamente"));
    if(is_null($v))
        return isset($_SESSION[$k]) ? $_SESSION[$k] : false;
    else
        $_SESSION[$k] = $v;
}

function arrayToJson($array, $pretty_print = true){
    return json_encode($array, $pretty_print ? JSON_PRETTY_PRINT : null);
}

function jsonToArray($json){
    return json_decode($json, true);
}

function __($str){
    return $str;
}

function t(...$params){
    if(count($params) == 1)
        return __($params[0]);
    elseif(count($params) > 1){
        $params[0] = __($params[0]);
        return call_user_func_array('sprintf', $params);
    }
    return '';
}

function p(...$args){
    echo '<pre>';
    if(env('backtrace_in_print') && DEBUG_MODE == true)
        array_unshift($args, [getBackTrace()]);
    print_r($args);
    echo '</pre>';
}

function pd(...$args){
    echo '<pre>';
    if(env('backtrace_in_print') && DEBUG_MODE == true)
        array_unshift($args, [getBackTrace()]);
    print_r($args);
    echo '</pre>';
    die;
}

function bench(){
    return microtime(true) - BENCH_START;
}

function getBackTrace(){
    $backtrace = debug_backtrace();
    $bt = '';
    foreach ($backtrace as $index => $trace) {
        $bt .= "Chamada de função $index: " . $trace['function'] . "\n";
        $bt .= "Arquivo: " . $trace['file'] . ", linha: " . $trace['line'] . "\n\n";
    }
    return $bt;
}

function wlog($content, $file = 'common', $backtrace = true){
    $config = getConfig();
    $file = LOG_PATH . $file . ($config['log_extension'] ? ".{$config['log_extension']}" : '');

    $content = ($backtrace ? getBackTrace() : '').$content;
    file_put_contents($file, print_r($content, true));
}

function array_change_key_case_recursive($arr, $case = CASE_LOWER)
{
    return array_map(function($item) use($case) {
        if(is_array($item))
            $item = array_change_key_case_recursive($item, $case);
        return $item;
    },array_change_key_case($arr, $case));
}

function reserved($word){
    return array_filter(RESERVED_SQL_WORDS, function($k){ if(isset(RESERVED_SQL_WORDS[$k])) return true; });
}

function lower($var){
    return strtolower($var);
}
function upper($var){
    return strtoupper($var);
}

function getAllFiles($folder, $extensions = []) {
    $allFiles = [];
    $extensions = is_array($extensions) ? $extensions : [$extensions];
    if(is_dir($folder)){
        foreach (scandir($folder) as $f) {
            
            if($f == '.' || $f == '..') continue;
            $file = ($folder.$f);
            if(is_file($file)){
                $allFiles[$file] = $f;
            }
        }
    }

    return $allFiles;
}

function classToArray($class){
    return json_decode(json_encode($class), true);
}

function clearErrorLog(){

}

function errorLog($message, $class, $type = 'error'){
    //create a JSON file that saves all errors reported in exceptions
}