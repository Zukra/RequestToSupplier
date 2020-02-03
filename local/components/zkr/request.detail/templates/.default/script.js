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

function setTotalCurrency() {
    var form            = $('form[name="request"]'),
        totalCurrency   = form.find('.total-currency'),
        currentCurrency = form.find('[name=currency]').val();
    totalCurrency.html(currentCurrency);
}

function recalcTotal() {
    var form            = $('form[name="request"]'),
        total           = form.find('.total-price'),
        items           = form.find('.specification-item'),
        summ            = 0;
    items.each(function (index, item) {
        summ += parseFloat($(item).find('input[name=quantity_s]').val())
            * parseFloat($(item).find('input[name=price_s]').val());
    });
    total.val(summ);
    setTotalCurrency();
}

$(function () {

    recalcTotal();

    $('form[name="request"]').submit(function (event) {
        event.preventDefault();

        if (event.keyCode === 13) {
            event.preventDefault();
        }

        var request1cId = $('form[name="request"] input[name="request-1c"]').val(),
            id          = $('form[name="request"] input[name="request-id"]').val(),
            token       = $('form[name="request"] input[name="request-token"]').val();

        var params = {request_id: id};

        BX.ajax.runComponentAction('zkr:ajax',
            'sendRequestData', {mode: 'class', data: {params: params}}
        ).then(function (response) {
            if (response.status === 'success') {
                // get request
                $.ajax({
                    method: "POST",
                    url: "/api/v2/request.get",
                    data: {id: request1cId, token: token}
                }).done(function (data) {
                    var params = {data: data.result};
                    console.log(params);
                    // send request data to 1C
                    $.ajax({
                        method: "POST",
                        url: "/1c/api/v2/request.get",
                        data: params
                        // dataType: "json",
                        // contentType: 'application/json'
                    }).done(function (data) {
                        // console.log("data");
                    });
                });
            }
        }).catch(function (reason) {
            console.log(reason);
        });

        // location.href = "/";
        // location.href = location.href.split('?')[0];
        // location.reload();
    });

    $('form[name="request"] .general-term select').change(function (event) {
        // setTimeout(function () {
        if(this.name === 'currency'){
            setTotalCurrency();
        }
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

        recalcTotal();

        return true;
    });

});