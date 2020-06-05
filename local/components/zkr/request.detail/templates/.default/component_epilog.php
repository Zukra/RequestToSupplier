<? if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
$APPLICATION->SetPageProperty("title", "EMK Inquiry " . substr($arResult["NAME"], 0, 11));

/**  @var \Bitrix\Iblock\Elements\EO_ElementSupplier $supplier */
$supplier = $arResult['SUPPLIER'];
try {
    \Bitrix\Main\Loader::includeModule('zkr.logs');

    if (! empty($arResult['ID']) && ! empty($supplier->getId())) {
        $data = [
            'INQUIRY_ID'  => $arResult['ID'],
            'SUPPLIER_ID' => $supplier->getId(),
            'DATE_MODIFY' => new \Bitrix\Main\Type\DateTime(),
        ];
        $result = \Zkr\Logs\Inquiry\LogTable::add($data);
        if ($result->isSuccess()) {
//                return true;
        } else {
            $exception = new \Zkr\Exceptions\FileSaveException($result->getErrorMessages());
            $exception->saveMessage();
        }
    }
} catch (Exception $e) {
    $exception = new \Zkr\Exceptions\FileSaveException($e->getMessage());
    $exception->saveMessage();
}
