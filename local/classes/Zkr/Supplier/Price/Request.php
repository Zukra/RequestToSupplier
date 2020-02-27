<?php


namespace Zkr\Supplier\Price;


use Bitrix\Iblock\Elements\ElementRequestSpecificationTable;
use Bitrix\Iblock\Elements\ElementRequestTable;
use Bitrix\Iblock\Elements\ElementSupplierContactTable;
use Bitrix\Iblock\Elements\ElementSupplierTable;
use Bitrix\Iblock\Elements\EO_ElementRequest;
use Bitrix\Iblock\Elements\EO_ElementRequestSpecification;
use Bitrix\Iblock\Elements\EO_ElementSupplier;
use Bitrix\Iblock\Elements\EO_ElementSupplierContact;
use Bitrix\Iblock\Iblock;
use CIBlockElement;
use DateTime;
use Zkr\Supplier\Price\Models\Contact;
use Zkr\Supplier\Price\Models\Supplier;

class Request
{
    public const SCOPE = 'request';

    public const WAIT_REPLY     = 'Waiting for a reply';
    public const BLOCKED_UPDATE = 'Blocked for update';
    public const SENT           = 'Sent';

    public function get($query, $n, \CRestServer $server)
    {
        $result = [];
        if (! empty($query['id'])) {
//            $request = \Zkr\Supplier\Price\Models\Request::getById($query['id']);
            $request = \Zkr\Supplier\Price\Models\Request::getBy1CId($query['id']);
            if ($request) {
                $request->setIsBlocked(false);
                $request->save();
                $result = \Zkr\Supplier\Price\Models\Request::toArray($request);
            }
        }

        return ['status' => $result ? 1 : 0, 'result' => $result];
    }

    public function update($query, $n, \CRestServer $server)
    {
        $result = null;

        if (! empty($query['data'])) {
            foreach ($query['data'] as $datum) {
                if ($elem = $this->isHasElem($datum['id'])) {
                    $elem->fillIsBlocked();
                    $isBlocked = $elem->getIsBlocked() ? $elem->getIsBlocked()->getValue() : 0;
                    if ($isBlocked) {
                        $elem->fillRequestId();
                        $elem->fillContact();
                        $elem->fillSupplier();
                        $supplier = Supplier::toArray(Supplier::getById($elem->getSupplier()->getValue()));
                        $result[] = [
                            'status'      => 0,
                            'errors'      => 'Request to update is blocked by supplier',
                            "request_id"  => $elem->getRequestId()->getValue(),
                            "internal_id" => $elem->getId(),
                            "contact"     => Contact::toArray(Contact::getById($elem->getContact()->getValue())),
                            "supplier"    => ['id' => $supplier['id'], 'name' => $supplier['name']],
                        ];
                    } else {
                        $elem = $this->updateElem($elem->getId(), $datum);
                        $result[] = [
                            'status'      => 1,
                            'message'     => 'updated',
                            "request_id"  => $elem->getRequestId()->getValue(),
                            "internal_id" => $elem->getId(),
                        ];
                    }
                } else {
                    $elem = $this->addElem($datum);
                    $elem = $this->updateElem($elem->getId(), $datum);
                    $result[] = [
                        'status'      => 1,
                        'message'     => 'added',
                        "request_id"  => $elem->getRequestId()->getValue(),
                        "internal_id" => $elem->getId(),
                    ];
                }
            }
        } else {
            $result = ['status' => 0];
        }

        return ['result' => $result];
    }

    /**
     * @param $requestId
     * @return EO_ElementRequest
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function isHasElem($requestId)
    {
        $elem = ElementRequestTable::query()
            ->setSelect(['ID', 'NAME'])
            ->setFilter(['NAME' => $requestId])
            ->fetchObject();

        return $elem;
    }

    public function updateElem($elemId = null, $data = null)
    {
        $elem = null;
        if ($elemId && $data) {
            /** @var EO_ElementRequest $elem */
            $elem = ElementRequestTable::getByPrimary($elemId)->fetchObject();
            $elem->fillName();

            $supplier = $this->checkSupplier($data['supplier']);
            $specifications = $this->checkSpecification($data['specification'], $elem->getId());

            $elem
                ->setRequestId($data['id'])
                ->setTimestampX(new \Bitrix\Main\Type\DateTime())
                ->setEvent(static::WAIT_REPLY)   // $data['event']
                ->setStatus(static::WAIT_REPLY) // $data['status']
                ->setPaymentOrder($data['payment_order'])
                ->setDeliveryTime($data['delivery_time'])
                ->setIncoterms($data['incoterms'])
                ->setCurrency($data['currency'])
                ->setComment($data['comment'])
                ->setSupplierComment($data['comment_s'])
                ->setEmail($data['contact']['email'])
                ->setContact(static::getSupplierContact($data['contact'])->getId())
                ->setSupplier($supplier->getId())
                ->setSessionId('');

            $res = $elem->save();

            $specProps = [];
            foreach ($specifications as $spec) {
                $specProps[] = ["VALUE" => $spec->getId()];
            }
            // использую этот метод обновления множественного св-ва, т.к. не разобрался, как сделать по "нормальному"
            CIBlockElement::SetPropertyValuesEx($elem->getId(), REQUEST_IBLOCK, ["SPECIFICATION" => $specProps]);
        }

        return $elem;
    }

    public function checkSupplier($data)
    {
        $supplier = null;

        /** @var EO_ElementSupplier $supplier */
        $supplier = ElementSupplierTable::query()
            ->setSelect(['ID', 'NAME'])
            ->setFilter(['ID_ONE_C.VALUE' => $data['id']])
            ->fetchObject();

        if (! $supplier) {
            $el = new CIBlockElement();
            $arLoadProductArray = [
                //            "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
                "IBLOCK_ID" => REQUEST_SUPPLIER_IBLOCK,
                "NAME"      => $data['name'],
                "ACTIVE"    => "Y",            // активен
            ];
            if ($supplierId = $el->Add($arLoadProductArray)) {
                //                $supplierId = $supplierId ?: $supplier->getId();
                $supplier = ElementSupplierTable::wakeUpObject($supplierId);
            }
        }
        $supplier
            ->setTimestampX(new \Bitrix\Main\Type\DateTime())
            ->setName($data['name'])
            ->setKey($data['key'])
            ->setExpiryDate(date('Y-m-d', $data['key_expiry']))
            ->setIdOneC($data['id']);

        $contactProps = [];
        $contactIds = [];
        foreach ($data['contacts'] as $contact) {
            $item = static::getSupplierContact($contact, $supplier->getId());
            $contactProps[] = ["VALUE" => $item->getId()];
            $contactIds[] = $item->getId();
        }
        $supplier->save();

        // использую этот метод обновления множественного св-ва, т.к. не разобрался, как сделать по "нормальному"
        CIBlockElement::SetPropertyValuesEx($supplier->getId(), REQUEST_SUPPLIER_IBLOCK, ["CONTACTS" => $contactProps]);

        // удалить лишние контакты, которые уже не принадлежат данному поставщику
        /** @var \Bitrix\Iblock\Elements\EO_ElementSupplierContact_Collection $contacts */
        $contacts = ElementSupplierContactTable::query()
            ->setSelect(['ID'])
            ->setFilter(['SUPPLIER.VALUE' => $supplier->getId(), '!ID' => $contactIds])
            ->fetchCollection();
        foreach ($contacts as $elem) {
            $elem->delete();
        }

        return $supplier;
    }

    public function checkSpecification($data, $requestId)
    {
        $specifications = null;
        $specIds = [];

        /** @var EO_ElementRequestSpecification $specification */
        foreach ($data as $datum) {
            $specification = ElementRequestSpecificationTable::query()
                ->setSelect(['ID', 'NAME'])
                ->setFilter(['SKU.VALUE' => $datum["sku"]])
                ->fetchObject();

            if (! $specification) {
                $el = new CIBlockElement();
                $arLoadProductArray = [
                    //            "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
                    "IBLOCK_ID" => REQUEST_SPECIFICATION_IBLOCK,
                    "NAME"      => $datum['name'],
                    "ACTIVE"    => "Y",            // активен
                ];
                if ($id = $el->Add($arLoadProductArray)) {
                    $specification = ElementRequestSpecificationTable::wakeUpObject($id);
                }
            }
            $specification
                ->setTimestampX(new \Bitrix\Main\Type\DateTime())
                ->setDescId($datum['desc_id'] ?? '')
                ->setRequest($requestId)
                ->setSku($datum['sku'])
                ->setName($datum['name'])
                ->setComment($datum['comment'])
                ->setQuantityR($datum['quantity_r'])
                ->setUnitMeasure($datum['unit_measure']);
            // данные, заполненные поставщиком
            $specification
                ->setSupplierQuantity($datum['quantity_s'])
                ->setSupplierUnit($datum['unit_s'])
                ->setSupplierPriceUnit($datum['price_s'])
                ->setIncoterms($datum['incoterms'])
                ->setDeliveryTime($datum['delivery_time'])
                ->setReplacement($datum['replacement'])
                ->setSupplierComment($datum['comment_s']);

            $specification->save();

            $specIds[] = $specification->getId();
            $specifications[] = $specification;
        }

        // удалить лишние позиции спецификации, которые уже не принадлежат данной заявке
        $items = ElementRequestSpecificationTable::query()
            ->setSelect(['ID'])
            ->setFilter(['REQUEST.VALUE' => $requestId, '!ID' => $specIds])
            ->fetchCollection();
        foreach ($items as $item) {
            $item->delete();
        }

        return $specifications;
    }

    public function addElem($data)
    {
        $elem = null;
        $el = new CIBlockElement();
        $arLoadProductArray = [
            //            "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
            "IBLOCK_ID" => REQUEST_IBLOCK,
            "NAME"      => $data['id'],
            "ACTIVE"    => "Y",            // активен
        ];
        if ($elemId = $el->Add($arLoadProductArray)) {
            /** @var EO_ElementRequest $elem */
            $elem = ElementRequestTable::wakeUpObject($elemId);
        }

        return $elem;
    }

    public function deleteElem($elem): \Bitrix\Main\ORM\Data\Result
    {
        return $elem->delete();
    }

    public static function getSupplierContact($contact, $supplierId = false)
    {
        $elem = null;

        /** @var EO_ElementSupplierContact $elem */
        $elem = ElementSupplierContactTable::query()
            ->setSelect(['ID', 'NAME', 'EMAIL'])
            ->setFilter(['EMAIL.VALUE' => $contact['email']])
            ->fetchObject();

        if (! $elem) {
            $el = new CIBlockElement();
            $arLoadProductArray = [
                //            "IBLOCK_SECTION_ID" => false,          // элемент лежит в корне раздела
                "IBLOCK_ID" => REQUEST_SUPPLIER_CONTACT_IBLOCK,
                "NAME"      => $contact['name'],
                "ACTIVE"    => "Y",            // активен
            ];
            if ($elemId = $el->Add($arLoadProductArray)) {
                $elem = ElementSupplierContactTable::wakeUpObject($elemId);
            }
        }
        $elem
            ->setEmail($contact['email'])
            ->setName($contact['name']);
        if ($supplierId) {
            $elem->setSupplier($supplierId);
        }
        $elem->save();

        return $elem;
    }

}

class tmp
{
    protected $arSelect = [
        "ID", "NAME", 'TIMESTAMP_X', 'REQUEST_ID', 'PAYMENT_ORDER', 'DELIVERY_TIME', 'INCOTERMS',
        "EMAIL", 'COMMENT', "CONTACT", 'CURRENCY', 'STATUS', 'EVENT', 'SUPPLIER_COMMENT',
        'IS_BLOCKED', "SPECIFICATION", "SUPPLIER", 'SESSION_ID'
    ];
    protected $arFilter = ["ACTIVE" => "Y"];
    protected $arOrder  = ['ID'];

    public function getQuery($query, $n, \CRestServer $server)
    {
        $result = [];
        $arSelect = $this->arSelect;
        $arFilter = $this->arFilter;
        $arOrder = $this->arOrder;;

//        $arFilter['SUPPLIER.VALUE'] = '103';
//        $arFilter['CONTACT.VALUE'] = '105';
        if (! empty($query['id'])) {
//            $arFilter['ID'] = $query['internal_id'];
            $arFilter['REQUEST_ID.VALUE'] = $query['id'];
        }

        /** @var \Bitrix\Iblock\Elements\EO_ElementRequest_Collection $requests */
        $requests = ElementRequestTable::query()
            ->setSelect($arSelect)
            ->setFilter($arFilter)
            ->fetchCollection();

        foreach ($requests as $request) {
            //            $result[$request->getId()] =
            $result[] = $this->getRequestValues($request);
        }

        return $result;
    }

    public function getRequest($query)
    {
        $arSelect = $this->arSelect;
        $arFilter = $this->arFilter;

        $arFilter['REQUEST_ID.VALUE'] = $query['id'];

        /** @var \Bitrix\Iblock\Elements\EO_ElementRequest $request */
        $request = ElementRequestTable::query()
            ->setSelect($arSelect)
            ->setFilter($arFilter)
            ->fetchObject();

//        $result = $this->getRequestValues($request);

        return $request;
    }

    protected function getSpecification(\Bitrix\Iblock\Elements\EO_ElementRequest $request)
    {
        /** @var \Bitrix\Iblock\Elements\EO_ElementRequestSpecification_Collection $specification */
        $specification = ElementRequestSpecificationTable::getList([
            'select' => [
                'ID', 'NAME', 'SKU', 'QUANTITY_R', 'SUPPLIER_QUANTITY',
                'UNIT_MEASURE', 'SUPPLIER_UNIT', 'SUPPLIER_PRICE_UNIT', 'DESC_ID',
                'DELIVERY_TIME', 'INCOTERMS', 'REPLACEMENT', 'COMMENT', 'SUPPLIER_COMMENT'
            ],
            'filter' => ['ID' => $request->getSpecification()->getValueList()]
        ])->fetchCollection();

        $spec = array_map(function ($item) {
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
        }, $specification->getAll());

        return $spec;
    }

    protected function getSupplier(\Bitrix\Iblock\Elements\EO_ElementRequest $request)
    {
        /** @var \Bitrix\Iblock\Elements\EO_ElementSupplier_Collection $supplier */
        $supplier = ElementSupplierTable::getList([
            'select' => ['ID', 'NAME', 'ID_ONE_C', 'KEY', 'CONTACTS', 'EXPIRY_DATE'],
            'filter' => ['ID' => $request->getSupplier()->getValue()]
        ])->fetchCollection();
        $suppl = array_map(function (\Bitrix\Iblock\Elements\EO_ElementSupplier $item) {
            $contacts = array_map(function ($contact) {
                return $this->getContactById($contact);
            }, $item->getContacts()->getValueList());

            /** @var \Bitrix\Iblock\Elements\EO_ElementSupplier $item */
            return [
                'id'          => $item->getIdOneC()->getValue(),
                'internal_id' => $item->getId(),
                'name'        => $item->getName(),
                'key'         => $item->getKey()->getValue(),
                'key_expiry'  => (new DateTime($item->getExpiryDate()->getValue()))->format('U'),
                'contacts'    => $contacts,
            ];
        }, $supplier->getAll());

        $suppl = $suppl[0] ?? [];

        return $suppl;
    }

    protected function getContact(\Bitrix\Iblock\Elements\EO_ElementRequest $request, Iblock $obContact)
    {
        $result = [];
        /** @var \Bitrix\Iblock\Elements\EO_ElementSupplierContact_Collection $contact */
        $contact = $obContact
            ->getEntityDataClass()::getList([
                'select' => ['ID', 'NAME', 'EMAIL'],
                'filter' => ['ID' => $request->getContact()->getValue()]
            ])->fetchCollection();

        $cont = $contact->getAll()[0];
        if ($cont) {
            $result = [
                'id'    => $cont->getId(),
                'name'  => $cont->getName(),
                'email' => $cont->getEmail()->getValue(),
            ];
        }

        return $result;
    }

    public function getContactById(int $id)
    {
        $result = null;
        $contact = ElementSupplierContactTable::getByPrimary($id, [
                'select' => ['ID', 'NAME', 'EMAIL']]
        )->fetchObject();

        if ($contact) {
            $result = ['name' => $contact->getName(), 'email' => $contact->getEmail()->getValue()];
        }

        return $result;
    }

    public function updateSupplierKey(array $data)
    {
        $supplier = null;

        if (\Bitrix\Main\Loader::includeModule('iblock')) {
            /** @var EO_ElementSupplier $supplier */
            $supplier = \Bitrix\Iblock\Elements\ElementSupplierTable::getList([
                'select' => ['ID', 'NAME', 'ID_ONE_C', 'KEY', 'CONTACTS', 'EXPIRY_DATE'],
                'filter' => ['ID_ONE_C.VALUE' => $data['supplier_id']]
            ])->fetchObject();

            $supplier
                ->setKey($data['key'])
                ->setExpiryDate((new DateTime('@' . $data['key_expiry']))->format('Y-m-d'));
            $supplier->save();
        }

        return $supplier;
    }

    public function getRequestValues(EO_ElementRequest $request)
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
            //                "email"         => $request->getEmail()->getValue(),
            "event"         => $request->getEvent()->getValue(),
            "comment_s"     => $request->getSupplierComment()->getValue(),
            "contact"       => $this->getContactById($request->getContact()->getValue()),
            "supplier"      => $this->getSupplier($request),
            "specification" => $this->getSpecification($request),
        ];
    }
}