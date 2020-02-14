"use strict";

document.addEventListener('DOMContentLoaded', function () {

    document.forms['form_auth'].addEventListener('submit', function (event) {


        var userLogin = document.forms['form_auth'].querySelector('input[name="USER_LOGIN"]').value;

        if(!userLogin){
            return false;
        }
        //event.preventDefault();

        userLogin = userLogin.replace(/[^+\d]/g, '');
        console.log(userLogin);
        if( /(\+)?\d{12}/.test(userLogin) ) {

            var actionUrl = document.forms['form_auth'].action;

            var xhr = new XMLHttpRequest();
            xhr.open('get', actionUrl + '?AJAX=Y&USER_LOGIN=' + userLogin);
            xhr.onload = function (data) {
                var objResp = JSON.parse(data.target.responseText);

                if (!objResp || objResp.ERRORS.length) {

                } else {
                    console.log(objResp);
                    var res = objResp.VALUE[0];
                    document.forms['form_auth'].querySelector('input[name="USER_LOGIN"]').value = res;
                    document.forms['form_auth'].submit();
                }
            };
            xhr.send();
            event.preventDefault();
        }
    });
});
