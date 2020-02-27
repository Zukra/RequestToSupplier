"use strict";
$(function () {
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

/***
 number - исходное число
 decimals - количество знаков после разделителя
 dec_point - символ разделителя
 thousands_sep - разделитель тысячных
 ***/
function number_format(number, decimals, dec_point, thousands_sep) {
    number         = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n          = !isFinite(+number) ? 0 : +number,
        prec       = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep        = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec        = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s          = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + (Math.round(n * k) / k)
                .toFixed(prec);
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s              = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
        .split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '')
        .length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1)
            .join('0');
    }
    return s.join(dec);
}