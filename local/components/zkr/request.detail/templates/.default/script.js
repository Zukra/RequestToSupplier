"use strict";

function updateGeneralTerm(that) {
    var elem      = $(that),
        requestId = $('form[name="request"] input[name="request-id"]').val(),
        propName  = elem.attr('name'),
        propValue = elem.val(),
        propId    = elem.data('id'),
        propCode  = elem.data('code');

    if (propId && requestId) {
        var params = {
            request_id: requestId,
            prop: {
                id: propId,
                name: propName,
                code: propCode,
                value: propValue
            }
        };
        BX.ajax.runComponentAction('zkr:ajax',
            'updateRequest', {
                mode: 'class',
                data: {params: params}
            }
        ).then(function (response) {
            if (response.status === 'success') {
                console.log('update general terms!');
            }
        }).catch(function (reason) {
            console.log(reason);
        });
    }

}

$(function () {

    $('form[name="request"]').submit(function (event) {
        event.preventDefault();
        console.log('submit');
        /*if (event.keyCode === 13) {
            event.preventDefault();
        }*/
        // location.href = "/";
        // location.href = location.href.split('?')[0];
        // location.reload();
    });

    $('form[name="request"] .general-term select').change(function (event) {
        // setTimeout(function () {
            updateGeneralTerm(this);
        // }, 100);
    });

    $('form[name="request"] .general-term input, form[name="request"] .general-term textarea').keyup(function (event) {
        updateGeneralTerm(this);
    })

});