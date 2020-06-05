<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Zkr\Logs\Inquiry\AdminInterface\LogListHelper as LogList;
use Zkr\Logs\Inquiry\AdminInterface\LogEditHelper as LogEdit;

if (! Loader::includeModule('digitalwand.admin_helper')
    || ! Loader::includeModule('zkr.logs')
) {
    return;
}

Loc::loadMessages(__FILE__);

return [
    [
        'parent_menu' => 'global_menu_content',
        'sort'        => 150,
        'icon'        => 'fileman_sticker_icon',
        'page_icon'   => 'fileman_sticker_icon',
        'text'        => "Логирование",
        'id'          => "zkr_logs",
        "items_id"    => "zkr_logs",
        "items"       => [
            [
                "text"     => "Лог просмотра заявок",
                "url"      => LogList::getUrl(),
                "more_url" => LogEdit::getUrl(),
                "title"    => "Лог просмотра заявок",
            ],
        ]
    ]
];