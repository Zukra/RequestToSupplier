"use strict";

$(function () {
    var requestForm     = $('form[name="request"]'),
        blockNewContact = requestForm.find('.new-contact'),
        blockContact    = requestForm.find('.general-term select[name=contact]'),
        oldValueContact = blockContact.val(),
        count           = 0,
        max             = 3, // 5,
        delay           = 5, //25,
        timer           = 0;

    $('.gen_ststus .saving_data').hide();

    $('.specification .specification-item [name=price_s]').each(function (index, item) {
        var cleave = new Cleave(item, {
            numeral: true,
            delimiter: '',
            numeralDecimalMark: '.',
            numeralDecimalScale: 2,
            numeralPositiveOnly: true,
            // numeralThousandsGroupStyle: 'thousand'
        });
        if ($(item).val() < 0.0001) {
            $(item).val('');
        }
    })

    recalcTotal();
    setRawRowsCounter();

    // setBackground();
    setTextColor();

    autoHeightTextArea();
    setDeliveryTimePlaceholder();
    setIncotermsPlaceholder();

    requestForm.find('.general-term [name=delivery_time]').keyup(function () {
        setDeliveryTimePlaceholder(this);
    })

    requestForm.find('.general-term [name=incoterms]').keyup(function () {
        setIncotermsPlaceholder(this);
    })

    requestForm.find('[name=price_s]').focus(function () {
        $(this).select();
    })

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

        requiredElements = requestForm.find('.specification input:required');

        requiredElements.each(function (index, item) {
            // console.log(item);
            /*if (item.value.length === 0) {
                $(item).parent().addClass('has-error');
                isError = true;
            }*/
        });
        if (isError) {
            requestForm.find('.has-error').first().focus();
            return false;
        }

        var id = $('form[name="request"] input[name="request-id"]').val();
        // var request1cId = $('form[name="request"] input[name="request-1c"]').val(),
        //     token       = $('form[name="request"] input[name="request-token"]').val(),
        //     key         = $('form[name="request"] input[name="key"]').val();

        var params = {request_id: id};

        $('.form_gen_it').addClass('no_active');
        BX.showWait();
        BX.ajax.runComponentAction('zkr:ajax',
            'sendRequestData', {mode: 'class', data: {params: params}}
        ).then(function (response) {
            if (response.status === 'success') {
                if (response.data.status === 1) {
                    location.href = "/personal/requests/";
                } else {
                    errorHandling(response.data.errors);
                }
            }
            BX.closeWait();
            $('.form_gen_it').removeClass('no_active');
        }).catch(function (reason) {
            BX.closeWait();
            console.log(reason);
        });
        // location.href = location.href.split('?')[0];
        // location.reload();
    });

    $('form[name="request"] .general-term input').blur(function (event) {
        var value = $(this).val();
        $(this).val(value.trim());

        updateGeneralTerm(this);
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
        if (event.target.name === 'replacement') {
            var parent = $(this).parents('.specification-item');
            if ($(this).is(':checked')) {
                parent.find('[name=comment_s]').attr('required', true);
            } else {
                parent.find('[name=comment_s]').attr('required', false);
            }
        }
        updateSpecification(this);
    });

    $('form[name="request"] .specification input, form[name="request"] .specification textarea').keyup(function (event) {
        if (event.keyCode === 13 && event.target.name === 'price_s') {
            var parent = $(this).parents('.specification-item');
            parent.next().find('[name=price_s]').focus();
        }
        // setBackground();
        setTextColor();

        clearTimeout(timer);
        count = 0;
        updateSpecification(this);
    });

    $('form[name="request"] .specification .recalc').keyup(function (event) {
        var __self   = $(this),
            specItem = __self.parents('.specification-item'),
            count1   = parseFloat(specItem.find('.recalc').eq(0).val().replace(',', '.')),
            count2   = parseFloat(specItem.find('.recalc').eq(1).val().replace(',', '.')),
            total    = count1 * count2;

        total = parseFloat(total.toFixed(2));
        specItem.find('.total').html(total >= 0 ? number_format(total, 2, '.', ' ') : 'NaN');

        recalcTotal();
        setRawRowsCounter();

        return true;
    });

    $('form[name="request"] .js-cancel-new-contact').click(function (event) {
        blockNewContact.hide();
        blockContact.val(oldValueContact);
    });

    $('.request-detail .js-take_request_control').click(function (event) {
        var prop = document.getElementById('bitrix_session_id');
        updateGeneralTerm(prop, function () {
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

    $('form[name="request"] .js_raw_rows_counter').click(function (event) {
        var isActive = $(this).hasClass('active');

        $('form[name="request"] .specification .specification-item').each(function (index, item) {
            var price = $(item).find('[name=price_s]').val();
            if (price > 0 && !isActive) {
                $(item).hide();
            } else {
                $(item).show();
            }
        })
        if (isActive) {
            $(this).find('.raw_rows_text').show();
            $(this).find('.all_rows_text').hide();
        } else {
            $(this).find('.raw_rows_text').hide();
            $(this).find('.all_rows_text').show();
        }
    })

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
                    'requestCheckBlocked', {
                        mode: 'class',
                        data: {params: params}
                    }
                ).then(function (response) {
                    if (response.status === 'success') {
                        // если заблокирована другим, то обновление страницы
                        if (response.data.blocked > 0 && propName !== 'bitrix_session_id') {
                            location.reload();
                        } else {
                            // обновление данные
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
                    'requestCheckBlocked', {
                        mode: 'class',
                        data: {params: params}
                    }
                ).then(function (response) {
                    if (response.status === 'success') {
                        // если заблокирована другим, то обновление страницы
                        if (response.data.blocked > 0) {
                            location.reload();
                        }
                        // обновление данные
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
            var price    = $(item).find('input[name=price_s]').val()
                    .replace(',', '.')
                    .replace(/\s/g, ''),
                quantity = $(item).find('input[name=quantity_s]').val()
                    .replace(',', '.');

            price    = price.length < 1 ? 0 : price;
            quantity = quantity.length < 1 ? 0 : quantity;

            var sm = parseFloat(quantity) * parseFloat(price);
            $(item).find('.total').text(number_format(sm, 2, '.', ' '));
            summ += sm;
        });
        total.html(summ >= 0 ? number_format(parseFloat(summ.toFixed(2)), 2, '.', ' ') : 'NaN');
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

    function setDeliveryTimePlaceholder(that = null) {
        if (that === null || that.length < 1) {
            that = document.querySelector('form[name="request"] .general-term [name=delivery_time]');
        }

        var value       = $(that).val().trim(),
            placeholder = that.placeholder;

        requestForm.find('.specification [name=delivery_time]').each(function (index, item) {
            if (item.innerHTML.trim().length < 1) {
                item.placeholder = value ? value : placeholder;
            }
        })
    }

    function setIncotermsPlaceholder(that = null) {
        if (that === null || that.length < 1) {
            that = document.querySelector('form[name="request"] .general-term [name=incoterms]');
        }

        var value       = $(that).val().trim(),
            placeholder = that.placeholder;

        requestForm.find('.specification [name=incoterms]').each(function (index, item) {
            if (item.innerHTML.trim().length < 1) {
                item.placeholder = value ? value : placeholder;
            }
        })
    }

    $('form[name="request"] .specification input, form[name="request"] .specification textarea, form[name="request"] .specification select')
        .keyup(function (event) {
            var row    = $(this).parents('.specification-item'),
                column = $(this).parents('td'),
                name   = $(this).attr('name'),
                nextColumn,
                prevColumn;

            switch (event.keyCode) {
                case 37:
                    // console.log('left');
                    prevColumn = column.prev();
                    for (var i = 0; i < 4; i++) {
                        if (prevColumn.has('input').length > 0) {
                            prevColumn.find('input').focus();
                            break;
                        } else if (prevColumn.has('textarea').length > 0) {
                            prevColumn.find('textarea').focus();
                            break;
                        } else if (prevColumn.has('select').length > 0) {
                            // prevColumn.find('select').focus();
                            // break;
                        }
                        prevColumn = prevColumn.prev();
                    }
                    break;
                case 39:
                    // console.log('right');
                    nextColumn = column.next();
                    for (var i = 0; i < 4; i++) {
                        if (nextColumn.has('input').length > 0) {
                            nextColumn.find('input').focus();
                            break;
                        } else if (nextColumn.has('textarea').length > 0) {
                            nextColumn.find('textarea').focus();
                            break;
                        }
                        nextColumn = nextColumn.next();
                    }
                    break;
                case 38:
                    // console.log('up');
                    row.prev().find('[name=' + name + ']').focus();
                    break;
                case 40:
                    // console.log('down');
                    row.next().find('[name=' + name + ']').focus();
                    break;
            }
        });

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
    $('.gen_ststus .saved_data').hide();
    setTimeout("$('.gen_ststus .saving_data').hide(); $('.gen_ststus .saved_data').show();", 1000);
}

function setRawRowsCounter() {
    var rawRowsCounter = 0;
    $('form[name="request"] .specification .specification-item [name=price_s]').each(function (index, item) {
        if ($(item).val() < 0.0001) {
            rawRowsCounter++;
        }
    })
    $('form[name="request"] .raw_rows_counter').html(rawRowsCounter);
}

function setBackground() {
    $('.specification .specification-item').each(function (index, item) {
        var quantityR = $(item).find('.quantity_r'),
            quantityS = $(item).find('[name=quantity_s]'),
            measureR  = $(item).find('.measure_r'),
            measureS  = $(item).find('[name=unit_s]');

        quantityS.removeClass('background-grey');
        measureS.removeClass('background-grey');
        if (quantityR.text().trim() === quantityS.val().trim()) {
            quantityS.addClass('background-grey');
        }
        if (measureR.text().trim() === measureS.val().trim()) {
            measureS.addClass('background-grey');
        }
    })
}

function setTextColor() {
    $('.specification .specification-item').each(function (index, item) {
        var quantityR = $(item).find('.quantity_r'),
            quantityS = $(item).find('[name=quantity_s]'),
            measureR  = $(item).find('.measure_r'),
            measureS  = $(item).find('[name=unit_s]');

        quantityS.removeClass('text-grey');
        measureS.removeClass('text-grey');
        if (quantityR.text().trim() === quantityS.val().trim()) {
            quantityS.addClass('text-grey');
        }
        if (measureR.text().trim() === measureS.val().trim()) {
            measureS.addClass('text-grey');
        }
    })
}

function autoHeightTextArea() {
    $('textarea').each(function () {
        this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
    }).on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
}

function errorHandling(errors) {
    var accessKey = document.getElementsByName("access_key")[0].value;
    var msg       = typeof errors.status_code !== "undefined"
        ? (errors.status_code + ' ' + errors.error) : '';
    var params    = {
        url: location.href + '&key=' + accessKey,
        errors: errors
    };

    $('#errorSendRequestData').find('.js-replace-msg').html(msg);
    $('#errorSendRequestData').modal('show');

    BX.ajax.runComponentAction('zkr:ajax',
        'errorHandling', {
            mode: 'class',
            data: {params: params}
        }
    ).then(function (response) {
        if (response.status === 'success') {
            // console.log('update general terms!');
            // location.reload();
        }
    }).catch(function (reason) {
        console.log(reason);
    });
}