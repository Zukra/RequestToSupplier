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

    CJSCore::Init(['jquery3', "fx", 'ajax']);
    Asset::getInstance()->addJs("/local/assets/js/custom.js");
    Asset::getInstance()->addJs("/local/assets/js/bootstrap.min.js");
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

    <div id="content">

        <? $APPLICATION->IncludeComponent("bitrix:menu", "left", [
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
        ); ?>
        <? $APPLICATION->IncludeComponent(
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

        <? /*
                <div id="navigation">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:breadcrumb",
                        ".default",
                        [
                            "START_FROM" => "0",
                            "PATH"       => "",
                            "SITE_ID"    => ""
                        ]
                    ); ?>
                </div>
*/ ?>

        <h1 id="pagetitle"><? $APPLICATION->ShowTitle(false) ?></h1>
