<?php


namespace Zkr;


use Bitrix\Main\Context;

class Helper
{

    public static function getAccessKey()
    {
        $context = Context::getCurrent();
        $request = $context->getRequest();

        $accessKey = $request->get('key') ?? $_SESSION['access_key'];
        $_SESSION['access_key'] = $accessKey;

        return $accessKey;
    }

    public static function getSupplierByAccessKey($accessKey = ""): ?\Bitrix\Iblock\Elements\EO_ElementSupplier
    {
        \Bitrix\Main\Loader::includeModule('iblock');

        /*    $requestSupplier = new \Zkr\RequestSupplier();
    $supplier = $requestSupplier->getItem(['ID'], ['KEY.VALUE' => $accessKey]);
*/
        /** @var \Bitrix\Iblock\Elements\EO_ElementSupplier $supplier */
        $supplier = \Bitrix\Iblock\Elements\ElementSupplierTable::query()
            ->setSelect(['ID', 'EXPIRY_DATE', 'NAME', 'ID_ONE_C'])
            ->setFilter(['KEY.VALUE' => $accessKey])
            ->fetchObject();

        return $supplier;
    }

    public static function isValidAccessKey(string $key): bool
    {
        $oDateTimeExpiry = new \Bitrix\Main\Type\DateTime($key, "Y-m-d");
        $oDateTimeCurrent = new \Bitrix\Main\Type\DateTime("", "Y-m-d");

        return ($oDateTimeCurrent->getTimestamp() < $oDateTimeExpiry->getTimestamp());
    }

    public static function checkAccess($checkValidAccessKey = true): ?int
    {
        $accessKey = \Zkr\Helper::getAccessKey();
        /** @var \Bitrix\Iblock\Elements\EO_ElementSupplier $supplier */
        $supplier = \Zkr\Helper::getSupplierByAccessKey($accessKey);
        $elementId = $supplier ? $supplier->getId() : null;
        if ($elementId) {
            if ($checkValidAccessKey) {
                $isValidAccessKey = \Zkr\Helper::isValidAccessKey($supplier->getExpiryDate()->getValue());
                if (! $isValidAccessKey) {
                    LocalRedirect('/personal/update-key/');
                }
            }
        } else {
            LocalRedirect('/personal/error-key/');
        }

        return $elementId;
    }
}