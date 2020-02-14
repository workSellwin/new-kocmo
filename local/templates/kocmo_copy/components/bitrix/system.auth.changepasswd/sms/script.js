"use strict";

document.addEventListener('DOMContentLoaded', pageStart);

function pageStart() {
    document.forms.bform.addEventListener('submit', function (event) {

        var currentForm = this;
        event.preventDefault();

        var phone = currentForm.querySelector('input[name="PHONE"]').value;
        var code = currentForm.querySelector('input[name="SMS_CODE"]').value;
        var newPass = currentForm.querySelector('input[name="USER_PASSWORD"]').value;
        var confPass = currentForm.querySelector('input[name="USER_CONFIRM_PASSWORD"]').value;

        if(code.length != 6){
            console.log('sms код должен быть равен 6 символам');
        }
        else if(newPass.length < 6){
            console.log('пароль меньше 6 символов');
        }
        else if(newPass && confPass && newPass !== confPass){
            console.log('пароли не равны');
        }
        else {

            var xhr = new XMLHttpRequest();
            xhr.open('get', '/auth/sms-change.php?AJAX=Y&PHONE=' + phone
                + '&SMS_CODE=' + code + '&PASS=' + newPass);
            xhr.onload = function (data) {322

                var respTxt = data.currentTarget.responseText;
                var respObj = JSON.parse(respTxt);
                console.log(respObj);

                if (!respObj || respObj.ERRORS.length) {

                    for (var i = 0; i < respObj.ERRORS.length; i++) {
                        console.log(respObj.ERRORS[i]);
                    }
                } else if (respObj && respObj.SUCCESS) {

                    //console.log(respObj);
                    //currentForm.querySelector('input[name="USER_LOGIN"]').value = respObj.VALUE[0].USER_LOGIN;
                    //currentForm.querySelector('input[name="USER_CHECKWORD"]').value = respObj.VALUE[0].USER_CHECKWORD;
                    //currentForm.submit();

                    document.querySelector('.success-message').style.display = "block";

                    // setTimeout(function () {
                    //     location.href = '/user/profile/?login=yes';
                    // }, 2000);
                }
            };
            xhr.send();
        }
    })
}
