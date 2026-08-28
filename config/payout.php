<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payout Simulation Mode
    |--------------------------------------------------------------------------
    |
    | When true, payouts will be simulated without real transfer.
    | Set to false in production when using real payment gateway.
    |
    */
    'simulation_mode' => env('PAYOUT_SIMULATION_MODE', true),

    /*
    |--------------------------------------------------------------------------
    | Simulation Success Rate
    |--------------------------------------------------------------------------
    |
    | Percentage of successful transfers in simulation mode (0-100).
    | Only applicable when simulation_mode is true.
    |
    */
    'simulation_success_rate' => env('PAYOUT_SUCCESS_RATE', 60),

    /*
    |--------------------------------------------------------------------------
    | Minimum Payout Amount
    |--------------------------------------------------------------------------
    |
    | Minimum amount (in Rupiah) that can be withdrawn.
    |
    */
    'min_amount' => 50000,

    /*
    |--------------------------------------------------------------------------
    | Processing Delay
    |--------------------------------------------------------------------------
    |
    | Delay in seconds before processing the payout.
    | Used to simulate real gateway processing time.
    |
    */
    'processing_delay_seconds' => 10,

    /*
    |--------------------------------------------------------------------------
    | Payout Methods
    |--------------------------------------------------------------------------
    |
    | Available payout methods with their labels and icons.
    |
    */
    'methods' => [
        'dana' => [
            'label' => 'DANA',
            'icon' => '💰',
        ],
        'gopay' => [
            'label' => 'GoPay',
            'icon' => '🎯',
        ],
        'ovo' => [
            'label' => 'OVO',
            'icon' => '🟣',
        ],
        'shopeepay' => [
            'label' => 'ShopeePay',
            'icon' => '🛒',
        ],
        'bank' => [
            'label' => 'Transfer Bank',
            'icon' => '🏦',
        ],
    ],
];
