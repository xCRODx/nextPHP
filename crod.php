<?php
@session_start();
$_SESSION = [];
@session_destroy();
require_once 'vendor/autoload.php';
//require_once COMMON_PATH.'system/core/app.core.php';
//require_once CONFIG_PATH.'config.php';
use Core\App;
use Core\Interpreter;
use Includes\Entity;
use Classes\QueryBuilder;
use Core\Cache;

$app = new App();

/*
$db = new DB();
/* 
for($i = 0; $i < 100; $i++){
    $a = $db->query("SELECT * FROM login");
}*/
//p(md5($_SERVER['HTTP_USER_AGENT'].$_SERVER['LOCAL_ADDR'].$_SERVER['LOCAL_PORT'].$_SERVER['REMOTE_ADDR']));


$component = [
    'name' => 'Teste',
    'events' => [
        'oncreated' => '',
        'ondestroyed' => ''
    ],
    'properties' => [
        'state' => ''
    ]
];

p(['session' => $_SESSION, 'cache' => Cache::get('base_components')]);
//p(get_included_files());

//Action::execute('testeAction', []);
/*

// p(['env -> apKey' => env('apiKey')]);
$join = [
    'table' => 'login',
    //'alias' => 'p',
    'join_mode' => 'LEFT',
    'join_to_table' => 'u',
    'pk' => 'idusuario',
    'fk' => 'id',
    'conditions' => [

    ]
];
// p($app->QueryBuilder->insert('perfil', ['idusuario' => 4, 'data_nascimento' => '1995-06-14', 'telefone' => '11945594219']));
//p($app->QueryBuilder->table('usuario', 'u')->addJoins(['p' => $join, 'p2' => $join])->where('u.id', '=', 4)->get());
$query_buider = (new QueryBuilder())->table('login', 'u')->where('u.id', '=', 4);
//$query_buider->addJoin($join);
//p($app->QueryBuilder->joins,$app->QueryBuilder->joinsQuery);


$condition = [
    ['{{a}}', '=', '{{b}}'],
    ['{{c}}', '<', '{{d}}'],
    ['{{e}}', 'is', 'false'],
    [
        ['d', 'is not', 'true'],
        'or' => [[['{{c}}', '<=', '{{a}}'], ['{{d}}', '!=', '{{b}}']], 'or' => ['{{a}}', '>=', '{{g}}']]
    ]
];

$var_replace = [
    'a' => 'user.login',//['val' => 'ativo', 'type' => 'integer', 'return_type'],
    'c' => 'nome_usuario',
    'd' => 'user.pass',
    'f' => 'senha_teste',
    'b' => 'true'
];


$condition = Interpreter::replaceVars($condition, $var_replace, '', '');
p($condition);

$c = Interpreter::getConditions($condition);
echo $print ? "String gerada: [---" . $c."---]" : false;
echo br;

//$entity = new Entity();
//$entity->addJoin($join);
p($query_buider->get());

p($query_buider->getCount()) ;

p($query_buider->PaginationInfo());
/*
$cache = [
    'usuario' => 'A',
    'senha' => 'd98as4da65sda',
    'char' => 'Naruto',
    'hp' => rand(1,50000)
];

$lastId = $app->db->insert('usuario',[
    'login' => 'admin',
    'senha' => 'pass',
    'email' => 'teste@cleydson'
]);
p('last ID', $lastId);
/*
//Globals::set('last_query', $app->db->getSQL());
p('last ID', $lastId);
p('last query', Globals::get('last_query'));
*/

//p(['bench' => bench()]);


p(['bench' => bench()]);

