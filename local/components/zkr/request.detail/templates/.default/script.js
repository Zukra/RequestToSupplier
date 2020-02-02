"use strict";

function updateGeneralTerm(that) {
    var elem      = $(that),
        requestId = $('form[name="request"] input[name="request-id"]').val(),
        propName  = elem.attr('name'),
        propValue = elem.val(),
        propId    = elem.data('id'),
        propCode  = elem.data('code');

    if (propCode && requestId) {
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
                // console.log('update general terms!');
            }
        }).catch(function (reason) {
            console.log(reason);
        });
    }
}

function updateSpecification(that) {
    var requestId = $('form[name="request"] input[name="request-id"]').val(),
        prop      = $(that),
        specId    = prop.parents('.specification-item').attr('id'),
        propValue = prop.val(),
        propCode  = prop.data('code');

    if (propCode === 'REPLACEMENT') {
        propValue = prop.is(':checked') ? "1" : "0";
    }

    if (propCode && specId && requestId) {
        var params = {
            request_id: requestId,
            spec_id: specId,
            prop: {code: propCode, value: propValue}
        };

        BX.ajax.runComponentAction('zkr:ajax',
            'updateSpecification', {mode: 'class', data: {params: params}}
        ).then(function (response) {
            if (response.status === 'success') {
                // console.log('specification updated!');
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
    });

    $('form[name="request"] .specification select, form[name="request"] .specification input:checkbox').change(function (event) {
        updateSpecification(this);
    });

    $('form[name="request"] .specification input').keyup(function (event) {
        updateSpecification(this);
    });

    $('form[name="request"] .specification .recalc').keyup(function (event) {
        var __self   = $(this),
            specItem = __self.parents('.specification-item'),
            count1   = parseFloat(specItem.find('.recalc').eq(0).val()),
            count2   = parseFloat(specItem.find('.recalc').eq(1).val()),
            total    = count1 * count2;

        specItem.find('.total').html(total > 0 ? total : 'NaN');

        return true;
    });

});