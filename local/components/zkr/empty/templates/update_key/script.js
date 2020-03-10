"use strict";
$(function () {

    $('form[name="update-supplier-key"]').submit(function (event) {
        event.preventDefault();

        var email     = $(this).find('input[name="email"]').val(),
            requestId = $(this).find('input[name="request-id"]').val();
        // supplierKey = $(this).find('input[name="supplier-key"]').val(),
        // supplierId  = $(this).find('input[name="supplier-id"]').val();

        var params = {
            email: email,
            request_id: requestId
            // key: supplierKey,
            // id: supplierId
        };

        // request to 1C (get new key)
        BX.ajax.runComponentAction('zkr:ajax',
            'updateSupplierKey', {
                mode: 'class',
                data: {params: params}
            }
        ).then(function (response) {
            if (response.status === 'success' && response.data.status === 1) {
                params = {
                    status: 1,
                    email: response.data.email,
                    supplier_id: response.data.supplier_id
                    // key: response.data.key,
                    // request_id: response.data.request_id
                    // key_expiry: response.data.key_expiry,
                };
                // отправить запрос в 1С на отправку письма с новым ключом
                BX.ajax.runComponentAction('zkr:ajax',
                    'newKeyEmail', {
                        mode: 'class',
                        data: {params: params}
                    }
                ).then(function (response) {
                        if (response.status === 'success') {
                            if (response.data.status < 1) {
                                console.log(response.data.errors);
                            } else {
                                $('#successUpdateKey').modal('show');
                            }
                        }
                    }
                ).catch(function (reason) {
                    console.log(reason);
                });
            } else {
                console.log(response.data.errors);
            }
        }).catch(function (reason) {
            console.log(reason);
        });
    });

    $('#successUpdateKey').on('hidden.bs.modal', function (event) {
        location.href = "/";
        // location.href = location.href.split('?')[0];
        // location.reload();
    })

});