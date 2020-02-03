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
                            Back to Requests
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <h3><b><?= $arResult["NAME"] ?></b></h3>

    <section class="general_terms">
        <div class="container title_gen_it">
            <div class="row">
                <div class="col-xs-12 col-sm-8">
                    <div class="gen_title">
                        <h1><?= $arResult["PROPERTIES"]['EVENT']["VALUE"] . ' to ' . $supplier->getName() ?></h1>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4">
                    <div class="gen_ststus status_new_waiting">
                        <p>New, waiting for a reply</p>
                        <p><span>Changes is saving...</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container comment_gen_it">
            <div class="row">
                <div class="col-xs-12">
                    <div class="gen_comment">
                        <p>Comment to request
                            <span><?= $arResult["PROPERTIES"]['COMMENT']["VALUE"] ?></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container form_gen_it">
            <div class="row">
                <div class="col-xs-12">
                    <div class="gen_name"><p>General terms</p></div>
                </div>
                <div class="col-xs-12">
                    <div class="gen_form">
                        <form class="form-horizontal" role="form" name="request" method="post">
                            <div class="general-term">
                                <input type="hidden" name="request-id" value="<?= $arResult['ID'] ?>">
                                <input type="hidden" name="request-token" value="<?= REQUEST_TOKEN ?>">
                                <input type="hidden" name="request-1c" value="<?= $arResult['PROPERTIES']['REQUEST_ID']['VALUE'] ?>">

                                <div class="form-group has-error">
                                    <label for="" class="col-sm-4 control-label">Payment conditions
                                        <span class="example_help" data-container="body" data-toggle="popover" data-placement="top" data-content="Delay of payments or prepayment" data-original-title="" title="">
 	         	    	                <img src="<?= $APPLICATION->GetTemplatePath('images/help.svg') ?>" alt="">
 	         	    	            </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="payment_order"
                                               placeholder="Payment conditions" aria-label="PaymentOrder" aria-describedby="payment_order"
                                               value="<?= $arResult["PROPERTIES"]['PAYMENT_ORDER']["VALUE"] ?>"
                                               data-id="<?= $arResult["PROPERTIES"]['PAYMENT_ORDER']["ID"] ?>"
                                               data-code="<?= $arResult["PROPERTIES"]['PAYMENT_ORDER']["CODE"] ?>">
                                        <label class="error-label" for="inputError">Input with error</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Delivery time
                                        <span class="example_help" data-container="body" data-toggle="popover" data-placement="top" data-content="Delay of payments or prepayment" data-original-title="" title="">
 	         	    	                <img src="<?= $APPLICATION->GetTemplatePath('images/help.svg') ?>" alt="">
 	         	    	            </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="delivery_time"
                                               placeholder="Delivery time" aria-label="DeliveryTime"
                                               aria-describedby="basic-delivery_time"
                                               value="<?= $arResult["PROPERTIES"]['DELIVERY_TIME']["VALUE"] ?>"
                                               data-id="<?= $arResult["PROPERTIES"]['DELIVERY_TIME']["ID"] ?>"
                                               data-code="<?= $arResult["PROPERTIES"]['DELIVERY_TIME']["CODE"] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Delivery conditions INCOTERMS 2010
                                        <span class="example_help" data-container="body" data-toggle="popover" data-placement="top" data-content="Delay of payments or prepayment" data-original-title="" title="">
 	         	    	                <img src="<?= $APPLICATION->GetTemplatePath('images/help.svg') ?>" alt="">
 	         	    	            </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="incoterms"
                                               placeholder="INCOTERMS" aria-label="INCOTERMS"
                                               aria-describedby="basic-incoterms"
                                               value="<?= $arResult["PROPERTIES"]['INCOTERMS']["VALUE"] ?>"
                                               data-id="<?= $arResult["PROPERTIES"]['INCOTERMS']["ID"] ?>"
                                               data-code="<?= $arResult["PROPERTIES"]['INCOTERMS']["CODE"] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="inputGroupSelectSupplierContact" class="col-sm-4 control-label">Currency
                                        <span class="example_help" data-container="body" data-toggle="popover" data-placement="top" data-content="Delay of payments or prepayment" data-original-title="" title="">
                                        <img src="<?= $APPLICATION->GetTemplatePath('images/help.svg') ?>" alt="">
                                    </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="browser-default custom-select form-control" id="inputGroupSelectSupplierContact" name="currency"
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
                                    <label for="inputGroupSelectSupplierContact" class="col-sm-4 control-label">Contact
                                        <span class="example_help" data-container="body" data-toggle="popover" data-placement="top" data-content="Delay of payments or prepayment" data-original-title="" title="">
 	         	    	                <img src="<?= $APPLICATION->GetTemplatePath('images/help.svg') ?>" alt="">
                                    </span>
                                    </label>
                                    <div class="col-sm-8">
                                        <select class="custom-browser-default custom-select form-control"
                                                id="inputGroupSelectSupplierContact" name="contact"
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
                                <div class="form_usb">
                                    <button type="submit" class="btn btn-gen_it title_help" data-title="If you want to change general conditions for the whole offer please click here and resend it">Go to specification</button>
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
                                                <input type="text" class="form-control" id="" placeholder="Quick search by table">
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
                                                    <th class="title_help" data-title="Commen of Manager EMK">Description</th>
                                                    <th class="title_help" data-title="Commen of Manager EMK">Comment</th>
                                                    <th class="title_help" data-title="Commen of Manager EMK">Quan-tity</th>
                                                    <th class="title_help" data-title="Commen of Manager EMK">
                                                        Unit of<br> mea-<br>sure in<br> request
                                                    </th>
                                                    <th class="title_help" data-title="Commen of Manager EMK">Quan-<br>tity of
                                                        <br>supp-<br>lier's*
                                                    </th>
                                                    <th class="title_help" data-title="Commen of Manager EMK">Unit
                                                        <br>of supp-<br>lier's*
                                                    </th>
                                                    <th class="title_help" data-title="Commen of Manager EMK">Price by<br> Unit of<br> supplier's*,
                                                        <br>EUR
                                                    </th>
                                                    <th class="title_help" data-title="Commen of Manager EMK">Total
                                                        <br>price
                                                    </th>
                                                    <th class="title_help" data-title="Commen of Manager EMK">Common or
                                                        <br>individual
                                                        <br>delivery time
                                                    </th>
                                                    <th class="title_help" data-title="Commen of Manager EMK">Common or
                                                        <br>individual <br>INCOTERMS
                                                    </th>
                                                    <th class="title_help" data-title="Commen of Manager EMK">Repla-<br>cement of
                                                        <br>product
                                                    </th>
                                                    <th class="title_help" data-title="Commen of Manager EMK">Comment of
                                                        <br>supplier
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <? /** @var \Bitrix\Iblock\Elements\EO_ElementRequestSpecification $item */
                                                foreach ($specification as $item) { ?>
                                                    <tr class="specification-item" id="<?= $item->getId(); ?>">
                                                        <td><?= $item->getName() ?></td>
                                                        <td><?= $item->getComment()->getValue() ?></td>
                                                        <td><?= $item->getQuantityR()->getValue() ?></td>
                                                        <td><?= $item->getUnitMeasure()->getValue() ?></td>
                                                        <td class="error">
                                                            <input type="text" class="recalc redact_area" name="quantity_s"
                                                                   data-code="SUPPLIER_QUANTITY"
                                                                   value="<?= $item->getSupplierQuantity()->getValue() ?>">
                                                            <div class="error_box">
                                                                <span class="error_area" data-toggle="tooltip" data-placement="top" title="" data-original-title="Error">
                                                                    <img src="<?= $APPLICATION->GetTemplatePath('images/help_r.svg') ?>" alt="">
                                                                </span>
                                                            </div>
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
                                                            <? /*<input type="text" class="redact_area" id="" placeholder="T">
                                                            <div class="error_box">
                                                                <span class="error_area" data-toggle="tooltip" data-placement="top" title="" data-original-title="Error">
                                                                    <img src="<?= $APPLICATION->GetTemplatePath('images/help_r.svg') ?>" alt="">
                                                                </span>
                                                            </div>*/ ?>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="recalc" name="price_s" data-code="SUPPLIER_PRICE_UNIT"
                                                                   value="<?= $item->getSupplierPriceUnit()->getValue() ?>">
                                                        </td>
                                                        <td class="total">
                                                            <?= $item->getSupplierQuantity()->getValue() * $item->getSupplierPriceUnit()->getValue() ?>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="redact_area"
                                                                   placeholder="4 weeks"
                                                                   name="delivery_time"
                                                                   data-code="DELIVERY_TIME"
                                                                   value="<?= $item->getDeliveryTime()->getValue() ?>">
                                                            <div class="error_box">
 				 											<span class="error_area" data-toggle="tooltip" data-placement="top" title="" data-original-title="Error">
 				 												<img src="<?= $APPLICATION->GetTemplatePath('images/help_r.svg') ?>" alt="">
 				 											</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <input type="text" class="redact_area"
                                                                   placeholder="FSA Dombrova"
                                                                   name="incoterms"
                                                                   data-code="INCOTERMS"
                                                                   value="<?= $item->getIncoterms()->getValue() ?>">
                                                            <div class="error_box">
 				 											<span class="error_area" data-toggle="tooltip" data-placement="top" title="" data-original-title="Error">
 				 												<img src="<?= $APPLICATION->GetTemplatePath('images/help_r.svg') ?>" alt="">
 				 											</span>
                                                            </div>
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
                                                        <td>
                                                            <input type="text"
                                                                   class="redact_area"
                                                                   name="comment_s"
                                                                   data-code="SUPPLIER_COMMENT"
                                                                   value="<?= $item->getSupplierComment()->getValue() ?>">
                                                        </td>
                                                    </tr>
                                                <? } ?>
                                                <tr>
                                                    <td>Seamless pipe 12" SCH 140 (323,85х28,58) ASTM A312 (Gr. TP347)</td>
                                                    <td>-</td>
                                                    <td>10,61</td>
                                                    <td>T</td>
                                                    <td>
                                                        <input type="text" class="redact_area error" id="" placeholder="12,60">
                                                        <div class="error_box">
 				 											<span class="error_area" data-toggle="tooltip" data-placement="top" title="" data-original-title="Error">
 				 												<img src="<?= $APPLICATION->GetTemplatePath('images/help_r.svg') ?>" alt="">
 				 											</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="redact_area" id="" placeholder="T">
                                                    </td>
                                                    <td>100,61</td>
                                                    <td>100,61</td>
                                                    <td>
                                                        <input type="text" class="redact_area" id="" value="2 weeks">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="redact_area" id="" placeholder="FSA Dombrova">
                                                    </td>
                                                    <td>
                                                        <label class="checkbox-transform">
                                                            <input type="checkbox" class="checkbox__input">
                                                            <span class="checkbox__label"></span>
                                                        </label>

                                                    </td>
                                                    <td>
                                                        <input type="text" class="redact_area" id="" placeholder="">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Seamless pipe 14" SCH 140 (355,6х31,75) ASTM A312 (Gr. TP347)</td>
                                                    <td>-</td>
                                                    <td>8,40</td>
                                                    <td>T</td>
                                                    <td>
                                                        <input type="text" class="redact_area error" id="" value="1,7">
                                                        <div class="error_box">
 				 								 			<span class="error_area" data-toggle="tooltip" data-placement="top" title="" data-original-title="Error">
 				 								 				<img src="<?= $APPLICATION->GetTemplatePath('images/help_r.svg') ?>" alt="">
 				 								 			</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="redact_area" id="" value="m">
                                                    </td>
                                                    <td>800,40</td>
                                                    <td>800,40</td>
                                                    <td>
                                                        <input type="text" class="redact_area" id="" placeholder="4 weeks">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="redact_area" id="" placeholder="FSA Dombrova">
                                                    </td>
                                                    <td>
                                                        <label class="checkbox-transform">
                                                            <input type="checkbox" class="checkbox__input" checked="checked">
                                                            <span class="checkbox__label"></span>
                                                        </label>

                                                    </td>
                                                    <td>
                                                        <input type="text" class="redact_area" id="" value="Gr. TP3448">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Seamless pipe 20" SCH 160 (508х50,01) ASTM A312 (Gr. TP347)</td>
                                                    <td>-</td>
                                                    <td>1,70</td>
                                                    <td>T</td>
                                                    <td>
                                                        <input type="text" class="redact_area error" id="" placeholder="12,60">
                                                        <div class="error_box">
 				 								  			<span class="error_area" data-toggle="tooltip" data-placement="top" title="" data-original-title="Error">
 				 								  				<img src="<?= $APPLICATION->GetTemplatePath('images/help_r.svg') ?>" alt="">
 				 								  			</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="redact_area" id="" placeholder="T">
                                                    </td>
                                                    <td>100,70</td>
                                                    <td>100,70</td>
                                                    <td>
                                                        <input type="text" class="redact_area" id="" placeholder="4 weeks">
                                                    </td>
                                                    <td>
                                                        <input type="text" class="redact_area" id="" placeholder="FSA Dombrova">
                                                    </td>
                                                    <td>
                                                        <label class="checkbox-transform">
                                                            <input type="checkbox" class="checkbox__input">
                                                            <span class="checkbox__label"></span>
                                                        </label>

                                                    </td>
                                                    <td>
                                                        <input type="text" class="redact_area" id="" placeholder="">
                                                    </td>
                                                </tr>
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
                                                    <b>Total price of request </b>
                                                </p>
                                            </div>
                                            <div class="col-xs-12 col-sm-9 col-md-9">
                                                <span class="total-price">0</span>
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
                                            <button type="submit" class="btn btn-spec_it">Send reply</button>
                                            <a class="btn btn-link" href="/personal/requests/">
                                                Show raw rows
                                                <span class="badge badge-light"><?= $specification->count() ?></span>
                                            </a>
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
                            Back to Requests
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>