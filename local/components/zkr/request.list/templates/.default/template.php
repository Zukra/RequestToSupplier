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

/** @var \Bitrix\Iblock\Elements\EO_ElementSupplier $supplier */
$supplier = $arResult['SUPPLIER'];
?>
<div class="request-list">
    <h3><?= 'List of requests to ' . $supplier->getName() ?></h3>

    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Id</th>
            <th scope="col">Date modify</th>
            <th scope="col">Status</th>
        </tr>
        </thead>
        <tbody>
        <? $i = 0; ?>
        <? foreach ($arResult["ITEMS"] as $arItem) { ?>
            <tr class="request-item" id="request_<?= $arItem['ID']; ?>">
                <th scope="row"><?= ++$i ?></th>
                <td>
                    <? if (! $arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
                        <a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>">
                            <b><? echo $arItem["NAME"] ?></b>
                        </a>
                        <br/>
                    <? else: ?>
                        <b><? echo $arItem["NAME"] ?></b><br/>
                    <? endif; ?>
                </td>
                <td><?= $arItem["FIELDS"]['TIMESTAMP_X'] ?></td>
                <td><?= $arItem["DISPLAY_PROPERTIES"]['EVENT']['DISPLAY_VALUE'] ?></td>
            </tr>
        <? } ?>
        </tbody>
    </table>

    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
        <br/><?= $arResult["NAV_STRING"] ?>
    <? endif; ?>
</div>
