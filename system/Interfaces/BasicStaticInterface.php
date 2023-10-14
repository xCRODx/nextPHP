<?php
namespace Interfaces;
interface BasicStaticInterface{
    public static function set($k, $v);
    public static function get($k);
    public static function delete($k);
}