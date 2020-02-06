<? if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult['SUPPLIER'] = \Zkr\Helper::getSupplierByAccessKey(\Zkr\Helper::getAccessKey());
