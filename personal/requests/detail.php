<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Context;


$APPLICATION->SetTitle("Request detail");

$supplierId = \Zkr\Helper::checkAccess();
?>

<?php
$context = Context::getCurrent();
$request = $context->getRequest();

$requestId = $request->get('id') ?? null;
if ($requestId) { ?>
    <? $APPLICATION->IncludeComponent(
        "zkr:request.detail",
        "",
        [
            "ACTIVE_DATE_FORMAT"        => "d.m.Y",
            "ADD_ELEMENT_CHAIN"         => "Y",
            "ADD_SECTIONS_CHAIN"        => "N",
            "AJAX_MODE"                 => "N",
            "AJAX_OPTION_ADDITIONAL"    => "",
            "AJAX_OPTION_HISTORY"       => "N",
            "AJAX_OPTION_JUMP"          => "N",
            "AJAX_OPTION_STYLE"         => "Y",
            "BROWSER_TITLE"             => "-",
            "CACHE_GROUPS"              => "Y",
            "CACHE_TIME"                => "36000000",
            "CACHE_TYPE"                => "N",
            "CHECK_DATES"               => "Y",
            "DETAIL_URL"                => "",
            "DISPLAY_BOTTOM_PAGER"      => "N",
            "DISPLAY_DATE"              => "Y",
            "DISPLAY_NAME"              => "Y",
            "DISPLAY_PICTURE"           => "Y",
            "DISPLAY_PREVIEW_TEXT"      => "Y",
            "DISPLAY_TOP_PAGER"         => "N",
            "ELEMENT_CODE"              => "",
            "ELEMENT_ID"                => $requestId,
            "FIELD_CODE"                => ["NAME", ""],
            "IBLOCK_ID"                 => REQUEST_IBLOCK,
            "IBLOCK_TYPE"               => "requests",
            "IBLOCK_URL"                => "",
            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
            "MESSAGE_404"               => "",
            "META_DESCRIPTION"          => "-",
            "META_KEYWORDS"             => "-",
            "PAGER_BASE_LINK_ENABLE"    => "N",
            "PAGER_SHOW_ALL"            => "N",
            "PAGER_TEMPLATE"            => ".default",
            "PAGER_TITLE"               => "Страница",
            "PROPERTY_CODE"             => ["EMAIL", "REQUEST_ID", "INCOTERMS", "CURRENCY", "IS_BLOCKED", "COMMENT", "SUPPLIER_COMMENT", "EVENT", "DELIVERY_TIME", "PAYMENT_ORDER", "STATUS", "SUPPLIER", "SPECIFICATION", "SESSION_ID",""],
            "SET_BROWSER_TITLE"         => "Y",
            "SET_CANONICAL_URL"         => "N",
            "SET_LAST_MODIFIED"         => "N",
            "SET_META_DESCRIPTION"      => "Y",
            "SET_META_KEYWORDS"         => "N",
            "SET_STATUS_404"            => "N",
            "SET_TITLE"                 => "Y",
            "SHOW_404"                  => "N",
            "STRICT_SECTION_CHECK"      => "N",
            "USE_PERMISSIONS"           => "N",
            "USE_SHARE"                 => "N"
        ]
    ); ?>
<?php } ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
