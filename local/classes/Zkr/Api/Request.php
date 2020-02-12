<?php


namespace Zkr\Api;


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

class Request
{
    public const WAIT_REPLY     = 'Waiting for a reply';
    public const BLOCKED_UPDATE = 'Blocked for update';
    public const SENT           = 'Sent';

    public const SCOPE = 'request';

    protected $arSelect = [
        "ID", "NAME", 'TIMESTAMP_X', 'REQUEST_ID', 'PAYMENT_ORDER', 'DELIVERY_TIME', 'INCOTERMS',
        "EMAIL", 'COMMENT', "CONTACT", 'CURRENCY', 'STATUS', 'EVENT', 'SUPPLIER_COMMENT',
        'IS_BLOCKED',
        "SPECIFICATION", "SUPPLIER",
    ];
    protected $arFilter = ["ACTIVE" => "Y"];
    protected $arOrder  = ['ID'];

    public function get($query, $n, \CRestServer $server)
    {
        $result = [];
        if (! empty($query['id'])) {
            $request = $this->getRequest($query);
            $result = $this->getRequestValues($request);
        } else {
//            $result = $this->getQuery($query, $n, $server);
        }

        //        return ['query' => $query, 'result' => $result, 'n' => $n];
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
                        $supplier = $this->getSupplier($elem);
                        $result[] = [
                            'status'     => 0,
                            'errors'     => 'Request to update is blocked by supplier',
                            "request_id" => $elem->getRequestId()->getValue(),
                            "contact"    => $this->getContactById($elem->getContact()->getValue()),
                            "supplier"   => ['id' => $supplier['id'], 'name' => $supplier['name']],
                        ];
                    } else {
                        $elem = $this->updateElem($elem->getId(), $datum);
                        $result[] = [
                            'status'     => 1,
                            'message'    => 'updated',
                            "request_id" => $elem->getRequestId()->getValue(),
                        ];
                    }
                } else {
                    $elem = $this->addElem($datum);
                    $elem = $this->updateElem($elem->getId(), $datum);
                    $result[] = [
                        'status'     => 1,
                        'message'    => 'added',
                        "request_id" => $elem->getRequestId()->getValue(),
                    ];
                }
            }
        } else {
            $result = ['status' => 0];
        }

        return ['result' => $result];
    }

    public function getAll($query, $n, \CRestServer $server)
    {
        $result = $this->getQuery($query, $n, $server);

        return ['query' => $query, 'result' => $result, 'n' => $n];
    }

    public function getList($query, $n, \CRestServer $server)
    {
        $result = [];

        /*$res = \Bitrix\Main\UserTable::getList(
            [
                'filter'      => $query['filter'] ?: [],
                'select'      => $query['select'] ?: ['*'],
                'order'       => $query['order'] ?: ['ID' => 'ASC'],
                'limit'       => 5,
                //                'limit'       => $navData['limit'],
                //                'offset'      => $navData['offset'],
                'count_total' => true,
            ]
        );

        while ($user = $res->fetch()) {
            $result[] = $user;
        }*/

        $arSelect = ["ID", "NAME"];
        $arFilter = ["IBLOCK_ID" => REQUEST_IBLOCK, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y"];
        $res = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
        while ($ob = $res->Fetch()) {
            $result[] = $ob;
        }

        return ['query' => $query, 'result' => $result, 'n' => $n];
    }

    public function getListNav($query, $nav, \CRestServer $server)
    {
        // /rest/api.test.list?order[ID]=ASC&filter[<ID]=1000&select[]=ID&select[]=NAME&start=200
        $navData = static::getNavData($nav, true);

        $res = \Bitrix\Main\UserTable::getList(
            [
                'filter'      => $query['filter'] ?: [],
                'select'      => $query['select'] ?: ['*'],
                'order'       => $query['order'] ?: ['ID' => 'ASC'],
                'limit'       => $navData['limit'],
                'offset'      => $navData['offset'],
                'count_total' => true,
            ]
        );

        $result = [];
        while ($user = $res->fetch()) {
            $result[] = $user;
        }

        /** @var \Bitrix\Main\ORM\Query\Result $res */
        return static::setNavData($result, [
                "count"  => $res->getCount(),
                "offset" => $navData['offset']
            ]
        );
    }

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

    public function getQueryOld($query, $n, \CRestServer $server)
    {
        $result = [];
        $arSelect = $this->arSelect;
        $arFilter = $this->arFilter;
        $arOrder = $this->arOrder;;

        //        $arFilter['SUPPLIER.VALUE'] = '103';
        //        $arFilter['CONTACT.VALUE'] = '105';
        if (! empty($query['id'])) {
            $arFilter['ID'] = $query['id'];
        }

        $obRequest = Iblock::wakeUp(REQUEST_IBLOCK);
        $obSpecification = Iblock::wakeUp(REQUEST_SPECIFICATION_IBLOCK);
        $obSupplier = Iblock::wakeUp(REQUEST_SUPPLIER_IBLOCK);
        $obContact = Iblock::wakeUp(REQUEST_SUPPLIER_CONTACT_IBLOCK);

        /** @var \Bitrix\Iblock\Elements\EO_ElementRequest_Collection $requests */
        $requests = $obRequest->getEntityDataClass()::getList([
            'select' => $arSelect, 'filter' => $arFilter, 'order' => $arOrder
        ])->fetchCollection();

        foreach ($requests as $request) {
            //            $result[$request->getId()] = [
            $result[] = [
                //                'id'            => $request->getId(),
                'id'            => $request->getRequestId()->getValue(),
                'payment_order' => $request->getPaymentOrder()->getValue(),
                'delivery_time' => $request->getDeliveryTime()->getValue(),
                'incoterms'     => $request->getIncoterms()->getValue(),
                'currency'      => $request->getCurrency()->getValue(),
                'status'        => $request->getStatus()->getValue(),
                'comment'       => $request->getComment()->getValue(),
                "supplier"      => $this->getSupplier($request, $obSupplier),
                "specification" => $this->getSpecification($request, $obSpecification),
                "contact"       => $this->getContact($request, $obContact),
            ];
        }

        return $result;
    }

    protected function getSpecification(\Bitrix\Iblock\Elements\EO_ElementRequest $request)
    {
        /** @var \Bitrix\Iblock\Elements\EO_ElementRequestSpecification_Collection $specification */
        $specification = ElementRequestSpecificationTable::getList([
            'select' => [
                'ID', 'NAME', 'SKU', 'QUANTITY_R', 'SUPPLIER_QUANTITY',
                'UNIT_MEASURE', 'SUPPLIER_UNIT', 'SUPPLIER_PRICE_UNIT',
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
                'comment'       => $item->getComment()->getValue(),
                'quantity_r'    => $item->getQuantityR()->getValue(),
                'unit_measure'  => $item->getUnitMeasure()->getValue(),
                'quantity_s'    => $item->getSupplierQuantity()->getValue(),
                'unit_s'        => $item->getSupplierUnit()->getValue(),
                'price_s'       => $item->getSupplierPriceUnit()->getValue(),
                'delivery_time' => $item->getDeliveryTime()->getValue(),
                'incoterms'     => $item->getIncoterms()->getValue(),
                'replacement'   => $item->getReplacement()->getValue(),
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

    /**
     * @param $data
     * @return EO_ElementRequest|null
     */
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
            $elem = EO_ElementRequest::wakeUp($elemId);
        }

        return $elem;
    }

    public function addElemOld($data)
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
            $supplier = $this->checkSupplier($data['supplier']);

            /** @var EO_ElementRequest $elem */
            $elem = EO_ElementRequest::wakeUp($elemId);
            $elem
                ->setRequestId($data['id'])
                ->setPaymentOrder($data['payment_order'])
                ->setDeliveryTime($data['delivery_time'])
                ->setIncoterms($data['incoterms'])
                ->setCurrency($data['currency'])
//                ->setStatus($data['status'])
                ->setComment($data['comment'])
                ->setEmail($data['contact']['email'])
                ->setContact(static::getSupplierContact($data['contact'])->getId())
                ->setSupplier($supplier->getId());

//            $this->setSpecification($elem);
//            foreach ($data['specification'] as $spec) {
//                $elem->addToSpecification(new PropertyValue($spec['sku']));
//            }
            $res = $elem->save();
        }/* else {
            echo "Error: " . $el->LAST_ERROR;
        }*/

        return $elem;
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

    /**
     * @return \Bitrix\Main\ORM\Data\Result
     */
    public function deleteElem($elem)
    {
        return $elem->delete();
    }

    /**
     * @param  array     $contact
     * @param  int|bool  $supplierId
     * @return EO_ElementSupplierContact
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
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
                $elem = EO_ElementSupplierContact::wakeUp($elemId);
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
                $supplier = EO_ElementSupplier::wakeUp($supplierId);
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

    public function updateElem($elemId = null, $data = null)
    {
        $elem = null;
        if ($elemId && $data) {
            /** @var EO_ElementRequest $elem */
            $elem = EO_ElementRequest::wakeUp($elemId);
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
                ->setSupplier($supplier->getId());

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
                    $specification = EO_ElementRequestSpecification::wakeUp($id);
                }
            }
            $specification
                ->setTimestampX(new \Bitrix\Main\Type\DateTime())
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
