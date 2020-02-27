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
