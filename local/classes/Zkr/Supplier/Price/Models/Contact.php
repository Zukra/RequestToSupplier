<?php


namespace Zkr\Supplier\Price\Models;


use Bitrix\Iblock\Elements\ElementSupplierContactTable;
use Bitrix\Iblock\Elements\EO_ElementSupplierContact;

class Contact
{
    static function getById($id): ?EO_ElementSupplierContact
    {
        $result = null;
        $contact = ElementSupplierContactTable::getByPrimary($id, [
                'select' => ['ID', 'NAME', 'EMAIL']]
        )->fetchObject();

        if ($contact) {
            $result = $contact;
//            $result = ['name' => $contact->getName(), 'email' => $contact->getEmail()->getValue()];
        }

        return $result;
    }

    static function toArray(EO_ElementSupplierContact $contact): array
    {
        return [
            'name'  => $contact->getName(),
            'email' => $contact->getEmail()->getValue()
        ];
    }
}