<?php


namespace Zkr\Supplier\Price\Models;


use Bitrix\Iblock\Elements\ElementRequestSpecificationTable;
use Bitrix\Iblock\Elements\EO_ElementRequestSpecification_Collection;

class Specification
{
    static $arSelect = [
        'ID', 'NAME', 'SKU', 'QUANTITY_R', 'SUPPLIER_QUANTITY',
        'UNIT_MEASURE', 'SUPPLIER_UNIT', 'SUPPLIER_PRICE_UNIT', 'DESC_ID',
        'DELIVERY_TIME', 'INCOTERMS', 'REPLACEMENT', 'COMMENT', 'SUPPLIER_COMMENT'
    ];

    static function getByIds(array $ids): ?EO_ElementRequestSpecification_Collection
    {
        $specifications = null;
        if ($ids) {
            /** @var \Bitrix\Iblock\Elements\EO_ElementRequestSpecification_Collection $specifications */
            $specifications = ElementRequestSpecificationTable::getList([
                'select' => static::$arSelect,
                'filter' => ['ID' => $ids]
            ])->fetchCollection();
        }

        return $specifications;
    }

    static function toArray(EO_ElementRequestSpecification_Collection $specifications): array
    {
        $result = [];
        if ($specifications) {
            $result = array_map(function ($item) {
                /** @var \Bitrix\Iblock\Elements\EO_ElementRequestSpecification $item */
                return [
                    'sku'           => $item->getSku()->getValue(),
                    'internal_id'   => $item->getId(),
                    'name'          => $item->getName(),
                    'desc_id'       => $item->getDescId()->getValue(),
                    'comment'       => $item->getComment()->getValue(),
                    'quantity_r'    => $item->getQuantityR()->getValue(),
                    'unit_measure'  => $item->getUnitMeasure()->getValue(),
                    'quantity_s'    => $item->getSupplierQuantity()->getValue(),
                    'unit_s'        => $item->getSupplierUnit()->getValue(),
                    'price_s'       => $item->getSupplierPriceUnit()->getValue(),
                    'delivery_time' => $item->getDeliveryTime()->getValue(),
                    'incoterms'     => $item->getIncoterms()->getValue(),
                    'replacement'   => $item->getReplacement()->getValue() > 0 ? 1 : 0,
                    'comment_s'     => $item->getSupplierComment()->getValue()
                ];
            }, $specifications->getAll());
        }

        return $result;
    }
}