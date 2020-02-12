<? if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

/** @var \Bitrix\Iblock\Elements\EO_ElementSupplierContact $contacts */
$contacts = $arResult['CONTACTS'];
?>
<div class="supplier-detail">
    <? /*<h3><?= $arResult["NAME"] ?></h3>*/ ?>

    <?
    //    echo $arResult["DISPLAY_PROPERTIES"]["ID_ONE_C"]['NAME'] . ': ' . $arResult["DISPLAY_PROPERTIES"]["ID_ONE_C"]["DISPLAY_VALUE"] . "<br>";
    //    echo $arResult["DISPLAY_PROPERTIES"]["KEY"]['NAME'] . ': ' . $arResult["DISPLAY_PROPERTIES"]["KEY"]["DISPLAY_VALUE"] . "<br>";
    //    echo $arResult["DISPLAY_PROPERTIES"]["EXPIRY_DATE"]['NAME'] . ': ' . $arResult["DISPLAY_PROPERTIES"]["EXPIRY_DATE"]["DISPLAY_VALUE"] . "<br>";
    ?>

    <section class="manager">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h3>List of managers of
                        <b><?= $arResult['NAME'] ?></b> who can respond to requests. To add new
                        сontact the purchasing manager.</h3>
                </div>
                <div class="col-xs-12 col-sm-6 manager_list hidden-xs">
                    <p><b>Name</b></p>
                </div>
                <div class="col-xs-12 col-sm-6 manager_list hidden-xs">
                    <p><b>email</b></p>
                </div>

                <? /** @var \Bitrix\Iblock\Elements\EO_ElementSupplierContact $contact */
                foreach ($contacts as $contact) { ?>
                    <div class="col-xs-12 col-sm-6 manager_list">
                        <p><b><?= $contact->getName() ?></b></p>
                    </div>
                    <div class="col-xs-12 col-sm-6 manager_list">
                        <p><?= $contact->getEmail()->getValue() ?></p>
                    </div>
                <? } ?>
            </div>
        </div>
    </section>
</div>
