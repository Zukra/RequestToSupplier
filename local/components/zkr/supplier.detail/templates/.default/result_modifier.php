<?

/** @var \Bitrix\Iblock\Elements\EO_ElementSupplierContact $contacts */
$contacts = \Bitrix\Iblock\Elements\ElementSupplierContactTable::query()
    ->setSelect(['ID', 'NAME', 'EMAIL'])
    ->setFilter(['ID' => $arResult["DISPLAY_PROPERTIES"]["CONTACTS"]['VALUE']])
    ->fetchCollection();

$arResult['CONTACTS'] = $contacts ?: [];
