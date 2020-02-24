"use strict";
$(function () {

    /*$('.js-get-key').click(function () {
        BX.ajax.runComponentAction('zkr:ajax',
            'getKey', { // Вызывается без постфикса Action
                mode: 'class',
                data: {
                    params: {
                        email: "test@test.com",
                        request_id: "00001-0002"
                    }
                } // ключи объекта data соответствуют параметрам метода
            })
            .then(function (response) {
                if (response.status === 'success') {
                    console.log(response);
                    $.ajax({
                        method: "POST",
                        url: "/1с/api/v1/request/newKeyEmail",
                        data: {
                            supplier_id: "00011",
                            email: "test@test.com",
                            status: '1'
                        }
                    }).done(function () {
                        console.log("Key updated");
                    });
                }
            });
    });*/

});

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    // if (charCode > 31 && (charCode !== 46 && charCode !== 44 && (charCode < 48 || charCode > 57))) {
    if (charCode > 31 && charCode !== 46 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
/**
 * @return {boolean}
 */
function ValidateEmail(inputText) {
    var mailformat = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/;
    return !!inputText.match(mailformat);
}
