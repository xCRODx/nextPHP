<?php
namespace Includes;
use Classes\QueryBuilder;

class Entity extends QueryBuilder{
    public static $entities = [];
    public static $use_cache = false;

    public function __construct($cfg = []){
        //in production mode, the cache makes performance better
        self::$use_cache = env('environment') == 'development' ? false : true;
        self::$use_cache = isset($cfg['use_cache']) ? $cfg['use_cache'] : self::$use_cache;
    }

    public static function getEntity($e){
        $e = self::$entities[$e];
        return [
            'table' => isset($e['table']) ? $e['table'] :[],
            'joins' => isset($e['joins']) ? $e['joins'] :[],
            'fields' => isset($e['fields']) ? $e['fields'] :[],
            'filters' => isset($e['filters']) ? $e['filters'] :[],
            'create_fields' => isset($e['insert_fields']) ? $e['insert_fields'] :[], //fields to include a new register
            'read_fields' => isset($e['list_fields']) ? $e['listt_fields'] :[],     //return such fields in a select
            'update_fields' => isset($e['update_fields']) ? $e['update_fields'] :[], //based on primary key values its update a register
            'delete_fields' => isset($e['delete_fields']) ? $e['delete_fields'] :[], // delete a register based on the filter
        ];
    }
}