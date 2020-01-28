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
  4 => 
  array (
    'CONDITION' => '#^/e-store/books/reviews/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/e-store/books/reviews/index.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/e-store/books/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/e-store/books/index.php',
    'SORT' => 100,
  ),
  2 => 
  array (
    'CONDITION' => '#^/content/news/#',
    'RULE' => '',
    'ID' => 'bitrix:news',
    'PATH' => '/content/news/index.php',
    'SORT' => 100,
  ),
  1 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
  34 =>
      array (
          "CONDITION" => "#^/marketplace/local/#",
          "RULE" => "",
          "ID" => "bitrix:rest.marketplace.localapp",
          "PATH" => "/marketplace/local/index.php",
          'SORT' => 100,
      ),
  36 =>
      array (
          "CONDITION" => "#^/marketplace/app/#",
          "RULE" => "",
          "ID" => "bitrix:app.layout",
          "PATH" => "/marketplace/app/index.php",
          'SORT' => 100,
      ),
  37 =>
      array (
          "CONDITION" => "#^/marketplace/hook/#",
          "RULE" => "",
          "ID" => "bitrix:rest.hook",
          "PATH" => "/marketplace/hook/index.php",
          'SORT' => 100,
      ),
  35 =>
    array (
        "CONDITION" => "#^/marketplace/#",
        "RULE" => "",
        "ID" => "bitrix:rest.marketplace",
        "PATH" => "/marketplace/index.php",
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
);
