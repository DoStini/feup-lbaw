<?php

return [
    'unexpected' => [
        'code' => 500,
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
        'recover_failed' => [
            'code' => 404,
            'message' => 'Recover account link expired or invalid'
        ],
        'blocked' => [
            'code' => 403,
            'message' => 'The user has been blocked'
        ]
    ],
    'fields' => [
        'code' => 422,
        'message' => 'Fields contain errors',
    ],
    'not_exist_product' => [
        'code' => 422,
        'message' => 'Product does not exist'
    ],
    'not_enough_products' => [
        'code' => 422,
        'message' => 'A product needs at least one photo'
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
    'wishlist' => [
        'already_exists' => [
            'code' => 409,
            'message' => 'Product already in wishlist'
        ],
        'not_exists' => [
            'code' => 422,
            'message' => 'Product not in wishlist'
        ],
    ],
    'address' => [
        'not_in_user' => [
            'code' => 422,
            'message' => 'Address does not belong to the user'
        ],
    ],
    'block' => [
        'already_blocked' => [
            'code' => 422,
            'message' => 'User is already blocked',
        ],
        'not_blocked' => [
            'code' => 422,
            'message' => 'User is not blocked',
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
        ],
        'invalid' => [
            'code' => 422,
            'message' => "Coupon can't be applied to this order"
        ],

    ],
    'constants' => [
        'auth' => [
            'recover_link_expire' => 5,
        ]
    ],
    'order' => [
        'terminal_state' => [
            'code' => 422,
            'message' => 'Order state cannot be updated any further'
        ]
    ],
    'ordered_canceled' => [
        'code' => 422,
        'message' => 'Order was canceled',
    ],
];
