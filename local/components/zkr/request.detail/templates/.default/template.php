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
$currencies = [
    "USD" => "USD (US Dollar)",
    "EUR" => "EUR (Euro)",
    "RUB" => "RUB (Russian Ruble)",
    "PLN" => "PLN",
];
//$currencies = \Bitrix\Currency\CurrencyManager::getCurrencyList();
//$measures = ['T', 't', 'm'];

$classColors = [
    \Zkr\Supplier\Price\Request::NEW            => 'color_new_blocked',
    \Zkr\Supplier\Price\Request::WAIT_REPLY     => 'color_new_waiting',
    \Zkr\Supplier\Price\Request::BLOCKED_UPDATE => 'color_updated_waiting',
    \Zkr\Supplier\Price\Request::SENT           => 'color_sent',
];
$currentContact = $arResult["PROPERTIES"]["CONTACT"]['VALUE']
    ? $supplierContacts->getByPrimary($arResult["PROPERTIES"]["CONTACT"]['VALUE'])
    : null;

$isBlocked = $arResult["PROPERTIES"]["IS_BLOCKED"]['VALUE'];

$this->setFrameMode(true);
?>
<div class="request-detail">
    <input type="hidden" id="bitrix_session_id"
           name="bitrix_session_id"
           value="<?= bitrix_sessid() ?>"
           data-id="<?= $arResult["PROPERTIES"]['SESSION_ID']["ID"] ?>"
           data-code="<?= $arResult["PROPERTIES"]['SESSION_ID']["CODE"] ?>">

    <section class="bread_crumbs_sect">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="bread_crumbs">
                        <a href="/personal/requests/">
                            <span>
                                <img src="<?= $APPLICATION->GetTemplatePath('images/lef.svg') ?>" alt="">
                                <img src="<?= $APPLICATION->GetTemplatePath('images/lef.svg') ?>" alt="">
                            </span>
                            Back to Inquiries
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="general_terms">
        <div class="container title_gen_it">
            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <div class="gen_title">
                        <h3>
                            <b>
                                Inquiry #<?= substr($arResult["NAME"], 0, 11) ?>
                                by <?= (new \Bitrix\Main\Type\Date($arResult["TIMESTAMP_X"]))->toString() ?>
                                to <?= $supplier->getName() ?>
                            </b>
                        </h3>
                        <h1>
                            <span class="request-event"><?= $arResult["PROPERTIES"]['EVENT']["VALUE"] ?></span>
                            <span class="request-by_blocked">
                                <? if ($arResult["PROPERTIES"]["IS_BLOCKED"]['VALUE']) { ?>
                                    by
                                    <span class="blocked-contact">
                                        <?= $currentContact->state
                                            ? $currentContact->getName() . ' (' . $currentContact->getEmail()->getValue() . ')'
                                            : '' ?>
                                    </span>
                                <? } ?>
                            </span>
                            <span class="request-supplier"><?= ' to ' . $supplier->getName() ?></span>
                        </h1>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <div class="gen_ststus <?= $classColors[$arResult["PROPERTIES"]['STATUS']["VALUE"]] ?>">
                        <p class="status"><?= $arResult["PROPERTIES"]['STATUS']["VALUE"] ?></p>
                        <p>
                            <span class="saving_data">Changes is saving...</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <? if ($isBlocked && bitrix_sessid() != $arResult["PROPERTIES"]["SESSION_ID"]['VALUE']) { ?>
                        <div class="form_usb">
                            <button type="button" name='take_request_control' class="js-take_request_control btn btn-gen_it title_help"
                                    data-title="If you want to take general control of the request">
                                Take control of the inquiry
                            </button>
                        </div>
                    <? } ?>
                </div>
            </div>
        </div>
        <div class="container comment_gen_it">
            <div class="row">
                <div class="col-xs-12">
                    <div class="gen_comment">
                        <p>Comment to inquiry
                            <span><?= $arResult["PROPERTIES"]['COMMENT']["VALUE"] ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container form_gen_it <?= $isBlocked && bitrix_sessid() != $arResult["PROPERTIES"]["SESSION_ID"]['VALUE'] ? 'no_active' : '' ?>">
            <div class="row">
                <div class="col-xs-12">
                    <div class="gen_name"><p>General terms</p></div>
                </div>
                <div class="col-xs-12">
                    <div class="gen_form">
                        <form class="form-horizontal" role="form" name="request" method="post" action="/">
                            <div class="general-term">
                                <input type="hidden" name="request-id" value="<?= $arResult['ID'] ?>">
                                <input type="hidden" name="request-1c" value="<?= $arResult['PROPERTIES']['REQUEST_ID']['VALUE'] ?>">
                                <input type="hidden" name="supplier-id" value="<?= $supplier->getId() ?>">
                                <? /*<input type="hidden" name="request-token" value="<?= REQUEST_TOKEN ?>">
                                <input type="hidden" name="key" value="<?= $_SESSION["access_key"] ?>">*/ ?>

                                <? /*<div class="form-group has-error">*/ ?>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Payment conditions
                                        <span class="example_help" tabindex="0" data-toggle="popover" data-trigger="focus" data-content="Payment conditions">
 	         	    	                    <img src="<?= $APPLICATION->GetTemplatePath('images/help.svg') ?>" alt="">
 	         	    	                </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="payment_order"
                                               required
                                               placeholder="Payment conditions" aria-label="PaymentOrder" aria-describedby="payment_order"
                                               value="<?= $arResult["PROPERTIES"]['PAYMENT_ORDER']["VALUE"] ?>"
                                               data-id="<?= $arResult["PROPERTIES"]['PAYMENT_ORDER']["ID"] ?>"
                                               data-code="<?= $arResult["PROPERTIES"]['PAYMENT_ORDER']["CODE"] ?>">
                                        <? /*<label class="error-label" for="inputError">Input with error</label>*/ ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Delivery time
                                        <span class="example_help" tabindex="0" data-toggle="popover" data-trigger="focus" data-content="Delivery time">
 	         	    	                <img src="<?= $APPLICATION->GetTemplatePath('images/help.svg') ?>" alt="">
 	         	    	            </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="delivery_time"
                                               required
                                               placeholder="Delivery time" aria-label="DeliveryTime"
                                               aria-describedby="basic-delivery_time"
                                               value="<?= $arResult["PROPERTIES"]['DELIVERY_TIME']["VALUE"] ?>"
                                               data-id="<?= $arResult["PROPERTIES"]['DELIVERY_TIME']["ID"] ?>"
                                               data-code="<?= $arResult["PROPERTIES"]['DELIVERY_TIME']["CODE"] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Delivery conditions INCOTERMS 2010
                                        <span class="example_help" tabindex="0" data-toggle="popover" data-trigger="focus" data-content="Delivery conditions INCOTERMS 2010">
 	         	    	                <img src="<?= $APPLICATION->GetTemplatePath('images/help.svg') ?>" alt="">
 	         	    	            </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="incoterms"
                                               placeholder="INCOTERMS" aria-label="INCOTERMS"
                                               aria-describedby="basic-incoterms"
                                               required
                                               value="<?= $arResult["PROPERTIES"]['INCOTERMS']["VALUE"] ?>"
                                               data-id="<?= $arResult["PROPERTIES"]['INCOTERMS']["ID"] ?>"
                                               data-code="<?= $arResult["PROPERTIES"]['INCOTERMS']["CODE"] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Currency
                                        <span class="example_help" tabindex="0" data-toggle="popover" data-trigger="focus" data-content="Currency">
                                        <img src="<?= $APPLICATION->GetTemplatePath('images/help.svg') ?>" alt="">
                                    </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="browser-default custom-select form-control" name="currency"
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
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Contact
                                        <span class="example_help" tabindex="0" data-toggle="popover" data-trigger="focus" data-content="Contact">
 	         	    	                <img src="<?= $APPLICATION->GetTemplatePath('images/help.svg') ?>" alt="">
                                    </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="custom-browser-default custom-select form-control"
                                                name="contact"
                                                data-id="<?= $arResult["PROPERTIES"]['CONTACT']["ID"] ?>"
                                                data-code="<?= $arResult["PROPERTIES"]['CONTACT']["CODE"] ?>">
                                            <? foreach ($supplierContacts as $contact) { ?>
                                                <option <?= $arResult["PROPERTIES"]["CONTACT"]['VALUE'] == $contact->getId() ? "selected" : "" ?>
                                                        value="<?= $contact->getId() ?>">
                                                    <?= $contact->getName() . ' (' . $contact->getEmail()->getValue() . ')' ?>
                                                </option>
                                            <? } ?>
                                            <option value="0">Add new</option>
                                        </select>
                                    </div>
                                </div>
                                <? /*<div class="form_usb">
                                    <button type="submit" class="btn btn-gen_it title_help" data-title="If you want to change general conditions for the whole offer please click here and resend it">Go to specification</button>
                                </div>*/ ?>
                            </div>

                            <div class="new-contact" style="display: none">
                                <div class="form-group">
                                    <label for="new_name" class="col-sm-4 control-label"></label>
                                    <div class="col-sm-3">
                                        <input id="new_name" type="text" class="form-control" name="new_name"
                                               placeholder="Name" aria-label="new_name"
                                               aria-describedby="basic-new_name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="new_email" class="col-sm-4 control-label"></label>
                                    <div class="col-sm-3">
                                        <input id="new_email" type="email" class="form-control" name="new_email"
                                               placeholder="Email" aria-label="new_email"
                                               aria-describedby="basic-new_email">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"></label>
                                    <div class="col-sm-3">
                                        <button type="button" class="js-add-new-contact btn btn-primary">Add</button>
                                        <button type="button" class="js-cancel-new-contact btn btn-danger">Cancel</button>
                                    </div>
                                </div>
                            </div>

                            <div class="container specif_wrap">
                                <!--                            <div class="container specif_wrap no_active">-->
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="specif_search_box">
                                            <div class="specif_search_name">
                                                Specification
                                            </div>
                                            <div class="specif_search">
                                                <input type="text" class="form-control"
                                                       id="" placeholder="Quick search by table"
                                                       onkeyup="filterSearch(this)">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="table-responsive specification">
                                            <table class="table table-bordered specif_table">
                                                <colgroup>
                                                    <col span="4" style="background:#f6f5f1;">
                                                    <col span="3" style="background:#fff">
                                                    <col span="1" style="background:#f6f5f1">
                                                    <col span="3" style="background:#fff">
                                                </colgroup>
                                                <thead>
                                                <tr>
                                                    <th class="">Description</th>
                                                    <th class="title_help" data-title="Commen of Manager EMK">Comment</th>
                                                    <th class="title_help" data-title="Quantity of Manager EMK">Quantity<br>acc. to inquiry</th>
                                                    <th class="">
                                                        Unit acc.<br>to inquiry
                                                    </th>
                                                    <th class="">Quantity acc.
                                                        <br>to Supplier's <br>offer
                                                    </th>
                                                    <th class="">Unit acc.
                                                        <br>to Supplier's <br>offer
                                                    </th>
                                                    <th class="">Price per<br> unit acc.<br> to Supplier's
                                                        <br>offer
                                                    </th>
                                                    <th class="">Total</th>
                                                    <th class="">Common or
                                                        <br>individual
                                                        <br>delivery time
                                                    </th>
                                                    <th class="">Common or
                                                        <br>individual <br>INCOTERMS
                                                    </th>
                                                    <th class="">Repla-<br>cement of
                                                        <br>product
                                                    </th>
                                                    <th class="">Comment of
                                                        <br>supplier
                                                        <span class="example_help" tabindex="0"
                                                              data-toggle="popover"
                                                              data-trigger="focus"
                                                              data-content="Specify analogue">
                                                            <img src="<?= $APPLICATION->GetTemplatePath('images/help.svg') ?>" alt="">
                                                        </span>
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <? /** @var \Bitrix\Iblock\Elements\EO_ElementRequestSpecification $item */
                                                foreach ($specification as $item) { ?>
                                                    <tr class="specification-item" id="<?= $item->getId(); ?>">
                                                        <td class="spec_name"><?= $item->getName() ?></td>
                                                        <td class="spec_comment"><?= $item->getComment()->getValue() ?: "" ?></td>
                                                        <td class="quantity_r"><?= $item->getQuantityR()->getValue() ?></td>
                                                        <td class="measure_r"><?= $item->getUnitMeasure()->getValue() ?></td>
                                                        <td class="r">
                                                            <input type="text" class="recalc redact_area"
                                                                   name="quantity_s"
                                                                   data-code="SUPPLIER_QUANTITY"
                                                                   value="<?= $item->getSupplierQuantity()->getValue() ?: $item->getQuantityR()->getValue() ?>"
                                                                   onkeypress="return isNumberKey(event);"
                                                                   autocomplete="off">
                                                            <? /*<div class="error_box">
                                                                <span class="error_area" data-toggle="tooltip" data-placement="top" title="" data-original-title="Error">
                                                                    <img src="<?= $APPLICATION->GetTemplatePath('images/help_r.svg') ?>" alt="">
                                                                </span>
                                                            </div>*/ ?>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="redact_area" name="unit_s"
                                                                   data-code="SUPPLIER_UNIT"
                                                                   value="<?= $item->getSupplierUnit()->getValue() ?: $item->getUnitMeasure()->getValue() ?>">
                                                            <? /* <select class="custom-select" id="inputGroupSelectMeasure" name="unit_s"
                                                                    data-code="SUPPLIER_UNIT">
                                                                <? foreach ($measures as $measure) { ?>
                                                                    <option <?= $item->getSupplierUnit()->getValue() == $measure ? "selected" : "" ?>
                                                                            value="<?= $measure ?>">
                                                                        <?= $measure ?>
                                                                    </option>
                                                                <? } ?>
                                                            </select>*/ ?>
                                                            <? /*<input type="text" class="redact_area" id="" placeholder="T">
                                                            <div class="error_box">
                                                                <span class="error_area" data-toggle="tooltip" data-placement="top" title="" data-original-title="Error">
                                                                    <img src="<?= $APPLICATION->GetTemplatePath('images/help_r.svg') ?>" alt="">
                                                                </span>
                                                            </div>*/ ?>
                                                        </td>
                                                        <td data-col="price_s">
                                                            <input type="text" class="recalc redact_area"
                                                                   name="price_s"
                                                                   data-code="SUPPLIER_PRICE_UNIT"
                                                                   value="<?= $item->getSupplierPriceUnit()->getValue() ?? '' ?>"
                                                                   onkeypress="return isNumberKey(event);"
                                                                   autocomplete="off">
                                                        </td>
                                                        <td class="total">
                                                            <?= $item->getSupplierQuantity()->getValue() * $item->getSupplierPriceUnit()->getValue() ?>
                                                        </td>
                                                        <td>
                                                            <? /*
                                                            <input type="text" class="redact_area"
                                                                   placeholder="Common delivery time"
                                                                   name="delivery_time"
                                                                   data-code="DELIVERY_TIME"
                                                                   value="<?= $item->getDeliveryTime()->getValue() ?>">
 */ ?>
                                                            <? /*<div class="error_box">
                                                                <span class="error_area" data-toggle="tooltip" data-placement="top" title="" data-original-title="Error">
                                                                    <img src="<?= $APPLICATION->GetTemplatePath('images/help_r.svg') ?>" alt="">
                                                                </span>
                                                            </div>*/ ?>
                                                            <textarea class="form-control"
                                                                      name="delivery_time"
                                                                      data-code="DELIVERY_TIME"
                                                                      placeholder="Common delivery time"><?= $item->getDeliveryTime()->getValue() ?></textarea>
                                                        </td>
                                                        <td>
                                                            <? /*
                                                            <input type="text" class="redact_area"
                                                                   placeholder="Common INCOTERMS"
                                                                   name="incoterms"
                                                                   data-code="INCOTERMS"
                                                                   value="<?= $item->getIncoterms()->getValue() ?>">
 */ ?>
                                                            <? /*<div class="error_box">
                                                                <span class="error_area" data-toggle="tooltip" data-placement="top" title="" data-original-title="Error">
                                                                    <img src="<?= $APPLICATION->GetTemplatePath('images/help_r.svg') ?>" alt="">
                                                                </span>
                                                            </div>*/ ?>
                                                            <textarea class="form-control"
                                                                      name="incoterms"
                                                                      data-code="INCOTERMS"
                                                                      placeholder="Common INCOTERMS"><?= $item->getIncoterms()->getValue() ?></textarea>
                                                        </td>
                                                        <td>
                                                            <label class="checkbox-transform">
                                                                <input type="checkbox" class="checkbox__input"
                                                                       name="replacement"
                                                                       data-code="REPLACEMENT"
                                                                       value="<?= $item->getReplacement()->getValue() ?>"
                                                                    <?= $item->getReplacement()->getValue() ? "checked" : "" ?>>
                                                                <span class="checkbox__label"></span>
                                                            </label>
                                                        </td>
                                                        <td class="error_">
                                                            <? /*
                                                            <input type="text"
                                                                   class="redact_area"
                                                                   name="comment_s"
                                                                   data-code="SUPPLIER_COMMENT"
                                                                   value="<?= $item->getSupplierComment()->getValue() ?>"
                                                                   autocomplete="off"
                                                                <?= $item->getReplacement()->getValue() ? "required" : "" ?>>
*/ ?>
                                                            <textarea class="form-control"
                                                                      name="comment_s"
                                                                      data-code="SUPPLIER_COMMENT"
                                                                      autocomplete="off"
                                                                      <?= $item->getReplacement()->getValue() ? "required" : "" ?>><?= $item->getSupplierComment()->getValue() ?></textarea>
                                                            <div class="error_box">
                                                                <? /*<span class="error_area"
                                                                      data-toggle="tooltip"
                                                                      title="Disabled tooltip">
                                                                        <img src="<?= $APPLICATION->GetTemplatePath('images/help_r.svg') ?>" alt="">
                                                                  </span>*/ ?>
                                                                <? /*<span class="title_help"
                                                                      data-title="test test test">
                                                                       <img src="<?= $APPLICATION->GetTemplatePath('images/help_r.svg') ?>" alt="">
                                                                </span>*/ ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <? } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="general-term">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="total_price_wrap">
                                            <div class="col-xs-12 col-sm-3 col-md-3 text_rig">
                                                <p>
                                                    <b>Total price of inquiry </b>
                                                </p>
                                            </div>
                                            <div class="col-xs-12 col-sm-9 col-md-9">
                                                <span class="total-price">0.00</span>
                                                <span class="total-currency"></span>
                                            </div>
                                        </div>
                                        <div class="total_price_comment">
                                            <div class="col-xs-12 col-sm-3 col-md-3 text_rig">
                                                <label for="" class="control-label">Comment</label>
                                            </div>
                                            <div class="col-xs-12 col-sm-9 col-md-9">
                                                <textarea class="form-control" name="supplier_comment" placeholder="Your comment"
                                                          data-id="<?= $arResult["PROPERTIES"]['SUPPLIER_COMMENT']["ID"] ?>"
                                                          data-code="<?= $arResult["PROPERTIES"]['SUPPLIER_COMMENT']["CODE"] ?>"><?= $arResult["PROPERTIES"]['SUPPLIER_COMMENT']["VALUE"] ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="container">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="form_specific_sub">
                                            <button type="submit" class="btn btn-spec_it js_form_submit">Send reply</button>
                                            <button type="button" class="btn btn-outline-light js_raw_rows_counter" data-toggle="button" aria-pressed="false" autocomplete="off">
                                                <span class="raw_rows_text">
                                                    Missed rows <span class="badge badge-light raw_rows_counter"></span>
                                                </span>
                                                <span class="all_rows_text" style="display: none">Show all rows</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bread_crumbs_sect">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="bread_crumbs">
                        <a href="/personal/requests/">
                            <span>
                                <img src="<?= $APPLICATION->GetTemplatePath('images/lef.svg') ?>" alt="">
                                <img src="<?= $APPLICATION->GetTemplatePath('images/lef.svg') ?>" alt="">
                            </span>
                            Back to Inquiries
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

<script>
    BX.message({
        classColors: '<?= json_encode($classColors); ?>',
        BLOCKED_UPDATE: '<?=\Zkr\Supplier\Price\Request::BLOCKED_UPDATE?>',
        WAIT_REPLY: '<?=\Zkr\Supplier\Price\Request::WAIT_REPLY?>'
    });
</script>
