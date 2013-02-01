<?php
return [
    'services' => [
        'ValuCliAuth' => [
            'name' => 'Auth',
            'class' => 'ValuCli\\Service\\Auth',
            'priority' => -50000 // Make sure this gets executed last
        ],
    ],
];
