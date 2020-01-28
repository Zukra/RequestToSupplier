<?

use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

$eventManager->addEventHandlerCompatible('rest', 'OnRestServiceBuildDescription', ['Zkr\Api\Controller', 'OnRestServiceBuildDescription']);
