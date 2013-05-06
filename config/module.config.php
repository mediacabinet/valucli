<?php
return [
    'valu_so' => [
        'services' => [
            'ValuCliAuth' => [
                'name' => 'Auth',
                'class' => 'ValuCli\\Service\\AuthService',
                'priority' => -50000 // Make sure this gets executed last
            ],
        ],
    ]
];
