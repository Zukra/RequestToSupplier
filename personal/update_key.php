<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Context;

$APPLICATION->SetTitle("Update key");

$context = Context::getCurrent();
$request = $context->getRequest();

$accessKey = $request->get('key') ?? $_SESSION['access_key'];
$requestId = $request->get('request_id') ?: '';

$elementId = \Zkr\Helper::checkAccess(false);

/*$_SESSION['access_key'] = $accessKey;
if ($accessKey) {
    $supplier = \Zkr\Helper::getSupplierByAccessKey($accessKey);

    if (! $supplier) {
        echo 'Используемый ключ недействителен или неверен, используйте новый ключ';
    }

    $elementId = $supplier ? $supplier->getId() : null;
} else {
    LocalRedirect('/');
} */ ?>

<?php if ($elementId) { ?>
    <? $APPLICATION->IncludeComponent(
        "zkr:empty",
        "update_key",
        [
            "ACCESS_KEY" => $accessKey,
            "ELEMENT_ID" => $elementId,
            "REQUEST_ID" => $requestId,
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A"
        ]
    ); ?>
<?php } ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
