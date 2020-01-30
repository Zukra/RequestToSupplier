<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

use Bitrix\Main\Context;

$APPLICATION->SetTitle("Персональный раздел");

$context = Context::getCurrent();
$request = $context->getRequest();

$accessKey = $request->get('key') ?? $_SESSION['access_key'];
$_SESSION['access_key'] = $accessKey;

if ($accessKey) { ?>
    <?php
    /*    $requestSupplier = new \Zkr\RequestSupplier();
        $supplier = $requestSupplier->getItem(['ID'], ['KEY.VALUE' => $accessKey]);
    */

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
    ?>
    <?php if ($elementId) { ?>
        <?php
        $oDateTimeExpiry = new \Bitrix\Main\Type\DateTime($supplier->getExpiryDate()->getValue(), "Y-m-d");
        $oDateTimeCurrent = new \Bitrix\Main\Type\DateTime("", "Y-m-d");

        if ($oDateTimeCurrent->getTimestamp() > $oDateTimeExpiry->getTimestamp()) {
            LocalRedirect('/personal/update-key/');
        } ?>
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
                "ELEMENT_ID"                => $elementId,
                "FIELD_CODE"                => ["NAME", ""],
                "IBLOCK_ID"                 => "10",
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
    <?php } ?>
<?php } else {
    LocalRedirect('/');
} ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
