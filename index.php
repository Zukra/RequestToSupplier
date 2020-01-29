<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Демонстрационная версия продукта «1С-Битрикс: Управление сайтом»");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("Новости");
?>

    <div class="list-group">
        <a href="#" class="list-group-item list-group-item-action js-get-key">Get new key</a>
        <a href="/marketplace" class="list-group-item list-group-item-action">Marketplace</a>
    </div>

<? $APPLICATION->IncludeComponent(
    "bitrix:news.list",
    "",
    [
        "DISPLAY_DATE"              => "Y",
        "DISPLAY_NAME"              => "Y",
        "DISPLAY_PICTURE"           => "N",
        "DISPLAY_PREVIEW_TEXT"      => "Y",
        "IBLOCK_TYPE"               => "news",
        "IBLOCK_ID"                 => "3",
        "NEWS_COUNT"                => "5",
        "SORT_BY1"                  => "ACTIVE_FROM",
        "SORT_ORDER1"               => "DESC",
        "SORT_BY2"                  => "SORT",
        "SORT_ORDER2"               => "ASC",
        "FILTER_NAME"               => "",
        "FIELD_CODE"                => ["", ""],
        "PROPERTY_CODE"             => ["", ""],
        "DETAIL_URL"                => "/content/news/#SECTION_ID#/#ELEMENT_ID#/",
        "PREVIEW_TRUNCATE_LEN"      => "0",
        "ACTIVE_DATE_FORMAT"        => "d.m.Y",
        "DISPLAY_PANEL"             => "N",
        "SET_TITLE"                 => "N",
        "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
        "CACHE_TIME"                => "3600",
        "CACHE_FILTER"              => "N",
        "DISPLAY_TOP_PAGER"         => "N",
        "DISPLAY_BOTTOM_PAGER"      => "N",
        "PAGER_TITLE"               => "Новости",
        "PAGER_SHOW_ALWAYS"         => "N",
        "PAGER_TEMPLATE"            => "",
        "PAGER_DESC_NUMBERING"      => "N",
        "PAGER_SHOW_ALL"            => "N",
    ]
); ?>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>