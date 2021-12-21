<?php

return [
    'unexpected' => [
        'code' => 401,
        'message' => 'Unexpected Error'
    ],
    'authentication' => [
        'auth' => [
            'code' => 403,
            'message' => 'The user must be logged in'
        ],
        'must_be_shopper' => [
            'code' => 403,
            'message' => 'The user is not a shopper'
        ],
    ]

];
