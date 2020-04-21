<?php


namespace Zkr;


use Bitrix\Main\Context;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

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

    public static function sendHttp($baseUrl = '', $uri = '', $params = [])
    {
        $client = new Client([
            'base_uri' => $baseUrl,
            'timeout'  => 10.0,
            //    'headers'  => ['Content-Type' => 'application/json', 'Accept' => 'application/json',],
        ]);
        try {
            $response = $client->request('POST', $uri, ['json' => $params]);
            $body = $response->getBody();
        } catch (RequestException $e) {
//            echo Psr7\str($e->getRequest()) . "\n";
//            $body = $e->getRequest()->getBody();
            if ($e->hasResponse()) {
                echo Psr7\str($e->getResponse());
                $body = $e->getResponse()->getBody();
                if (in_array($e->getResponse()->getStatusCode(), [401, 404])) {
                    $body = json_encode(['status' => 0, 'errors' => $e->getResponse()->getReasonPhrase()]);
                }
            } else {
                $body = json_encode(['status' => 0, 'errors' => 'Response is null']);
            }
        }
        $data = \GuzzleHttp\json_decode((string)$body, true);

        return $data;
    }
}
