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
/** @var \Bitrix\Iblock\Elements\EO_ElementSupplier $supplier */
/** @var \Bitrix\Iblock\Elements\EO_ElementSupplierContact_Collection $supplierContacts */
/** @var \Bitrix\Iblock\Elements\EO_ElementRequestSpecification_Collection $specification */

$supplier = $arResult['SUPPLIER'];
$supplierContacts = $arResult['SUPPLIER_CONTACTS'];
$specification = $arResult['SPECIFICATION'];
$currencies = \Bitrix\Currency\CurrencyManager::getCurrencyList();
$measures = ['T', 't', 'm'];

$this->setFrameMode(true);
?>
<div class="request-detail">

    <a href="/personal/requests/">Back to Requests</a>

    <h3><b><?= $arResult["NAME"] ?></b></h3>
    <h3>
        <b><?= $arResult["PROPERTIES"]['EVENT']["VALUE"] . ' to ' . $supplier->getName() ?></b>
    </h3>
    <h3>
        <b><?= $arResult["PROPERTIES"]['STATUS']["VALUE"] ?></b>
    </h3>
    <h3>
        <b>Comment to request</b> <?= $arResult["PROPERTIES"]['COMMENT']["VALUE"] ?>
    </h3>

    <form name="request" method="post">
        <input type="hidden" name="request-id" value="<?= $arResult['ID'] ?>">

        <div class="general-term">
            <div class="form-group">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-payment_order">Payment conditions</span>
                    </div>
                    <input type="text" class="form-control" name="payment_order"
                           placeholder="Payment conditions" aria-label="PaymentOrder" aria-describedby="payment_order"
                           value="<?= $arResult["PROPERTIES"]['PAYMENT_ORDER']["VALUE"] ?>"
                           data-id="<?= $arResult["PROPERTIES"]['PAYMENT_ORDER']["ID"] ?>"
                           data-code="<?= $arResult["PROPERTIES"]['PAYMENT_ORDER']["CODE"] ?>">
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-delivery_time">Delivery time</span>
                    </div>
                    <input type="text" class="form-control" name="delivery_time"
                           placeholder="Delivery time" aria-label="DeliveryTime"
                           aria-describedby="basic-delivery_time"
                           value="<?= $arResult["PROPERTIES"]['DELIVERY_TIME']["VALUE"] ?>"
                           data-id="<?= $arResult["PROPERTIES"]['DELIVERY_TIME']["ID"] ?>"
                           data-code="<?= $arResult["PROPERTIES"]['DELIVERY_TIME']["CODE"] ?>">
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="basic-incoterms">Delivery conditions INCOTERMS 2010</span>
                    </div>
                    <input type="text" class="form-control" name="incoterms"
                           placeholder="INCOTERMS" aria-label="INCOTERMS"
                           aria-describedby="basic-incoterms"
                           value="<?= $arResult["PROPERTIES"]['INCOTERMS']["VALUE"] ?>"
                           data-id="<?= $arResult["PROPERTIES"]['INCOTERMS']["ID"] ?>"
                           data-code="<?= $arResult["PROPERTIES"]['INCOTERMS']["CODE"] ?>">
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelectCurrency">Currency</label>
                    </div>
                    <select class="custom-select" id="inputGroupSelectSupplierContact" name="currency"
                            data-id="<?= $arResult["PROPERTIES"]['CURRENCY']["ID"] ?>"
                            data-code="<?= $arResult["PROPERTIES"]['CURRENCY']["CODE"] ?>">
                        <? foreach ($currencies as $key => $currency) { ?>
                            <option <?= $arResult["PROPERTIES"]["CURRENCY"]['VALUE'] == $key ? "selected" : "" ?>
                                    value="<?= $key ?>">
                                <?= $key ?>
                            </option>
                        <? } ?>
                    </select>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <label class="input-group-text" for="inputGroupSelectSupplierContact">Contact</label>
                    </div>
                    <select class="custom-select" id="inputGroupSelectSupplierContact" name="contact"
                            data-id="<?= $arResult["PROPERTIES"]['CONTACT']["ID"] ?>"
                            data-code="<?= $arResult["PROPERTIES"]['CONTACT']["CODE"] ?>">
                        <? foreach ($supplierContacts as $contact) { ?>
                            <option <?= $arResult["PROPERTIES"]["CONTACT"]['VALUE'] == $contact->getId() ? "selected" : "" ?>
                                    value="<?= $contact->getId() ?>">
                                <?= $contact->getName() . ' (' . $contact->getEmail()->getValue() . ')' ?>
                            </option>
                            <option value="0">Add new</option>
                        <? } ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="specification">
            <div class="form-group">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col" class="col-3">Description</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Unit of measure in request</th>
                        <th scope="col">Quantity of supplier's*</th>
                        <th scope="col">Unit of supplier*</th>
                        <th scope="col">Price Unit of supplier*, EUR</th>
                        <th scope="col">Total price</th>
                        <th scope="col">Common or individual delivery time</th>
                        <th scope="col">Common or individual INCOTERMS</th>
                        <th scope="col">Replacement of product</th>
                        <th scope="col">Comment of supplier</th>
                    </tr>
                    </thead>
                    <tbody>
                    <? /** @var \Bitrix\Iblock\Elements\EO_ElementRequestSpecification $item */
                    foreach ($specification as $item) { ?>
                        <tr class="specification-item" id="<?= $item->getId(); ?>">
                            <td><? echo $item->getName() ?></td>
                            <td><?= $item->getComment()->getValue() ?></td>
                            <td><?= $item->getQuantityR()->getValue() ?></td>
                            <td><?= $item->getUnitMeasure()->getValue() ?></td>
                            <td>
                                <input type="text" class="recalc" name="quantity_s"
                                       data-code="SUPPLIER_QUANTITY"
                                       value="<?= $item->getSupplierQuantity()->getValue() ?>">
                            </td>
                            <td>
                                <select class="custom-select" id="inputGroupSelectMeasure" name="unit_s"
                                        data-code="SUPPLIER_UNIT">
                                    <? foreach ($measures as $measure) { ?>
                                        <option <?= $item->getSupplierUnit()->getValue() == $measure ? "selected" : "" ?>
                                                value="<?= $measure ?>">
                                            <?= $measure ?>
                                        </option>
                                    <? } ?>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="recalc" name="price_s" data-code="SUPPLIER_PRICE_UNIT"
                                       value="<?= $item->getSupplierPriceUnit()->getValue() ?>">
                            </td>
                            <td class="total">
                                <?= $item->getSupplierQuantity()->getValue() * $item->getSupplierPriceUnit()->getValue() ?>
                            </td>
                            <td>
                                <input type="text" name="delivery_time" data-code="DELIVERY_TIME"
                                       value="<?= $item->getDeliveryTime()->getValue() ?>">
                            </td>
                            <td>
                                <input type="text" name="incoterms" data-code="INCOTERMS"
                                       value="<?= $item->getIncoterms()->getValue() ?>">
                            </td>
                            <td>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <input type="checkbox" name="replacement" data-code="REPLACEMENT"
                                                   value="<?= $item->getReplacement()->getValue() ?>"
                                                <?= $item->getReplacement()->getValue() ? "checked" : "" ?>>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <input type="text" name="comment_s" data-code="SUPPLIER_COMMENT"
                                       value="<?= $item->getSupplierComment()->getValue() ?>">
                            </td>
                        </tr>
                    <? } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="general-term">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Comment</span>
                    </div>
                    <textarea class="form-control" name="supplier_comment"
                              data-id="<?= $arResult["PROPERTIES"]['SUPPLIER_COMMENT']["ID"] ?>"
                              data-code="<?= $arResult["PROPERTIES"]['SUPPLIER_COMMENT']["CODE"] ?>"><?= $arResult["PROPERTIES"]['SUPPLIER_COMMENT']["VALUE"] ?></textarea>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Send reply</button>
        <a class="btn btn-link" href="/personal/requests/">Show raw rows</a>
    </form>

</div>