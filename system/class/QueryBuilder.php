<?php
namespace Classes;

use Core\Interpreter;
use Classes\DB;
use Engines\Postgres;
use Exceptions\DB_Exception;

class QueryBuilder extends DB{
    static $instance;
    public $table = '';
    public $groupBy;
    public $prepare;
    public $groupFields;
    public $table_data;
    public $query = '';
    public $where = '';
    public $from = '';
    public $mainTable = '';
    public $joins = [];
    public $joinsQuery = [];
    public $fields = [];
    public $fieldsQuery = [];
    public $distinct = false;
    public $prepare_fields = [];
    public $only_query_buider = false;
    private $_db;

    function __construct(Array $config = []){
        if(!$config['only_query_builder'])
            parent::__construct();
        else
            $this->only_query_buider = true;
    }

    public function useEntity($entity = []){
        //extrai os dados de um objeto entidade e permite realizar a querys de acordo com as confifuracoes
        $this->resetQuery();
        $e = [];

    }

    public function table($tableName, $alias = false){
        $this->resetQuery();
        $alias = $alias ?: $tableName;
        $this->table_data = ['table' => $tableName, 'alias' => 'alias'];
        $tb = "{$tableName}" . ($tableName !== $alias ? " AS $alias" : '');
        $this->table = $tb;
        $this->from .= " FROM {$tb}";
        $this->mainTable = $tableName;
        return $this;
    }

    public function getMainTable(){
        return $this->mainTable;
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function addJoin(Array $join = []){
        
        $join = self::parse($join);
        $conditions = false;

        if(isset($join['conditions'])){
            $conditions = $join['conditions'];
        }
        
        $join = array_change_key_case_recursive($join, CASE_LOWER);
        
        if($conditions)
            $join['conditions'] = $conditions;
        
        $join_mode = isset($join['join_mode']) && $join['join_mode'] ? $join['join_mode'] : 'LEFT' ;
        $alias = isset($join['alias']) && $join['alias'] ? $join['alias'] : $join['table'];
        $and = '';
        $join_table = '';
        
        if(isset($join['join_to_table'], $join['pk'], $join['fk'])){
            $and = 'AND ';
            $join_table = "{$alias}.{$join['pk']} = {$join['join_to_table']}.{$join['fk']}";
        }else{
            throw new DB_Exception(t('É preciso definir corretamente os parâmetros de join da tabela "%s"', $alias));
        }

        $conditionString = $conditions ? Interpreter::getConditions($conditions) : '';
        $conditionString = $conditionString ? "{$and}{$conditionString}": '';
        $conditionString = "{$join_table} {$conditionString}";
        
        
        $njTable = $join['table'] !== $alias ? " AS {$alias}" : '';
        $newJoin = " {$join_mode} JOIN {$join['table']}{$njTable} ON {$conditionString} ";

        $this->joins[$alias] = $join;
        $this->joinsQuery[$alias] = $newJoin;
        return $this;
    }

    public function addJoins(Array $joinList){
        foreach($joinList as $alias => $join){
            $join['alias'] = $alias;
            $this->addJoin($join);
        }
        return $this;
    }

    public function addField($field){
        self::parse($field);
        $table = isset($field['table']) ? $field['table'] : $this->getMainTable();
        $alias = isset($field['alias']) ? $field['alias'] : $field['field'];
        $query = isset($field['query']) ? $field['query'] : false;
        $group = isset($field['group']) && !$query ? $field['group'] : false;

        $type  = isset($field['type'])  ? $field['type']  : 'string';
        $fieldName = isset($field['field']) ? $field['field'] : $field['field'];
        $alias_q = isset($field['alias']) ? " AS {$alias}" : '';

        $query_field = ($query ? $query :"{$table}.{$fieldName}")."{$alias_q}";
        $this->groupFields[$alias] = $group;
        $this->fieldsQuery[$alias] = $query_field;
        $this->fields[$alias] = $field;
        return $this;
    }

    public function addFields($fields){
        if(!is_array($fields))
            return;
        foreach($fields as $k => $field){
            $field['alias'] = $k;
            $this->addField($field);
        }
        return $this;
    }

    public function addGroup($index){
        $this->groupBy[] = $index;
        return $this;
    }

    public function getQuery($fields, $additionalWhere = [], $groupBy = false){
    }

    public function resetQuery(){
        $this->table = '';
        $this->query = '';
        $this->where = '';
        $this->from = '';
        $this->mainTable = '';
        $this->joins = [];
        $this->joinsQuery = [];
        $this->fields = [];
        $this->fieldsQuery = [];
        $this->distinct = false;
        $this->prepare = [];
        $this->_db = DB::getInstance();
    }

    //Add erros to debug
    public function parse($join){
        return $join;
    }

}

//$query = new QueryBuilder(['table' => 'usuario']);
//$query->addJoin('table', 'alias', ['pk', 'fk']);
//$query->table('usuario')->inner([]);
//pd($query->get(['first' => 50, 'orderBy' => '50', 'groupBy' => '1,2,4,5,1', 'having' => 'count(*) < 50']));