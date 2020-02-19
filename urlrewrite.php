<?php
$arUrlRewrite=array (
  0 => 
  array (
    'CONDITION' => '#^\\/?\\/mobileapp/jn\\/(.*)\\/.*#',
    'RULE' => 'componentName=$1',
    'ID' => NULL,
    'PATH' => '/bitrix/services/mobileapp/jn.php',
    'SORT' => 100,
  ),
 1 => 
  array (
    'CONDITION' => '#^/personal/update-key/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/personal/update_key.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/personal/error-key/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/personal/error_key.php',
    'SORT' => 100,
  ),
  39 => 
  array (
    'CONDITION' => '#^/personal/requests/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/personal/requests/index.php',
    'SORT' => 100,
  ),
  38 => 
  array (
    'CONDITION' => '#^/personal/request/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/personal/requests/detail.php',
    'SORT' => 100,
  ),
  41 => 
  array (
    'CONDITION' => '#^/personal/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/personal/index.php',
    'SORT' => 100,
  ),
  34 => 
  array (
    'CONDITION' => '#^/marketplace/local/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.marketplace.localapp',
    'PATH' => '/marketplace/local/index.php',
    'SORT' => 100,
  ),
  37 => 
  array (
    'CONDITION' => '#^/marketplace/hook/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.hook',
    'PATH' => '/marketplace/hook/index.php',
    'SORT' => 100,
  ),
  40=>array(
				"CONDITION" => "#^/marketplace/configuration/#",
				"RULE" => "",
				"ID" => "bitrix:rest.configuration",
				"PATH" => "/marketplace/configuration/index.php",
			),
  36 => 
  array (
    'CONDITION' => '#^/marketplace/app/#',
    'RULE' => '',
    'ID' => 'bitrix:app.layout',
    'PATH' => '/marketplace/app/index.php',
    'SORT' => 100,
  ),
  35 => 
  array (
    'CONDITION' => '#^/marketplace/#',
    'RULE' => '',
    'ID' => 'bitrix:rest.marketplace',
    'PATH' => '/marketplace/index.php',
    'SORT' => 100,
  ),
  5 => 
  array (
    'CONDITION' => '#^/api/v2/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/api/v2/index.php',
    'SORT' => 100,
  ),
    10 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
);
