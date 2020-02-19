<? if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

if ($arParams["ELEMENT_ID"]) {
    /** @var \Bitrix\Iblock\Elements\EO_ElementSupplier $supplier */
    $supplier = \Bitrix\Iblock\Elements\ElementSupplierTable::getByPrimary($arParams["ELEMENT_ID"], [
        'select' => ['ID', 'NAME', 'ID_ONE_C', 'KEY', 'CONTACTS', 'EXPIRY_DATE']
    ])->fetchObject();
}
$arResult['SUPPLIER'] = $supplier ?: [];
