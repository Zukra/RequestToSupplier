<?php


namespace Zkr\Components;

if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Iblock\Elements\ElementRequestTable;
use Bitrix\Iblock\Elements\ElementSupplierContactTable;
use Bitrix\Iblock\Elements\ElementSupplierTable;
use Bitrix\Iblock\Elements\EO_ElementRequest;
use Bitrix\Iblock\Elements\EO_ElementSupplier;
use Bitrix\Iblock\Elements\EO_ElementSupplierContact;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use CBitrixComponent;
use CIBlockElement;
use Zkr\Supplier\Price\Request;

class CustomAjax extends CBitrixComponent implements Controllerable
{
    public function configureActions()
    {
        Loader::includeModule('iblock');

        // Предустановленные фильтры находятся в папке /bitrix/modules/main/lib/engine/actionfilter/
        return [
            // Ajax-метод
            'getKey'                   => [
                'prefilters'  => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST]),
                    //                    new ActionFilter\Csrf(),
                    //                    new ActionFilter\Authentication(),
                ],
                '-prefilters' => [
                    ActionFilter\Authentication::class
                ],
                'postfilters' => [],
            ],
            'updateSupplierKey'        => [
                'prefilters'  => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST]),
                    //                    new ActionFilter\Csrf(),
                    //                    new ActionFilter\Authentication(),
                ],
                '-prefilters' => [
                    ActionFilter\Authentication::class
                ],
                'postfilters' => [],
            ],
            'getNewSupplierKey'        => [
                'prefilters'  => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST]),
                    //                    new ActionFilter\Csrf(),
                    //                    new ActionFilter\Authentication(),
                ],
                '-prefilters' => [
                    ActionFilter\Authentication::class
                ],
                'postfilters' => [],
            ],
            'updateRequest'            => [
                'prefilters'  => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST]),
                ],
                '-prefilters' => [
                    ActionFilter\Authentication::class
                ],
                'postfilters' => [],
            ],
            'updateSpecification'      => [
                'prefilters'  => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST]),
                ],
                '-prefilters' => [
                    ActionFilter\Authentication::class
                ],
                'postfilters' => [],
            ],
            'sendRequestData'          => [
                'prefilters'  => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST]),
                ],
                '-prefilters' => [
                    ActionFilter\Authentication::class
                ],
                'postfilters' => [],
            ],
            'addRequestContact'        => [
                'prefilters'  => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST]),
                ],
                '-prefilters' => [
                    ActionFilter\Authentication::class
                ],
                'postfilters' => [],
            ],
            'setRequestBlockingStatus' => [
                'prefilters'  => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST]),
                ],
                '-prefilters' => [
                    ActionFilter\Authentication::class
                ],
                'postfilters' => [],
            ],
            'requestCheckBlocked'      => [
                'prefilters'  => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST]),
                ],
                '-prefilters' => [
                    ActionFilter\Authentication::class
                ],
                'postfilters' => [],
            ],
        ];
    }

    function executeComponent()
    {
    }

    /**
     * @return array
     */
    public function getKeyAction($params)
    {
        $data = ['status' => 0, 'errors' => 'errror!'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $params['url']);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true); // remove body
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        $head = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return $head;
//        return $data;
    }

    public function updateSupplierKeyAction($params)
    {
        $params["key_expiry"] = time();

        $data = ['status' => 0, 'errors' => 'error!'];

        $request = new \Zkr\Supplier\Price\Request();
        /** @var EO_ElementSupplier $supplier */
        $supplier = $request->updateSupplierKey($params);
        if ($supplier) {
            $data = [
                "status"      => 1,
                "supplier_id" => $supplier->getIdOneC()->getValue(),    // id поставщика в 1С
                "key"         => $supplier->getKey()->getValue(),   // сгенерированный ключ
                "key_expiry"  => $supplier->getExpiryDate()->getValue(), // дата окончания действия ключа Unix-формат
                "email"       => $params['email'],             // contact email (email ответственного лица) или ID сформированного к отправке письма
                'request_id'  => $params['request_id']
            ];
        }

        return $data;
    }

    public function updateRequestAction($params)
    {
        $props = [
            $params['prop']['code'] => $params['prop']['value'],
            'IS_BLOCKED'            => REQUEST_IS_BLOCKED_ID,
            'STATUS'                => Request::BLOCKED_UPDATE,
            'EVENT'                 => Request::BLOCKED_UPDATE,
            'SESSION_ID'            => bitrix_sessid()
        ];
        if ($params['prop']['code'] == 'CONTACT') {
            /** @var EO_ElementSupplierContact $contact */
            $contact = ElementSupplierContactTable::getByPrimary(
                $params['prop']['value'],
                ['select' => ['ID', 'NAME', 'EMAIL']]
            )->fetchObject();
            $props['EMAIL'] = $contact->getEmail()->getValue();
        }
        $arLoadProductArray = ['TIMESTAMP_X' => new \Bitrix\Main\Type\DateTime(),];
        $el = new CIBlockElement;
        $el->Update($params['request_id'], $arLoadProductArray);
        CIBlockElement::SetPropertyValuesEx($params['request_id'], false, $props);
    }

    /**
     * Проверка на блокировку заявки.
     * Если у заявки статус заблокирована и id сессии заявки не совпадает с текущей, то заявка заблокирована
     * @param $params
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function requestCheckBlockedAction($params)
    {
        $result = ['status' => 0, 'errors' => 'error!'];
        if ($params['request_id']) {
            /** @var EO_ElementRequest $request */
            $request = ElementRequestTable::getByPrimary(
                $params['request_id'],
                ['select' => ['ID', 'IS_BLOCKED', 'SESSION_ID']]
            )->fetchObject();

            $blocked = $request->getIsBlocked()->getValue()
                       && $request->getSessionId()->getValue() !== bitrix_sessid();
            $result = [
                'status'     => 1,
                'blocked'    => $blocked ? 1 : 0,
                'is_blocked' => $request->getIsBlocked()->getValue(),
                'id'         => $request->getId(),
                'session_id' => $request->getSessionId()->getValue(),
            ];
        }

        return $result;
    }

    public function updateSpecificationAction($params)
    {
        $props = [
            $params['prop']['code'] => $params['prop']['value'],
        ];

        if ($params['prop']['code'] == 'REPLACEMENT' && $params['prop']['value']) {
            $props['REPLACEMENT'] = REQUEST_SPECIFICATION_IS_REPLACEMENT;
        }

        $arLoadProductArray = ['TIMESTAMP_X' => new \Bitrix\Main\Type\DateTime(),];
        $el = new CIBlockElement;
        $el->Update($params['spec_id'], $arLoadProductArray);
        $el->Update($params['request_id'], $arLoadProductArray);
        CIBlockElement::SetPropertyValuesEx($params['spec_id'], false, $props);
        CIBlockElement::SetPropertyValuesEx($params['request_id'], false, [
            'IS_BLOCKED' => REQUEST_IS_BLOCKED_ID,
            'STATUS'     => Request::BLOCKED_UPDATE,
            'EVENT'      => Request::BLOCKED_UPDATE,
            'SESSION_ID' => bitrix_sessid()
        ]);
    }

    public function addRequestContactAction($params)
    {
        $contact = Request::getSupplierContact($params, $params['supplier_id']);
        $parameters = [
            'request_id' => $params['request_id'],
            'prop'       => [
                'code'  => 'CONTACT',
                'value' => $contact->getId(),
            ]
        ];
        $this->updateRequestAction($parameters);

//        $params['request_id']
//        $params['supplier_id']

        $contactProps = [];
        /** @var EO_ElementSupplier $supplier */
        $supplier = ElementSupplierTable::query()
            ->setSelect(['ID', 'NAME', 'CONTACTS'])
            ->setFilter(['ID' => $params['supplier_id']])
            ->fetchObject();

        foreach ($supplier->getContacts() as $item) {
            $contactProps[] = ["VALUE" => $item->getValue()];
        }
        $contactProps[] = ["VALUE" => $contact->getId()];

        CIBlockElement::SetPropertyValuesEx($params['supplier_id'], REQUEST_SUPPLIER_IBLOCK, ["CONTACTS" => $contactProps]);

        return [
            "status"     => 1,
            "contact_id" => $contact->getId(),    // id поставщика в 1С
        ];
    }

    public function sendRequestDataAction($params)
    {
        $props = [
            'IS_BLOCKED' => false,
            'STATUS'     => Request::SENT,
            'EVENT'      => Request::SENT
        ];

        $el = new CIBlockElement;
        $el->Update($params['request_id'], ['TIMESTAMP_X' => new \Bitrix\Main\Type\DateTime()]);
        CIBlockElement::SetPropertyValuesEx($params['request_id'], false, $props);
    }

    public function getNewSupplierKeyAction($params)
    {
        $data = [
            "status"      => 1,
            "supplier_id" => $params['id'],    // id поставщика в 1С
            "key"         => substr(md5(mt_rand()), 0, 15),// сгенерированный ключ
            "key_expiry"  => new DateTime(null, 'Y-m-d'), // дата окончания действия ключа Unix-формат
            "email"       => $params['email'],             // contact email (email ответственного лица) или ID сформированного к отправке письма
            'request_id'  => $params['request_id'] ?? ''
        ];

        return $data;
    }

    public function setRequestBlockingStatusAction($params)
    {
        if ($params['request_id']) {
            $props = [
                'IS_BLOCKED' => $params['value'] > 0 ? REQUEST_IS_BLOCKED_ID : false,
                'STATUS'     => $params['value'] > 0 ? Request::BLOCKED_UPDATE : Request::WAIT_REPLY,
                'EVENT'      => $params['value'] > 0 ? Request::BLOCKED_UPDATE : Request::WAIT_REPLY,
            ];

            $arLoadProductArray = ['TIMESTAMP_X' => new \Bitrix\Main\Type\DateTime(),];
            $el = new CIBlockElement;
            $el->Update($params['request_id'], $arLoadProductArray);
            CIBlockElement::SetPropertyValuesEx($params['request_id'], false, $props);
        }
    }
}
