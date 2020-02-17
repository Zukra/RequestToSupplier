"use strict";
$(function () {

    $('form[name="update-supplier-key"]').submit(function (event) {
        event.preventDefault();
        var email       = $(this).find('input[name="email"]').val(),
            supplierKey = $(this).find('input[name="supplier-key"]').val(),
            requestId   = $(this).find('input[name="request-id"]').val(),
            supplierId  = $(this).find('input[name="supplier-id"]').val();

        var params = {
            email: email,
            request_id: requestId,
            key: supplierKey,
            id: supplierId
        };

        $.ajax({
            method: "POST",
            url: "http://API:1111m@srv-1c.emk.loc:8080/testnew2/hs/1%D1%81/api/v1/request/getKey",
            dataType: 'json',
            processData: false,
            contentType: 'application/json',
            accept: 'application/json',
            data: {
                // supplier_id: params.supplier_id,
                email: params.email,
                request_id: params.request_id
            }
        }).done(function (response) {
            console.log(response);
        }).fail(function (jqXHR, textStatus, errorThrown) {
            // var err = eval("(" + jqXHR.responseText + ")");
            console.log(jqXHR.status, jqXHR.statusText, textStatus, errorThrown);
        });


        /*BX.ajax.runComponentAction('zkr:ajax',
            'getNewSupplierKey', {
                mode: 'class',
                data: {
                    params: params
                }
            }
        ).then(function (response) {
            if (response.status === 'success') {
                params = {
                    email: response.data.email,
                    request_id: response.data.request_id,
                    key: response.data.key,
                    key_expiry: response.data.key_expiry,
                    supplier_id: response.data.supplier_id
                };

                // установить новый ключ и срок действия
                BX.ajax.runComponentAction('zkr:ajax',
                    'updateSupplierKey', {
                        mode: 'class',
                        data: {
                            params: params
                        }
                    }
                ).then(function (response) {
                    if (response.status === 'success') {
                        // отправить запрос в 1С на отправку письма с новым ключом
                        $.ajax({
                            method: "POST",
                            url: "/1с/api/v1/request/newKeyEmail",
                            data: {
                                supplier_id: params.supplier_id,
                                email: params.email,
                                request_id: params.request_id,
                                status: '1'
                            }
                        }).done(function () {
                            console.log("Key updated");
                        });

                        $('#successUpdateKey').modal('show');
                    }
                }).catch(function (reason) {
                    console.log(reason);
                });
            }
        }).catch(function (reason) {
            console.log(reason);
        });*/

    });

    $('#successUpdateKey').on('hidden.bs.modal', function (event) {
        location.href = "/";
        // location.href = location.href.split('?')[0];
        // location.reload();
    })

});