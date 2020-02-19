<?php


namespace Zkr\Api;

use Zkr\Supplier\Price\Request;

class Controller
{
    public static function OnRestServiceBuildDescription()
    {
        return [
            Request::SCOPE => [
                Request::SCOPE . '.test'    => [
                    'callback' => [__CLASS__, 'test'],
                    'options'  => [],
                ],
                Request::SCOPE . '.listnav' => [
                    'callback' => [__CLASS__, 'getListNav'],
                    'options'  => [],
                ],
                Request::SCOPE . '.list'    => [
                    'callback' => [__CLASS__, 'getList'],
                    'options'  => [],
                ],
                Request::SCOPE . '.getAll'  => [
                    'callback' => [new Request(), 'getAll'],
                    'options'  => [],
                ],
                Request::SCOPE . '.get'     => [
                    'callback' => [new Request(), 'get'],
                    'options'  => [],
                ],
                Request::SCOPE . '.update'  => [
                    'callback' => [new Request(), 'update'],
                    'options'  => [],
                ],
            ],
        ];
    }
}
