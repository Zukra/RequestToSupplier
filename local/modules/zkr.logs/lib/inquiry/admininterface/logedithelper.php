<?php

namespace Zkr\Logs\Inquiry\AdminInterface;

use Bitrix\Main\Localization\Loc;
use DigitalWand\AdminHelper\Helper\AdminEditHelper;

Loc::loadMessages(__FILE__);

/**
 * Хелпер описывает интерфейс, выводящий форму редактирования новости.
 *
 * {@inheritdoc}
 */
class LogEditHelper extends AdminEditHelper
{
    protected static $model = '\Zkr\Logs\Inquiry\LogTable';

    /**
     * @inheritdoc
     */
    public function setTitle($title)
    {
        if (! empty($this->data)) {
            $title = 'Редактировать';
        } else {
            $title = 'Создать';
        }
        parent::setTitle($title);
    }
}
