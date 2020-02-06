<? if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<?php
/** @var \Bitrix\Iblock\Elements\EO_ElementSupplier $supplier */
$supplier = $arResult['SUPPLIER'];
?>

<ul id="menu" class="nav navbar-nav">
    <li class="hidden-sm hidden-md hidden-lg mebu_name">
        <p>MENU</p>
    </li>
    <li>
        <a href="tel:+48223072044">+48 (22) 307-20-44</a>
    </li>
    <? /*<li class="active">
                                            <a href="/">Contacts</a>
                                        </li>*/ ?>
    <? if ($supplier) { ?>
        <?/*<li class="login_linck">
            <a href="/personal/">requests:<span><?= $supplier->getName() ?></span></a>
        </li>*/?>
        <li role="presentation" class="dropdown supplier-menu">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"
               role="button" aria-haspopup="true" aria-expanded="false">
                <span><?= $supplier->getName() ?></span>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="/personal/">Personal</a>
                </li>
                <li>
                    <a href="/personal/requests/">Requests</a>
                </li>
                <li>
                    <a href="/personal/update-key/">Refresh access key</a>
                </li>
            </ul>
        </li>
    <? } ?>

    <? global $USER ?>
    <? if ($USER->IsAdmin()) { ?>
        <li>
            <a href="/marketplace/">Marketplace</a>
        </li>
    <? } ?>
</ul>
