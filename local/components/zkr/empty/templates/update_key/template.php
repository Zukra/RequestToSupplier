<? if (! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>

<?php
/** @var \Bitrix\Iblock\Elements\EO_ElementSupplier $supplier */
$supplier = $arResult['SUPPLIER'];
?>

<form name="update-supplier-key" action="#">
    <input type="hidden" name="supplier-key" value="<?= $supplier->getKey()->getValue() ?>">
    <input type="hidden" name="supplier-name" value="<?= $supplier->getName() ?>">
    <input type="hidden" name="request-id" value="<?= $arParams['REQUEST_ID'] ?: '' ?>">
    <input type="hidden" name="supplier-id" value="<?= $supplier->getIdOneC()->getValue() ?>">
    <div class="form-group">
        <label for="email">Email address</label>
        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email" required>
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

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

