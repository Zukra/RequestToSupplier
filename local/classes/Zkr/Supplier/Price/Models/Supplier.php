<?php


namespace Zkr\Supplier\Price\Models;


use Bitrix\Iblock\Elements\ElementSupplierTable;
use Bitrix\Iblock\Elements\EO_ElementSupplier;
use Bitrix\Main\Diag\Debug;
use DateTime;

class Supplier
{
    static function getById($id): ?EO_ElementSupplier
    {
        $supplier = null;
        if ($id && \Bitrix\Main\Loader::includeModule('iblock')) {
            /** @var \Bitrix\Iblock\Elements\EO_ElementSupplier $supplier */
            $supplier = ElementSupplierTable::getByPrimary($id, [
                'select' => ['ID', 'NAME', 'ID_ONE_C', 'KEY', 'CONTACTS', 'EXPIRY_DATE'],
            ])->fetchObject();
        }

        return $supplier;
    }

    static function updateSupplierKey(array $params): ?EO_ElementSupplier
    {
        $supplier = static::getById1C($params['supplier_id']);

        if ($supplier) {
            $supplier
                ->setKey($params['key'])
                ->setExpiryDate((new DateTime('@' . $params['key_expiry']))->format('Y-m-d'));
            $supplier->save();
        }

        return $supplier;
    }

    static function getById1C($id): ?EO_ElementSupplier
    {
        $supplier = null;

        if ($id && \Bitrix\Main\Loader::includeModule('iblock')) {
            /** @var EO_ElementSupplier $supplier */
            $supplier = \Bitrix\Iblock\Elements\ElementSupplierTable::getList([
                'select' => ['ID', 'NAME', 'ID_ONE_C', 'KEY', 'CONTACTS', 'EXPIRY_DATE'],
                'filter' => ['ID_ONE_C.VALUE' => $id]
            ])->fetchObject();
        }

        return $supplier;
    }

    public static function toArray($supplier): ?array
    {
        $contacts = array_map(function ($contact) {
            return Contact::toArray(Contact::getById($contact));
        }, $supplier->getContacts()->getValueList());

        $result = [
            'id'          => $supplier->getIdOneC()->getValue(),
            'internal_id' => $supplier->getId(),
            'name'        => $supplier->getName(),
            'key'         => $supplier->getKey()->getValue(),
            'key_expiry'  => (new DateTime($supplier->getExpiryDate()->getValue()))->format('U'),
            'contacts'    => $contacts,
        ];

        return $result;
    }
}