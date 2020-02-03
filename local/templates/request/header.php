<? if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Page\Asset;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?
    //    Asset::getInstance()->addCss("/bitrix/css/main/bootstrap_v4/bootstrap.min.css");
    //    Asset::getInstance()->addCss("/local/assets/css/bootstrap.min.css");
    Asset::getInstance()->addCss("/local/assets/css/bootstrap-3.0.3.min.css");
    Asset::getInstance()->addCss("/local/assets/css/bootstrap.offcanvas.min.css");
    Asset::getInstance()->addCss("/local/assets/css/slick.css");
    Asset::getInstance()->addCss("/local/assets/css/slick-theme.css");
    Asset::getInstance()->addCss("/local/assets/css/custom.css");

    CJSCore::Init(["fx", 'ajax']);
        CJSCore::Init(['jquery3', "fx", 'ajax']);
//    Asset::getInstance()->addJs("/local/assets/js/jquery-3.4.1.min.js");
    Asset::getInstance()->addJs("/local/assets/js/bootstrap.min.js");
    Asset::getInstance()->addJs("/local/assets/js/bootstrap.offcanvas.min.js");
    Asset::getInstance()->addJs("/local/assets/js/slick.js");
    Asset::getInstance()->addJs("/local/assets/js/script.js");
    Asset::getInstance()->addJs("/local/assets/js/custom.js");
    ?>

    <? $APPLICATION->ShowHead() ?>
    <title><? $APPLICATION->ShowTitle() ?></title>
</head>
<body>
<? $APPLICATION->ShowPanel(); ?>

<div class="wrapper">
    <div class="content">
        <header class="container-fluid">
            <div class="header_top">
                <div class="container">
                    <div class="row">
                        <nav class="col-xs-12 navbar navbar-default" role="navigation">
                            <div class="container-fluid">
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle offcanvas-toggle pull-right" data-toggle="offcanvas"
                                            data-target="#js-bootstrap-offcanvas" style="float:left;">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span>
                                           <span class="icon-bar"></span>
                                           <span class="icon-bar"></span>
                                           <span class="icon-bar"></span>
                                        </span>
                                    </button>
                                    <? $APPLICATION->IncludeFile(
                                        $APPLICATION->GetTemplatePath("include_areas/company_logo.php"),
                                        [],
                                        ["MODE" => "html"]
                                    ); ?>
                                </div>
                                <div class="navbar-offcanvas navbar-offcanvas-touch" id="js-bootstrap-offcanvas">
                                    <ul id="menu" class="nav navbar-nav">
                                        <li class="hidden-sm hidden-md hidden-lg mebu_name">
                                            <p>MENU</p>
                                        </li>
                                        <li class="login_linck">
                                            <a href="/personal/">requests:<span>Stalprofil</span></a>
                                        </li>
                                        <li>
                                            <a href="tel:+48223072044">+48 (22) 307-20-44</a>
                                        </li>
                                        <li class="active">
                                            <a href="/">Contacts</a>
                                        </li>
                                        <? global $USER ?>
                                        <? if ($USER->IsAdmin()) { ?>
                                            <li>
                                                <a href="/marketplace/">Marketplace</a>
                                            </li>
                                        <? } ?>
                                    </ul>
                                    <? /*$APPLICATION->IncludeComponent(
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
                                    ); */?>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </header>


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
        ]); ?>
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


        <? /* <div id="container">
            <div id="header">
                <div id="company_logo">
                    <? $APPLICATION->IncludeFile(
                        $APPLICATION->GetTemplatePath("include_areas/company_logo.php"),
                        [],
                        ["MODE" => "html"]
                    ); ?>
                </div>

                <div id="menu">
                    <span>+48 (22) 307-20-44</span>
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
*/ ?>
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

        <? /*<h1 id="pagetitle"><? $APPLICATION->ShowTitle(false) ?></h1>*/ ?>
