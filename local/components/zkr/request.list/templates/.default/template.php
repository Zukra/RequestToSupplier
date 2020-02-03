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

    <section class="cont_middle">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1><?= 'List of requests to ' . $supplier->getName() ?></h1>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>Date of creation</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <? foreach ($arResult["ITEMS"] as $arItem) { ?>
                                <tr class="request-item" id="request_<?= $arItem['ID']; ?>">
                                    <td>
                                        <span class="table_color color_new_waiting"></span>
                                    </td>
                                    <td>
                                        <? if (! $arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])): ?>
                                            <a href="<? echo $arItem["DETAIL_PAGE_URL"] ?>">
                                                <? echo $arItem["NAME"] ?>
                                            </a>
                                        <? else: ?>
                                            <? echo $arItem["NAME"] ?>
                                        <? endif; ?>
                                    </td>
                                    <td><?= $arItem["FIELDS"]['TIMESTAMP_X'] ?></td>
                                    <td>
                                        <?= $arItem["DISPLAY_PROPERTIES"]['EVENT']['DISPLAY_VALUE'] ?> / <?= $arItem["DISPLAY_PROPERTIES"]['STATUS']['DISPLAY_VALUE'] ?>
                                    </td>
                                </tr>
                            <? } ?>
                            <tr>
                                <td>
                                    <span class="table_color color_new_waiting"></span>
                                </td>
                                <td>0001/006</td>
                                <td>2019-12-23</td>
                                <td>New, waiting for a reply</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="table_color color_new_blocked"></span>
                                </td>
                                <td>0001/005</td>
                                <td>2019-12-22</td>
                                <td>Request updated by EMK, waiting for reply</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="table_color color_updated_waiting"></span>
                                </td>
                                <td>0001/004</td>
                                <td>2019-12-21</td>
                                <td>New, blocked for update by
                                    <a href="mailto:j.smith@company.com">j.smith@company.com</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="table_color color_updated_blocked"></span>
                                </td>
                                <td>0001/003</td>
                                <td>2019-12-20</td>
                                <td>Reply sent 23.12.2019 09:18:01, blocked for update by
                                    <a href="mailto:j.smith@company.com">j.smith@company.com</a>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="table_color color_sent"></span>
                                </td>
                                <td>0001/002</td>
                                <td>2019-12-19</td>
                                <td>Reply sent 22.12.2019 17:21:19 by
                                    <a href="mailto:j.smith@company.com">j.smith@company.com</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="table_color color_sent_blocked"></span>
                                </td>
                                <td>0001/001</td>
                                <td>2019-12-18</td>
                                <td>Reply sent 23.12.2019 09:18:01, blocked for update by
                                    <a href="mailto:j.smith@company.com">j.smith@company.com</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="wrap_pag">
            <ul class="pagination">
                <li class="navigation_pag">
                    <a href="#"><img src="<?= $APPLICATION->GetTemplatePath('images/lef.svg') ?>" alt=""></a>
                </li>
                <li><a href="#" class="active">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li class="navigation_pag">
                    <a href="#"><img src="<?= $APPLICATION->GetTemplatePath('images/rig.svg') ?>" alt=""></a>
                </li>
            </ul>
        </div>

        <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
            <br/><?= $arResult["NAV_STRING"] ?>
        <? endif; ?>

        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    <span class="table_color color_new_waiting"></span>
                                </td>
                                <td>New, waiting for a reply</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="table_color color_new_blocked"></span>
                                </td>
                                <td>Request updated by EMK, waiting for reply</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="table_color color_updated_waiting"></span>
                                </td>
                                <td>New, blocked for update by
                                    <a href="mailto:j.smith@company.com">j.smith@company.com</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="table_color color_updated_blocked"></span>
                                </td>
                                <td>Reply sent 23.12.2019 09:18:01, blocked for update by
                                    <a href="mailto:j.smith@company.com">j.smith@company.com</a>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="table_color color_sent"></span>
                                </td>
                                <td>Reply sent 22.12.2019 17:21:19 by
                                    <a href="mailto:j.smith@company.com">j.smith@company.com</a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="table_color color_sent_blocked"></span>
                                </td>
                                <td>Reply sent 23.12.2019 09:18:01, blocked for update by
                                    <a href="mailto:j.smith@company.com">j.smith@company.com</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
