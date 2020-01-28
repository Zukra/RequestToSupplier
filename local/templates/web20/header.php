<? if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page\Asset;

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <?
    Asset::getInstance()->addCss("/bitrix/css/main/bootstrap.min.css");

    CJSCore::Init(['jquery3']);
    Asset::getInstance()->addJs("/local/assets/js/custom.js");
    ?>

    <? $APPLICATION->ShowHead() ?>
    <title><? $APPLICATION->ShowTitle() ?></title>
</head>

<body>

<? $APPLICATION->ShowPanel(); ?>

<div id="container">

    <div id="header">
        <div id="header_text">
            <? $APPLICATION->IncludeFile(
                $APPLICATION->GetTemplatePath("include_areas/company_name.php"),
                [],
                ["MODE" => "html"]
            ); ?>
        </div>

        <div id="company_logo"></div>

        <div id="search">
            &nbsp;Поиск на сайте
            <? $APPLICATION->IncludeComponent("bitrix:search.form", "flat", [
                    "PAGE" => "/search/"
                ]
            ); ?>
        </div>

        <div id="login">
            <? $APPLICATION->IncludeComponent("bitrix:system.auth.form", "auth", [
                    "REGISTER_URL" => "/auth/",
                    "PROFILE_URL"  => "/personal/profile/"
                ]
            ); ?>
        </div>

        <div id="menu">
            <? $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "tabs",
                [
                    "ROOT_MENU_TYPE"        => "top",
                    "MAX_LEVEL"             => "1",
                    "USE_EXT"               => "N",
                    "MENU_CACHE_TYPE"       => "A",
                    "MENU_CACHE_TIME"       => "3600",
                    "MENU_CACHE_USE_GROUPS" => "N",
                    "MENU_CACHE_GET_VARS"   => []
                ]
            ); ?>
        </div>
    </div>

    <table id="content" cellpadding="0" cellspacing="0">
        <tr>
            <td rowspan="4" width="9" class="table-border-color">
                <div style="width:9px"></div>
            </td>
            <td width="4">
                <img src="<?= SITE_TEMPLATE_PATH ?>/images/left_top_corner.gif" width="4" height="4" border="0" alt=""/>
            </td>
            <td align="right">
                <img src="<?= SITE_TEMPLATE_PATH ?>/images/right_top_corner.gif" width="7" height="5" border="0" alt=""/>
            </td>
            <td rowspan="4" width="7" class="table-border-color">
                <div style="width:7px"></div>
            </td>
        </tr>
        <tr>
            <td class="left-column"><? $APPLICATION->IncludeComponent("bitrix:menu", "left", [
                        "ROOT_MENU_TYPE"        => "left",
                        "MAX_LEVEL"             => "1",
                        "CHILD_MENU_TYPE"       => "left",
                        "USE_EXT"               => "Y",
                        "MENU_CACHE_TYPE"       => "A",
                        "MENU_CACHE_TIME"       => "3600",
                        "MENU_CACHE_USE_GROUPS" => "Y",
                        "MENU_CACHE_GET_VARS"   => [
                            0 => "SECTION_ID",
                            1 => "page",
                        ],
                    ]
                ); ?>

                <!-- SOCIALNETWORK -->


                <? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    [
                        "AREA_FILE_SHOW"      => "sect",
                        "AREA_FILE_SUFFIX"    => "inc",
                        "AREA_FILE_RECURSIVE" => "N",
                        "EDIT_MODE"           => "html",
                        "EDIT_TEMPLATE"       => "sect_inc.php"
                    ]
                ); ?><? $APPLICATION->IncludeComponent(
                    "bitrix:main.include",
                    "",
                    [
                        "AREA_FILE_SHOW"      => "page",
                        "AREA_FILE_SUFFIX"    => "inc",
                        "AREA_FILE_RECURSIVE" => "N",
                        "EDIT_MODE"           => "html",
                        "EDIT_TEMPLATE"       => "page_inc.php"
                    ]
                ); ?>
            </td>
            <td class="main-column">

                <div id="printer">
                    <noindex>
                        <a href="<?= htmlspecialchars($APPLICATION->GetCurUri("print=Y")); ?>" title="Версия для печати" rel="nofollow">версия<br/>для печати</a>
                    </noindex>
                </div>

                <div id="navigation"><? $APPLICATION->IncludeComponent(
                        "bitrix:breadcrumb",
                        ".default",
                        [
                            "START_FROM" => "0",
                            "PATH"       => "",
                            "SITE_ID"    => ""
                        ]
                    ); ?></div>
                <h1 id="pagetitle"><? $APPLICATION->ShowTitle(false) ?></h1>