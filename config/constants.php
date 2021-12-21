<?php

return [
    'unexpected' => [
        'code' => 401,
        'message' => 'Unexpected Error'
    ],
    'authentication' => [
        'auth' => [
            'code' => 401,
            'message' => 'The user must be logged in'
        ],
        'cant_be_admin' => [
            'code' => 401,
            'message' => 'The user can\'t be an admin'
        ],
    ]

];
