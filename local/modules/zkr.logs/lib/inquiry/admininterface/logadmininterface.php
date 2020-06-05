<?php

namespace Zkr\Logs\Inquiry\AdminInterface;

use Bitrix\Main\Localization\Loc;
use DigitalWand\AdminHelper\Helper\AdminInterface;
use DigitalWand\AdminHelper\Widget\DateTimeWidget;
use DigitalWand\AdminHelper\Widget\IblockElementWidget;
use DigitalWand\AdminHelper\Widget\NumberWidget;

Loc::loadMessages(__FILE__);

/**
 * Описание интерфейса (табок и полей) админки новостей.
 *
 * {@inheritdoc}
 */
class LogAdminInterface extends AdminInterface
{
    /**
     * @inheritdoc
     */
    public function fields()
    {
        return [
            'MAIN' => [
                'NAME'   => 'Лог просмотра заявок',
                'FIELDS' => [
                    'ID'          => [
                        'WIDGET'           => new NumberWidget(),
                        'READONLY'         => true,
                        'FILTER'           => true,
                        'HIDE_WHEN_CREATE' => true
                    ],
                    'INQUIRY_ID'  => [
                        'WIDGET'       => new IblockElementWidget(),
                        'FILTER'       => true,
                        'IBLOCK_conID' => REQUEST_IBLOCK,
                    ],
                    'SUPPLIER_ID' => [
                        'WIDGET'    => new IblockElementWidget(),
                        'FILTER'    => true,
                        'IBLOCK_ID' => REQUEST_SUPPLIER_IBLOCK,
                    ],
                    'DATE_MODIFY' => [
                        'WIDGET'           => new DateTimeWidget(),
                        'READONLY'         => false,
                        'HIDE_WHEN_CREATE' => false
                    ],
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function helpers()
    {
        return [
            '\Zkr\Logs\Inquiry\AdminInterface\LogListHelper' => [
                'BUTTONS' => [
                    'LIST_CREATE_NEW' => [
                        'TEXT' => 'Добавить',
                    ],
                ]
            ],
            '\Zkr\Logs\Inquiry\AdminInterface\LogEditHelper' => [
                'BUTTONS' => [
                    'ADD_ELEMENT'    => [
                        'TEXT' => 'Добавить'
                    ],
                    'DELETE_ELEMENT' => [
                        'TEXT' => 'Удалить'
                    ]
                ]
            ]
        ];
    }
}
