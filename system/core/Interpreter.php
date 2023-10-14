<?php
namespace Core;
class Interpreter{
    
    public static function getConditions($conditions) {
        $conditionString = '';
        if(is_string($conditions)){
            return $conditions;
        }elseif(empty($conditions)){
            return;
        }
        $hasSubConditions = false;
        foreach($conditions as $con){
            if(is_array($con)){
                $hasSubConditions = true;
                break;
            }
        }
        if(!$hasSubConditions){
            $conditionString = self::traitCondition($conditions);
            return $conditionString;
        }
        
        $hasOr = array_filter(array_keys($conditions), function($k){if(lower($k) == 'or') return true; });
        
        foreach ($conditions as $k => $condition) {
            
            if (is_array($condition)) {
                $or = lower($k) == 'or' ? " OR " : '';
                $subCondition = self::getConditions($condition);
                $conditionString .= "$or($subCondition)";
                if($or) $or = '';
            }
            if(!$hasOr)
                $conditionString .= ' AND ';
            //unset($conditions[$k]);
        }
    
        return rtrim($conditionString, ' AND ');
    }
    
    public static function traitCondition($condition){
        $field = isset($condition[0]) ? $condition[0] : '';
        $operator = isset($condition[1]) ? $condition[1] : '';
        $value = isset($condition[2]) ? $condition[2] : '';
    
        if ($operator === 'is') {
            $conditionString = "$field = $value";
        } elseif ($operator === 'is not') {
            $conditionString = "$field != $value";
        } else {
            $conditionString = "$field $operator $value";
        }
        return $conditionString;
    }
    
    /**
     * 
     * $view - if this is literal the string returns as "'val'", else return "val" or pass to a personalized function
     */
    public static function replaceVars($vars, $var_replace, $view = false, $add_begin = '', $add_end = '') {
        $newVars = array();
        $return_one = false;
        if(!is_array($vars)){
            $return_one = true;
            $vars = [$vars];
        }
        foreach ($vars as $varName => $var) {
            if (is_array($var)) {
                $subVar = self::replaceVars($var, $var_replace);
                $newVars[$varName] = $subVar;
            } else {
                $newVar = $var;
    
                foreach ($var_replace as $key => $value) {
                    $i = explode(":var", getConfig('SYSTEM_VAR_STRUCTURE'));
                    $begin = isset($i[0]) ? $i[0] : getConfig('SYSTEM_VAR_STRUCTURE');
                    $end = isset($i[1]) ? $i[1] : getConfig('SYSTEM_VAR_STRUCTURE');
                    $newVar = $add_begin . str_replace($begin . $key . $end, $value, $newVar) . $add_end;
                }
    
                $newVars[$varName] = $newVar;
            }
        }
    
        return $return_one ? array_values($newVars)[0] : $newVars;
    }

    /**
     * [view] = format to make queries in DB
     */
    public function renderVars($var){
        if(is_array($var)){
            $returnType = strtolower(isset($var['type']) ? $var['type'] : 'string');
            $value = isset($var['value']) ? $var['value'] : '';
            switch($returnType){
                default:
                case 'string':
                    $value = "'{$value}'";
                    break;
                case 'integer':
                    break;
                case 'time':
                case 'date':
                case 'datetime':
                    $date_format = isset($var['date_format']) ? $var['date_format'] : getConfig("default_{$returnType}_format");
                    if(isset($var['view']) && $var['view'] == 'literal')
                        $date_format = date(getConfig("default_{$returnType}_format"), strtotime($value));
                    
                    //can execute an aplication action or a PHP function
                    if(isset($var['render'])){

                        $render = $var['render'];
                        $action = 'padrao';
                        if(is_array($var['render'])){
                            //valida se a action pode ser visualizada    
                            //valida os argumentos passados
                            //seta o valor da variave para o retorno da action/funcao PHP
                            $action = isset($render['action']) ? $render['action'] : $action;
                        }
                    }
                    break;
                case 'bool':
                case 'boolean':
                    $value = (strtolower($value) === 'true' or $value === true) ? true : false;
                    break;
            }
        }
        return $var;
    }
}