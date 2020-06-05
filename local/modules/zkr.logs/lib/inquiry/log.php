<?php

namespace Zkr\Logs\Inquiry;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class LogTable extends DataManager
{
    public static function getTableName()
    {
        return 'b_zkr_logs_inquiry';
    }

    public static function getMap()
    {
        return [
            new IntegerField('ID', [
                'autocomplete' => true,
                'primary'      => true,
                'title'        => 'Id',
            ]),
            new IntegerField('INQUIRY_ID', [
                'required' => true,
                'title'    => 'Заявка',
            ]),
            new IntegerField('SUPPLIER_ID', [
                'required' => true,
                'title'    => 'Поставщик',
            ]),
            new DatetimeField('DATE_MODIFY', [
                'required' => true,
                'title'    => 'Дата',
            ]),
        ];
    }
}
