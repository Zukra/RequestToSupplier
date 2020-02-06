<?php
$arUrlRewrite = [
    0  => [
        'CONDITION' => '#^/personal/error-key/(/?)([^/]*)#',
        'RULE'      => '',
        'ID'        => null,
        'PATH'      => '/personal/error_key.php',
        'SORT'      => 100,
    ],
    1  =>
        [
            'CONDITION' => '#^/personal/update-key/(/?)([^/]*)#',
            'RULE'      => '',
            'ID'        => null,
            'PATH'      => '/personal/update_key.php',
            'SORT'      => 100,
        ],
    39 =>
        [
            'CONDITION' => '#^/personal/requests/(/?)([^/]*)#',
            'RULE'      => '',
            'ID'        => null,
            'PATH'      => '/personal/requests/index.php',
            'SORT'      => 100,
        ],
    38 =>
        [
            'CONDITION' => '#^/personal/request/(/?)([^/]*)#',
            'RULE'      => '',
            'ID'        => null,
            'PATH'      => '/personal/requests/detail.php',
            'SORT'      => 100,
        ],
    41 =>
        [
            'CONDITION' => '#^/personal/(/?)([^/]*)#',
            'RULE'      => '',
            'ID'        => null,
            'PATH'      => '/personal/index.php',
            'SORT'      => 100,
        ],
    34 =>
        [
            'CONDITION' => '#^/marketplace/local/#',
            'RULE'      => '',
            'ID'        => 'bitrix:rest.marketplace.localapp',
            'PATH'      => '/marketplace/local/index.php',
            'SORT'      => 100,
        ],
    37 =>
        [
            'CONDITION' => '#^/marketplace/hook/#',
            'RULE'      => '',
            'ID'        => 'bitrix:rest.hook',
            'PATH'      => '/marketplace/hook/index.php',
            'SORT'      => 100,
        ],
    36 =>
        [
            'CONDITION' => '#^/marketplace/app/#',
            'RULE'      => '',
            'ID'        => 'bitrix:app.layout',
            'PATH'      => '/marketplace/app/index.php',
            'SORT'      => 100,
        ],
    35 =>
        [
            'CONDITION' => '#^/marketplace/#',
            'RULE'      => '',
            'ID'        => 'bitrix:rest.marketplace',
            'PATH'      => '/marketplace/index.php',
            'SORT'      => 100,
        ],
    5  =>
        [
            'CONDITION' => '#^/api/v2/#',
            'RULE'      => '',
            'ID'        => null,
            'PATH'      => '/api/v2/index.php',
            'SORT'      => 100,
        ],
    10 =>
        [
            'CONDITION' => '#^/rest/#',
            'RULE'      => '',
            'ID'        => null,
            'PATH'      => '/bitrix/services/rest/index.php',
            'SORT'      => 100,
        ],
];
