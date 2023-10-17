<?php
use Core\Globals;

Globals::$actionList = [
    'system' => [
        'Example' => [
            'description' => t(),
            'action' => [
                [ // First action to execute
                    'type*' => '',//condition, call function, loop
                    'parameters' => ['parameter1', '...'],//if conditional, use Interpreter::traitCondition to verify
                    'passed' => ['execute other action by name'],//only conditional
                    'else' => ['execute other action by name']//only conditional
                ],
                [ //loop exemple
                    'action_type*' => 'foreach',
                    'parameters' => ['{{:var_to_verify}}', '{{:temp_var_name_key}}', '{{:temp_var_name_value}}'],
                    'actions' => [
                        [ //Firat action to execute
                            'type' => 'call',
                            'parameters' => ['{{:function|event}}', 'parameter1', 'parameter2', 'parameter3', '...'],
                            'return' => ['{{:var_name}}']//Its set the value of an variable of any context
                        ]
                    ] //actions to execute
                ]
            ],
            'parameters' => [
                ['varName', 'default(optional)'],
                ['varName2', 'default(optional)']
            ],
            'return' => [
                'data' => ['array', 'null'],
            ]
        ]
    ]
];