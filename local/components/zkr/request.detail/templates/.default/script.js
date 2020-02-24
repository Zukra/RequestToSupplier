"use strict";

$(function () {
    $('.gen_ststus .saving_data').hide();

    var requestForm     = $('form[name="request"]'),
        blockNewContact = requestForm.find('.new-contact'),
        blockContact    = requestForm.find('.general-term select[name=contact]'),
        oldValueContact = blockContact.val(),
        count           = 0,
        max             = 20,
        delay           = 50,
        timer           = 0;

    /*var checkUserActivity = function () {
        var requestId = requestForm.find('input[name="request-id"]').val();
        if (requestId) {
            var params = {
                request_id: requestId,
                value: 0
            };
            BX.ajax.runComponentAction('zkr:ajax',
                'setRequestBlockingStatus', {
                    mode: 'class',
                    data: {params: params}
                }
            ).then(function (response) {
                if (response.status === 'success') {
                    location.reload();
                    // changeStatus(BX.message('WAIT_REPLY'));
                }
            }).catch(function (reason) {
                console.log(reason);
            });
        }
    };*/


    recalcTotal();

    $('.js_form_submit').click(function () {
        requestForm.submit();
    });

    requestForm.on('keydown', function (event) {
        if (event.keyCode === 13 && event.target.type !== 'textarea') {
            event.preventDefault();
        }
    });

    requestForm.submit(function (event) {
        event.preventDefault();

        var requiredElements = requestForm.find('.general-term input:required'),
            isError          = false;

        requiredElements.each(function (index, item) {
            if (item.value.length === 0) {
                $(item).parent().addClass('has-error');
                isError = true;
            }
        });
        if (isError) {
            requestForm.find('.has-error').first().focus();
            return false;
        }

        var request1cId = $('form[name="request"] input[name="request-1c"]').val(),
            id          = $('form[name="request"] input[name="request-id"]').val(),
            token       = $('form[name="request"] input[name="request-token"]').val(),
            key         = $('form[name="request"] input[name="key"]').val();

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
                    // console.log(params);
                    // send request data to 1C
                    $.ajax({
                        method: "POST",
                        url: "/1c/api/v2/request.get",
                        data: params
                        // dataType: "json",
                        // contentType: 'application/json'
                    }).done(function (data) {
                        // console.log("data");
                    }).fail(function () {
                    });
                    location.href = "/personal/requests/?key=" + key;
                });
            }
        }).catch(function (reason) {
            console.log(reason);
        });
        // location.href = location.href.split('?')[0];
        // location.reload();
    });

    $('form[name="request"] .general-term select').change(function (event) {
        if ($(this).val() !== "0") {
            if (this.name === 'currency') {
                setTotalCurrency();
            } else if (this.name === 'contact') {
                $('.general_terms .request-by_blocked .blocked-contact').html($(this).find(':selected').html());
            }
            updateGeneralTerm(this);
        } else {
            blockNewContact.show();
        }
    });

    $('form[name="request"] .general-term input, form[name="request"] .general-term textarea').keyup(function (event) {
        clearTimeout(timer);
        count = 0;
        updateGeneralTerm(this);
    });

    $('form[name="request"] .specification select, form[name="request"] .specification input:checkbox').change(function (event) {
        updateSpecification(this);
    });

    $('form[name="request"] .specification input').keyup(function (event) {
        clearTimeout(timer);
        count = 0;
        updateSpecification(this);
    });

    $('form[name="request"] .specification .recalc').keyup(function (event) {
        var __self   = $(this),
            specItem = __self.parents('.specification-item'),
            count1   = parseFloat(specItem.find('.recalc').eq(0).val()),
            count2   = parseFloat(specItem.find('.recalc').eq(1).val()),
            total    = count1 * count2;

        specItem.find('.total').html(total >= 0 ? total : 'NaN');

        recalcTotal();

        return true;
    });

    $('form[name="request"] .js-cancel-new-contact').click(function (event) {
        blockNewContact.hide();
        blockContact.val(oldValueContact);
    });

    $('.request-detail .js-take_request_control').click(function (event) {
        var prop = document.getElementById('bitrix_session_id');
        updateGeneralTerm(prop, function () {
            console.log(prop);
            location.reload();
        });
    });

    $('form[name="request"] .js-add-new-contact').click(function (event) {
        var name    = blockNewContact.find('input[name=new_name]'),
            email   = blockNewContact.find('input[name=new_email]'),
            isError = false;

        if (name.val().length <= 0) {
            name.parent().addClass('has-error');
            isError = true;
        }
        if (email.val().length <= 0) {
            email.parent().addClass('has-error');
            isError = true;
        } else if (!ValidateEmail(email.val())) {
            email.parent().addClass('has-error');
            isError = true;
        }

        if (!isError) {
            addNewContact();
            blockNewContact.hide();
        }

    });

    $('form[name="request"] .new-contact input[name=new_name], form[name="request"] .new-contact input[name=new_email]').keyup(function (event) {
        $(this).parent().removeClass('has-error');
    });

    function updateGeneralTerm(that, callback) {
        if (++count > max) {
            clearTimeout(timer);
            count = 0;

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
                        if (callback && typeof callback === 'function') {
                            callback();
                        }
                        changeStatus(BX.message('BLOCKED_UPDATE'));
                    }
                }).catch(function (reason) {
                    console.log(reason);
                });
            }
        } else {
            timer = setTimeout(updateGeneralTerm, delay, that, callback);
        }
    }

    function updateSpecification(that) {
        if (++count > max) {
            clearTimeout(timer);
            count = 0;

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
                    'updateSpecification', {
                        mode: 'class',
                        data: {params: params}
                    }
                ).then(function (response) {
                    if (response.status === 'success') {
                        // console.log('specification updated!');
                        changeStatus(BX.message('BLOCKED_UPDATE'))
                    }
                }).catch(function (reason) {
                    console.log(reason);
                });
            }
        } else {
            timer = setTimeout(updateSpecification, delay, that);
        }
    }

    function setTotalCurrency() {
        var form            = $('form[name="request"]'),
            totalCurrency   = form.find('.total-currency'),
            currentCurrency = form.find('[name=currency]').val();
        totalCurrency.html(currentCurrency);
    }

    function recalcTotal() {
        var form  = $('form[name="request"]'),
            total = form.find('.total-price'),
            items = form.find('.specification-item'),
            summ  = 0;
        items.each(function (index, item) {
            summ += parseFloat($(item).find('input[name=quantity_s]').val())
                * parseFloat($(item).find('input[name=price_s]').val());
        });
        total.html(summ >= 0 ? parseFloat(summ.toFixed(2)) : 'NaN');
        setTotalCurrency();
    }

    function addNewContact() {
        var requestId  = $('form[name="request"] input[name="request-id"]').val(),
            supplierId = $('form[name="request"] input[name="supplier-id"]').val(),
            name       = blockNewContact.find('input[name=new_name]').val(),
            email      = blockNewContact.find('input[name=new_email]').val();

        if (name && email && requestId) {
            var params = {
                request_id: requestId,
                supplier_id: supplierId,
                name: name,
                email: email
            };

            BX.ajax.runComponentAction('zkr:ajax',
                'addRequestContact', {
                    mode: 'class',
                    data: {params: params}
                }
            ).then(function (response) {
                if (response.status === 'success') {
                    // console.log('update general terms!');
                    location.reload();
                }
            }).catch(function (reason) {
                console.log(reason);
            });
        }
    }
});

function filterSearch(that) {
    var specification = $('.specification'),
        items         = specification.find('.specification-item'),
        inputSearch   = $(that),
        inputString   = $(inputSearch).val();

    items.each(function (index, item) {
        var itemName = $(item).find('.spec_name');
        if (itemName.text().toLowerCase().indexOf(inputString.toLowerCase()) >= 0) {
            $(item).show();
        } else {
            $(item).hide();
        }
    });

    if (inputString.length <= 0) {
        items.show();
        return true;
    }
}

function changeStatus(status) {
    var classColors = JSON.parse(BX.message('classColors')),
        block       = $('.general_terms .gen_ststus');
    for (var prop in classColors) {
        block.removeClass(classColors[prop]);
        // console.log(prop, classColors[prop]);
    }
    block.addClass(classColors[status]);
    block.find('.status').html(status);
    $('.general_terms .request-event').html(status);
    $('.gen_ststus .saving_data').show();
    setTimeout("$('.gen_ststus .saving_data').hide()", 1000);
}