<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Context;

$APPLICATION->SetTitle("Update key");

$context = Context::getCurrent();
$request = $context->getRequest();

$accessKey = $request->get('key') ?? ($_SESSION['access_key'] ?? null);
$requestId = $request->get('request_id') ?: '';

//$supplierId = \Zkr\Helper::checkAccess(false);
?>

<?php //if ($supplierId) { ?>

<? $APPLICATION->IncludeComponent(
    "zkr:empty",
    "update_key",
    [
        "ACCESS_KEY" => $accessKey,
        //            "ELEMENT_ID" => $supplierId,
        "REQUEST_ID" => $requestId,
        "CACHE_TIME" => "3600",
        "CACHE_TYPE" => "A"
    ]
); ?>

<?php //} ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
