<? if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<?php
/** @var \Bitrix\Iblock\Elements\EO_ElementSupplier $supplier */
$supplier = $arResult['SUPPLIER'];
?>
<section class="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1>EMK for suppliers</h1>
                <form class="form-horizontal" role="form" name="update-supplier-key" action="#">
                    <input type="hidden" name="request-id" value="<?= $arParams['REQUEST_ID'] ?: '' ?>">
                    <? /*
                    <input type="hidden" name="supplier-key" value="<?= $supplier->getKey()->getValue() ?>">
                    <input type="hidden" name="supplier-name" value="<?= $supplier->getName() ?>">
                    <input type="hidden" name="supplier-id" value="<?= $supplier->getIdOneC()->getValue() ?>">
*/ ?>

                    <div class="form-group">
                        <div class="col-xs-12">
                            <input type="email" class="form-control" id="" name="email" placeholder="Email" required>
                        </div>
                    </div>
                    <div class="form-group text_cent">
                        <div class="col-xs-12">
                            <button type="submit" class="green_but">Get a login link</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="successUpdateKey" tabindex="-1" role="dialog" aria-labelledby="successUpdateKeyTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="successUpdateKeyTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                New key sent to email.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
