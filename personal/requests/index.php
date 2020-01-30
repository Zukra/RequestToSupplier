<?php

use Bitrix\Main\Context;

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Requests list");

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
    if ($elementId) {
        $oDateTimeExpiry = new \Bitrix\Main\Type\DateTime($supplier->getExpiryDate()->getValue(), "Y-m-d");
        $oDateTimeCurrent = new \Bitrix\Main\Type\DateTime("", "Y-m-d");
        if ($oDateTimeCurrent->getTimestamp() > $oDateTimeExpiry->getTimestamp()) {
            LocalRedirect('/personal/update-key/');
        }
    }
} else {
    LocalRedirect('/');
} ?>

<?php if ($elementId) { ?>
    <?php $GLOBALS['arrFilter'] = ['PROPERTY' => ['SUPPLIER' => $elementId]]; ?>
    <? $APPLICATION->IncludeComponent(
        "zkr:request.list",
        ".default",
        [
            "ACTIVE_DATE_FORMAT"              => "d.m.Y",
            "ADD_SECTIONS_CHAIN"              => "Y",
            "AJAX_MODE"                       => "Y",
            "AJAX_OPTION_ADDITIONAL"          => "",
            "AJAX_OPTION_HISTORY"             => "N",
            "AJAX_OPTION_JUMP"                => "N",
            "AJAX_OPTION_STYLE"               => "Y",
            "CACHE_FILTER"                    => "N",
            "CACHE_GROUPS"                    => "Y",
            "CACHE_TIME"                      => "36000000",
            "CACHE_TYPE"                      => "A",
            "CHECK_DATES"                     => "Y",
            "DETAIL_URL"                      => "/personal/request/?id=#ELEMENT_ID#",
            "DISPLAY_BOTTOM_PAGER"            => "Y",
            "DISPLAY_DATE"                    => "Y",
            "DISPLAY_NAME"                    => "Y",
            "DISPLAY_PICTURE"                 => "Y",
            "DISPLAY_PREVIEW_TEXT"            => "Y",
            "DISPLAY_TOP_PAGER"               => "N",
            "FIELD_CODE"                      => [
                0 => "NAME",
                1 => "TIMESTAMP_X",
                2 => "",
            ],
            "FILTER_NAME"                     => "arrFilter",
            "HIDE_LINK_WHEN_NO_DETAIL"        => "N",
            "IBLOCK_ID"                       => "7",
            "IBLOCK_TYPE"                     => "requests",
            "INCLUDE_IBLOCK_INTO_CHAIN"       => "Y",
            "INCLUDE_SUBSECTIONS"             => "Y",
            "MESSAGE_404"                     => "",
            "NEWS_COUNT"                      => "1",
            "PAGER_BASE_LINK_ENABLE"          => "N",
            "PAGER_DESC_NUMBERING"            => "N",
            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
            "PAGER_SHOW_ALL"                  => "N",
            "PAGER_SHOW_ALWAYS"               => "N",
            "PAGER_TEMPLATE"                  => ".default",
            "PAGER_TITLE"                     => "Заявки",
            "PARENT_SECTION"                  => "",
            "PARENT_SECTION_CODE"             => "",
            "PREVIEW_TRUNCATE_LEN"            => "",
            "PROPERTY_CODE"                   => [
                0 => "IS_BLOCKED",
                1 => "EVENT",
                2 => "STATUS",
                3 => "SUPPLIER",
                4 => "",
            ],
            "SET_BROWSER_TITLE"               => "Y",
            "SET_LAST_MODIFIED"               => "N",
            "SET_META_DESCRIPTION"            => "Y",
            "SET_META_KEYWORDS"               => "Y",
            "SET_STATUS_404"                  => "N",
            "SET_TITLE"                       => "Y",
            "SHOW_404"                        => "N",
            "SORT_BY1"                        => "TIMESTAMP_X",
            "SORT_BY2"                        => "SORT",
            "SORT_ORDER1"                     => "DESC",
            "SORT_ORDER2"                     => "ASC",
            "STRICT_SECTION_CHECK"            => "N",
            "COMPONENT_TEMPLATE"              => ".default"
        ],
        false
    ); ?>
<?php } ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");

