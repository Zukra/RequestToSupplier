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
        /*        $url = 'http://API:1111m@138.201.231.186:8080/testnew2/hs/1c/api/v1/request/getKey';
                $params = [
                    "email"      => "test@test.com",
                    "request_id" => "222222"
                ];*/
//        dump($url, json_encode($params));

        $client = new Client([
            'base_uri' => $baseUrl,
            'timeout'  => 5.0,
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

//curl -i -v -H "Content-Type: application/json" -d "{\"email\":\"test@test.com\",\"request_id\":\"11111\"}" http://API:1111m@srv-1c.emk.loc:8080//testnew2/hs/1c/api/v1/request/getKey
//curl -i -v -H "Content-Type: application/json" -d "{\"email\":\"test@test.com\",\"request_id\":\"11111\"}" http://API:1111m@138.201.231.186:8080/testnew2/hs/1c/api/v1/request/getKey
//curl -i -v -H "Content-Type: application/json" -d "{\"vcard_id\":\"11\", \"token\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL3RzdC5wZXJzb2dyYW0uY29tL2FwaS92MS9hdXRoL2xvZ2luIiwiaWF0IjoxNTgyMzU3MDMxLCJuYmYiOjE1ODIzNTcwMzEsImp0aSI6IkhzMkZlQTNKVzJqMTB5ZFQiLCJzdWIiOjIsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.vWT51sqLd0nJyeJrgV7wEVhPmidYIv9-WK-sgaDe0Yk\"}" https://tst.persogram.com/api/v1/card
//$params = http_build_query($params);
        /*$params = json_encode($params);
        $url = 'http://API:1111m@138.201.231.186:8080/testnew2/hs/1c/api/v1/request/getKey';
        $url = 'http://API:1111m@srv-1c.emk.loc:8080/testnew2/hs/1c/api/v1/request/getKey';
        //dump($url, $params, $_SERVER);
        dump($url, $params);
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST           => 1,
            //    CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HEADER         => 0,
            CURLOPT_POSTFIELDS     => $params,
            CURLOPT_HTTPHEADER     => [
        //        "REMOTE_ADDR: 188.120.253.16",
        //        "HTTP_X_FORWARDED_FOR: 188.120.253.16",
        //        'Content-Type: text/plain',
        'Content-Type: application/json',
        //        'Content-type: application/x-www-form-urlencoded',
        //        'Content-Length: ' . strlen($params),
        //                'Accept: application/json',
        //        'Connection: Keep-Alive',
            ],
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_TIMEOUT        => 15000,
        ]);
        if (! $res = curl_exec($ch)) {
            dump('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
        } else {
            dump($res);
        }*/
    }
}
