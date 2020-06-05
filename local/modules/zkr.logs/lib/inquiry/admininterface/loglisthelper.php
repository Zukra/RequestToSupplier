<?php

namespace Zkr\Logs\Inquiry\AdminInterface;

use DigitalWand\AdminHelper\Helper\AdminListHelper;

/**
 * Хелпер описывает интерфейс, выводящий список новостей.
 *
 * {@inheritdoc}
 */
class LogListHelper extends AdminListHelper
{
	protected static $model = '\Zkr\Logs\Inquiry\LogTable';
}