<?

/** @var \Bitrix\Iblock\Elements\EO_ElementSupplier $supplier */
$supplier = \Bitrix\Iblock\Elements\ElementSupplierTable::getByPrimary($arResult["ITEMS"][0]["DISPLAY_PROPERTIES"]['SUPPLIER']['VALUE'], [
    'select' => ['ID', 'NAME', 'ID_ONE_C', 'KEY', 'CONTACTS', 'EXPIRY_DATE']
])->fetchObject();

$arResult['SUPPLIER'] = $supplier ?: [];
