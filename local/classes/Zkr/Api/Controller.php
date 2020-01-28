<?php


namespace Zkr\Api;

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
                    'callback' => [new \Zkr\Api\Request(), 'getAll'],
                    'options'  => [],
                ],
                Request::SCOPE . '.get'     => [
                    'callback' => [new \Zkr\Api\Request(), 'get'],
                    'options'  => [],
                ],
                Request::SCOPE . '.update'     => [
                    'callback' => [new \Zkr\Api\Request(), 'update'],
                    'options'  => [],
                ],
            ],
        ];
    }
}
