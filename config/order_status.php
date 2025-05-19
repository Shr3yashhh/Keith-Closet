<?php

return [

    'flow' => [
        'pending',
        'processing',
        'delivered',
        'completed',
        'cancelled',
    ],

    'statuses' => [
        'pending' => [
            'label' => 'Pending',
            'can_edit' => true,
            'color' => 'warning', // Yellow
        ],
        'processing' => [
            'label' => 'Processing',
            'can_edit' => false,
            'color' => 'info', // Light Blue
        ],
        'delivered' => [
            'label' => 'Delivered',
            'can_edit' => false,
            'color' => 'primary', // Blue
        ],
        'completed' => [
            'label' => 'Completed',
            'can_edit' => false,
            'color' => 'success', // Green
        ],
        'cancelled' => [
            'label' => 'Cancelled',
            'can_edit' => false,
            'color' => 'danger', // Red
        ],
    ],

];
