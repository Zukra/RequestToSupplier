<?

/** @var \Bitrix\Iblock\Elements\EO_ElementSupplier $supplier */
$supplier = \Bitrix\Iblock\Elements\ElementSupplierTable::getByPrimary($arParams["ELEMENT_ID"], [
    'select' => ['ID', 'NAME', 'ID_ONE_C', 'KEY', 'CONTACTS', 'EXPIRY_DATE']
])->fetchObject();

$arResult['SUPPLIER'] = $supplier ?: [];
