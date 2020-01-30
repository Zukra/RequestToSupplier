<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$cache_id = serialize([$params]);
$cache = new CPHPCache;
if ($cache->StartDataCache($arParams["CACHE_TIME"], $cache_id, "/" . SITE_ID . $this->GetRelativePath())) {
    $arResult = $arParams;

    // Подключение шаблона компонента
//    $this->IncludeComponentTemplate();

    $templateCachedData = $this->GetTemplateCachedData();
    $cache->EndDataCache([
            "arResult"           => $arResult,
            "templateCachedData" => $templateCachedData
        ]
    );
} else {
    extract($cache->GetVars());
    $this->SetTemplateCachedData($templateCachedData);
}

$this->IncludeComponentTemplate();
