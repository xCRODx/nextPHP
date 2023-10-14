<?php
namespace Interfaces;
use Includes\PluginInclude;

interface PluginInterface {
    static function getInfoData(): PluginInclude;
}

