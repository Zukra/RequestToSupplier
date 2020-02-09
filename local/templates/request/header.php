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
    //    Asset::getInstance()->addCss("/local/assets/css/bootstrap.min.css");
    Asset::getInstance()->addCss("/local/assets/css/bootstrap-3.0.3.min.css");
    Asset::getInstance()->addCss("/local/assets/css/bootstrap.offcanvas.min.css");
    Asset::getInstance()->addCss("/local/assets/css/slick.css");
    Asset::getInstance()->addCss("/local/assets/css/slick-theme.css");
    Asset::getInstance()->addCss("/local/assets/css/custom.css");

    CJSCore::Init(["fx", 'ajax']);
    //        CJSCore::Init(['jquery3', "fx", 'ajax']);
    Asset::getInstance()->addJs("/local/assets/js/jquery-3.4.1.min.js");
    Asset::getInstance()->addJs("/local/assets/js/popper.min.js");
    //    Asset::getInstance()->addJs("/local/assets/js/popper-1.14.7.min.js");
    //        Asset::getInstance()->addJs("/local/assets/js/bootstrap-3.0.3.min.js");
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
                                    <? $APPLICATION->IncludeComponent(
                                        "zkr:empty",
                                        "header_menu",
                                        [
                                            "CACHE_TIME" => "3600",
                                            "CACHE_TYPE" => "N"
                                        ]
                                    ); ?>
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

        <? /*<h1 id="pagetitle"><? $APPLICATION->ShowTitle(false) ?></h1>*/ ?>
