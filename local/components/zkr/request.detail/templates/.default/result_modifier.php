<? if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var \Bitrix\Iblock\Elements\EO_ElementSupplier                        $supplier
 * @var \Bitrix\Iblock\Elements\EO_ElementSupplierContact_Collection      $supplierContacts
 * @var \Bitrix\Iblock\Elements\EO_ElementRequestSpecification_Collection $specification
 */

$supplier = \Bitrix\Iblock\Elements\ElementSupplierTable::getByPrimary($arResult["PROPERTIES"]["SUPPLIER"]['VALUE'], [
    'select' => ['ID', 'NAME', 'ID_ONE_C', 'KEY', 'CONTACTS', 'EXPIRY_DATE']
])->fetchObject();

if ($supplier) {
    $supplierContacts = \Bitrix\Iblock\Elements\ElementSupplierContactTable::query()
        ->setSelect(['ID', 'NAME', 'EMAIL'])
        ->setFilter(['ID' => $supplier->getContacts()->getValueList()])
        ->fetchCollection();

    $specification = \Bitrix\Iblock\Elements\ElementRequestSpecificationTable::query()
        ->setSelect(['ID', 'NAME', 'SKU', 'QUANTITY_R', 'SUPPLIER_QUANTITY',
                     'UNIT_MEASURE', 'SUPPLIER_UNIT', 'SUPPLIER_PRICE_UNIT',
                     'DELIVERY_TIME', 'INCOTERMS', 'REPLACEMENT', 'COMMENT', 'SUPPLIER_COMMENT'])
        ->setFilter(['ID' => $arResult["PROPERTIES"]["SPECIFICATION"]['VALUE']])
        ->fetchCollection();
}

$arResult['SUPPLIER'] = $supplier ?: [];
$arResult['SUPPLIER_CONTACTS'] = $supplierContacts ?: [];
$arResult['SPECIFICATION'] = $specification ?: [];
