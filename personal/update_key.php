<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Context;

$APPLICATION->SetTitle("Update key");

$context = Context::getCurrent();
$request = $context->getRequest();

$accessKey = $request->get('key') ?? $_SESSION['access_key'];
$_SESSION['access_key'] = $accessKey;

if ($accessKey) {
    \Bitrix\Main\Loader::includeModule('iblock');
    /** @var \Bitrix\Iblock\Elements\EO_ElementSupplier $supplier */
    $supplier = \Bitrix\Iblock\Elements\ElementSupplierTable::query()
        ->setSelect(['ID', 'EXPIRY_DATE'])
        ->setFilter(['KEY.VALUE' => $accessKey])
        ->fetchObject();

    if (! $supplier) {
        echo 'Используемый ключ недействителен или неверен, используйте новый ключ';
    }

    $elementId = $supplier ? $supplier->getId() : null;
} else {
    LocalRedirect('/');
} ?>

<?php if ($elementId) { ?>
    <? $APPLICATION->IncludeComponent(
        "zkr:empty",
        "update_key",
        [
            "ACCESS_KEY" => $accessKey,
            "ELEMENT_ID" => $elementId,
            "REQUEST_ID" => $request->get('request_id') ?: '',
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A"
        ]
    ); ?>
<?php } ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
