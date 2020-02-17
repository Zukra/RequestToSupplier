<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Personal");

$supplierId = \Zkr\Helper::checkAccess();
?>

<? $APPLICATION->IncludeComponent(
    "zkr:supplier.detail",
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
        "CACHE_TYPE"                => "A",
        "CHECK_DATES"               => "Y",
        "DETAIL_URL"                => "",
        "DISPLAY_BOTTOM_PAGER"      => "Y",
        "DISPLAY_DATE"              => "Y",
        "DISPLAY_NAME"              => "Y",
        "DISPLAY_PICTURE"           => "Y",
        "DISPLAY_PREVIEW_TEXT"      => "Y",
        "DISPLAY_TOP_PAGER"         => "N",
        "ELEMENT_CODE"              => "",
        "ELEMENT_ID"                => $supplierId,
        "FIELD_CODE"                => ["NAME", ""],
        "IBLOCK_ID"                 => REQUEST_SUPPLIER_IBLOCK,
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
        "PROPERTY_CODE"             => ["ID_ONE_C", "EXPIRY_DATE", "KEY", "CONTACTS", ""],
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

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
