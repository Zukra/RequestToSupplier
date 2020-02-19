<?
if (count($arResult["ITEMS"]) > 0) {
    /** @var \Bitrix\Iblock\Elements\EO_ElementSupplier $supplier */
    $supplier = \Bitrix\Iblock\Elements\ElementSupplierTable::getByPrimary($arResult["ITEMS"][0]["DISPLAY_PROPERTIES"]['SUPPLIER']['VALUE'], [
        'select' => ['ID', 'NAME', 'ID_ONE_C', 'KEY', 'CONTACTS', 'EXPIRY_DATE']
    ])->fetchObject();

    $arResult['SUPPLIER'] = $supplier ?: [];

    foreach ($arResult["ITEMS"] as $key => $item) {
        /** @var \Bitrix\Iblock\Elements\EO_ElementSupplierContact $contact */
        $contact = \Bitrix\Iblock\Elements\ElementSupplierContactTable::getByPrimary($item["DISPLAY_PROPERTIES"]['CONTACT']['VALUE'], [
            'select' => ['ID', 'NAME', 'EMAIL']
        ])->fetchObject();
        $arResult["ITEMS"][$key]['CONTACT'] = $contact;
    }
}
