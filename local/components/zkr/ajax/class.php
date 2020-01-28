<?php


namespace Zkr\Components;

if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Iblock\Elements\EO_ElementSupplier;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\Contract\Controllerable;
use CBitrixComponent;

class CustomAjax extends CBitrixComponent implements Controllerable
{
    public function configureActions()
    {
        // Предустановленные фильтры находятся в папке /bitrix/modules/main/lib/engine/actionfilter/
        return [
            // Ajax-метод
            'getKey' => [
                'prefilters'  => [
                    new ActionFilter\HttpMethod([ActionFilter\HttpMethod::METHOD_GET, ActionFilter\HttpMethod::METHOD_POST]),
                    new ActionFilter\Csrf(),
                    //                    new ActionFilter\Authentication(),
                ],
                'postfilters' => [],
            ]
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
        $params = [
            'supplier_id' => '001/001',
            'key'         => $this->randString(15),
            'key_expiry'  => time(),
            'email'       => 'test@test.com',
            'request_id'  => "00001-0002"
        ];

        $request = new \Zkr\Api\Request();
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
}
