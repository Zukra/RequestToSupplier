<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

$APPLICATION->SetTitle("Inquiries");

$supplierId = \Zkr\Helper::checkAccess();
?>

<?php $GLOBALS['arrFilter'] = ['PROPERTY' => ['SUPPLIER' => $supplierId]]; ?>
<? $APPLICATION->IncludeComponent(
    "zkr:request.list",
    ".default",
    [
        "ACTIVE_DATE_FORMAT"              => "d.m.Y",
        "ADD_SECTIONS_CHAIN"              => "Y",
        "AJAX_MODE"                       => "Y",
        "AJAX_OPTION_ADDITIONAL"          => "",
        "AJAX_OPTION_HISTORY"             => "Y",
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
        "IBLOCK_ID"                       => REQUEST_IBLOCK,
        "IBLOCK_TYPE"                     => "requests",
        "INCLUDE_IBLOCK_INTO_CHAIN"       => "Y",
        "INCLUDE_SUBSECTIONS"             => "Y",
        "MESSAGE_404"                     => "",
        "NEWS_COUNT"                      => "15",
        "PAGER_BASE_LINK_ENABLE"          => "N",
        "PAGER_DESC_NUMBERING"            => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL"                  => "N",
        "PAGER_SHOW_ALWAYS"               => "N",
        "PAGER_TEMPLATE"                  => "round",
        "PAGER_TITLE"                     => "Inquiries",
        "PARENT_SECTION"                  => "",
        "PARENT_SECTION_CODE"             => "",
        "PREVIEW_TRUNCATE_LEN"            => "",
        "PROPERTY_CODE"                   => [
            0 => "IS_BLOCKED",
            1 => "EVENT",
            2 => "STATUS",
            3 => "STATUS_T",
            4 => "SUPPLIER",
            5 => "CONTACT",
            6 => "",
        ],
        "SET_BROWSER_TITLE"               => "N",
        "SET_LAST_MODIFIED"               => "N",
        "SET_META_DESCRIPTION"            => "Y",
        "SET_META_KEYWORDS"               => "N",
        "SET_STATUS_404"                  => "N",
        "SET_TITLE"                       => "N",
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

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php");
