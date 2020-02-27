<?php


namespace Zkr\Supplier\Price\Models;


use Bitrix\Iblock\Elements\ElementRequestTable;

class Request
{
    static $arSelect = [
        "ID", "NAME", 'TIMESTAMP_X', 'REQUEST_ID', 'PAYMENT_ORDER', 'DELIVERY_TIME', 'INCOTERMS',
        "EMAIL", 'COMMENT', "CONTACT", 'CURRENCY', 'STATUS', 'EVENT', 'SUPPLIER_COMMENT',
        'IS_BLOCKED', "SPECIFICATION", "SUPPLIER", 'SESSION_ID'
    ];
    static $arFilter = ["ACTIVE" => "Y"];
    static $arOrder  = ['ID'];

    static function getById($id): ?\Bitrix\Iblock\Elements\EO_ElementRequest
    {
        $request = null;
        if (! empty($id)) {
            $arSelect = static::$arSelect;
            $arFilter = static::$arFilter;
            $arFilter['REQUEST_ID.VALUE'] = $id;

            /** @var \Bitrix\Iblock\Elements\EO_ElementRequest $request */
            $request = ElementRequestTable::getByPrimary($id, [
                'select' => $arSelect,
            ])->fetchObject();
        }

        return $request;
    }

    static function getBy1CId($id): ?\Bitrix\Iblock\Elements\EO_ElementRequest
    {
        $request = null;
        if (! empty($id)) {
            $arSelect = static::$arSelect;
            $arFilter = static::$arFilter;
            $arFilter['REQUEST_ID.VALUE'] = $id;

            /** @var \Bitrix\Iblock\Elements\EO_ElementRequest $request */
            $request = ElementRequestTable::query()
                ->setSelect($arSelect)
                ->setFilter($arFilter)
                ->fetchObject();
        }

        return $request;
    }

    static function toArray(\Bitrix\Iblock\Elements\EO_ElementRequest $request): array
    {
        return [
            'id'            => $request->getRequestId()->getValue(),
            'internal_id'   => $request->getId(),
            'payment_order' => $request->getPaymentOrder()->getValue(),
            'delivery_time' => $request->getDeliveryTime()->getValue(),
            'incoterms'     => $request->getIncoterms()->getValue(),
            'currency'      => $request->getCurrency()->getValue(),
            'status'        => $request->getStatus()->getValue(),
            'comment'       => $request->getComment()->getValue(),
            "event"         => $request->getEvent()->getValue(),
            "comment_s"     => $request->getSupplierComment()->getValue(),
            "contact"       => Contact::toArray(Contact::getById($request->getContact()->getValue())),
            "supplier"      => Supplier::toArray(Supplier::getById($request->getSupplier()->getValue())),
            "specification" => Specification::toArray(Specification::getByIds($request->getSpecification()->getValueList())),
            //                "email"         => $request->getEmail()->getValue(),
        ];
    }
}