<?

if(file_exists($_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php')){
  include_once($_SERVER['DOCUMENT_ROOT'] . '/local/vendor/autoload.php');
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;


Loc::loadMessages(__FILE__);

//подключение глобально файла перевода
if (file_exists($_SERVER["DOCUMENT_ROOT"] . BX_PERSONAL_ROOT . "/php_interface/lang/" . LANGUAGE_ID . "/lang.php")) {
    Loc::loadLanguageFile($_SERVER["DOCUMENT_ROOT"] . BX_PERSONAL_ROOT . "/php_interface/lang/" . LANGUAGE_ID . "/lang.php");
}
require_once 'include/constants.php';

try {
    Loader::registerAutoLoadClasses(null, [
            // "OnBeforeProlog" => "/local/php_interface/include/events/OnBeforeProlog.php",
//            "Emk\\RestAPI" => "/local/classes/Emk/RestAPI.php",
    ]);
} catch (LoaderException $e) {
    $APPLICATION->throwException($e->getMessage());
}

require_once 'include/handlers.php';
