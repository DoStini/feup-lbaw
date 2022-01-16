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
    ],
    'fields' => [
        'code' => 422,
        'message' => 'Fields contain errors',
    ],
    'not_exist_product' => [
        'code' => 422,
        'message' => 'Product does not exist'
    ],
    'cart' => [
        'already_exists' => [
            'code' => 409,
            'message' => 'Product already in cart'
        ],
        'not_exists' => [
            'code' => 422,
            'message' => 'Product not in cart'
        ],
        'stock' => [
            'code' => 422,
            'message' => 'Product does not have enough stock'
        ],
    ],
    'address' => [
        'not_in_user' => [
            'code' => 422,
            'message' => 'Address does not belong to the user'
        ]
    ],
    'coupon' => [
        'not_active' => [
            'code' => 422,
            'message' => 'Coupon is not active'
        ],
        'active' => [
            'code' => 422,
            'message' => 'Coupon is already active'
        ]
    ]
];
