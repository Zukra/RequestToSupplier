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
    <h3><?= $arResult["NAME"] ?></h3>

    <br/>
    <?
    echo $arResult["DISPLAY_PROPERTIES"]["ID_ONE_C"]['NAME'] . ': ' . $arResult["DISPLAY_PROPERTIES"]["ID_ONE_C"]["DISPLAY_VALUE"] . "<br>";
    echo $arResult["DISPLAY_PROPERTIES"]["KEY"]['NAME'] . ': ' . $arResult["DISPLAY_PROPERTIES"]["KEY"]["DISPLAY_VALUE"] . "<br>";
    echo $arResult["DISPLAY_PROPERTIES"]["EXPIRY_DATE"]['NAME'] . ': ' . $arResult["DISPLAY_PROPERTIES"]["EXPIRY_DATE"]["DISPLAY_VALUE"] . "<br>";
    ?>

    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
        </tr>
        </thead>
        <tbody>
        <? /** @var \Bitrix\Iblock\Elements\EO_ElementSupplierContact $contact */
        $i = 0;
        foreach ($contacts as $contact) { ?>
            <tr>
                <th scope="row"><?= ++$i ?></th>
                <td><?= $contact->getName() ?></td>
                <td><?= $contact->getEmail()->getValue() ?></td>
            </tr>
        <? } ?>
        </tbody>
    </table>
</div>
