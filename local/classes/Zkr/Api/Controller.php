<?php


namespace Zkr\Api;

use Zkr\Supplier\Price\Request;

class Controller
{
    public static function OnRestServiceBuildDescription()
    {
        return [
            Request::SCOPE => [
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
