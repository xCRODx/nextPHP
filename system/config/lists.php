<?php
use Core\Globals;

Globals::$actionList = [
    'system' => [
        'Example' => [
            'description' => t(),
            'action' => [
                [
                    'action_type*' => '',//condition, call function, 
                    'action_parameters' => ['parameter1', ''],//if conditional, use Interpreter::traitCondition to verify
                    'passed' => ['execute other action by name'],//only conditional
                    'else' => ['execute other action by name']//only conditional
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