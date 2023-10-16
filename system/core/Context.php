<?php
/* Cleydson Rodrigues
 * Gives a context to the runtime core aplication
 * Should define context vars based on the actual event, component, action or database entity
 */
namespace Core;
use Interfaces\BasicStaticInterface;
class Context implements BasicStaticInterface {
    public static $context = [];
    public static function set($k, $v = true)   { self::$context["context.".$k] = $v; }
    public static function delete($k)           { self::$context["context.".$k] = false; }
    public static function get($k)              { return isset(self::$context["context.".$k]) ? self::$context["context.".$k] : null; }

}