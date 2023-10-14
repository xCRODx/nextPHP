<?php
define('BENCH_START', microtime(true));
define('DEBUG_MODE', false);

define('COMMON_PATH', dirname(dirname(__DIR__)).'/');
define('SELF_FILE', $_SERVER['PHP_SELF']);
define('SYSTEM_PATH', COMMON_PATH.'system/');
define('APP_PATH', COMMON_PATH.'app/');

define('CONFIG_PATH', APP_PATH.'config/');

define('CACHE_PATH', COMMON_PATH.'writable/cache/');
define('CACHE_SYSTEM_PATH', COMMON_PATH.'writable/sytem_cache/');

define('LOG_PATH', COMMON_PATH.'writable/logs/');

define('INTERFACE_PATH', SYSTEM_PATH.'interfaces/');
define('INTERFACE_APP_PATH', APP_PATH.'interfaces/');

define('CLASS_PATH', SYSTEM_PATH.'class/');
define('CLASS_APP_PATH', APP_PATH.'class/');

define('INCLUDES_PATH', SYSTEM_PATH.'inc/');
define('INCLUDES_APP_PATH', APP_PATH.'inc/');//@todo remove

define('CORE_PATH', SYSTEM_PATH.'core/');
define('CORE_APP_PATH', APP_PATH.'core/');//@todo remove

define('FUNCS_PATH', SYSTEM_PATH.'funcs/');
define('FUNCS_APP_PATH', APP_PATH.'funcs/');//@todo remove

define('ENTITIES_PATH', SYSTEM_PATH.'ent/');//@todo remove
define('ENTITIES_APP_PATH', APP_PATH.'ent/');//@todo remove
define('ENTITIES_EXTENSION', '.entity');

define('ACTIONS_PATH', SYSTEM_PATH.'actions/');//@todo remove
define('ACTIONS_APP_PATH', APP_PATH.'actions/');//@todo remove
define('ACTIONS_EXTENSION', '.action');

define('EVENTS_PATH', SYSTEM_PATH.'events/');
define('EVENTS_APP_PATH', APP_PATH.'events/');
define('EVENTS_EXTENSION', '.event');

define('COMPONENTS_PATH', SYSTEM_PATH.'components/');
define('COMPONENTS_APP_PATH', APP_PATH.'components/');
define('COMPONENTS_EXTENSION', '.component');

define('CONTROLLERS_PATH', APP_PATH.'controllers/');//@todo remove
define('MODELS_PATH', APP_PATH.'models/');//@todo remove
define('VIEWS_PATH', APP_PATH.'views/');//@todo remove

require_once 'const.php';