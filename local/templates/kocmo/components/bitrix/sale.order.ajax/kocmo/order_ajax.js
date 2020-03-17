$(document).ready(function () {

    blockSelected($('[name="ORDER_PROP_21"]').val(), false);
    blockSelectedReset();
    $('body').on('change', '[name="ORDER_PROP_21"]', function () {
        var val = $(this).val();
        blockSelected(val);
    });

    $('body').on('change', '.basket-additional .js_radio', function () {

        if ($('.basket-price__item.delivery').length) {

            var val = $(this).parents('.basket-shipment__item').find(':checked').val();
            var sum = parseFloat(document.querySelector('.basket-price .basket-price__total-sum').dataset.total);
            var delivery = parseFloat($('.basket-price__item.delivery .basket-price__item-sum').text());

            if (val == 3 || val == 5) {

                $('.basket-price__item.delivery').hide();
                var itog = sum - delivery;
                $('.basket-price .basket-price__total-sum').text(itog.toFixed(2));
            }
            else {

                $('.basket-price__item.delivery').show();
                //var itog = sum + delivery;
                var itog = sum;
                $('.basket-price .basket-price__total-sum').text(itog.toFixed(2))
            }
        }

    });
});

function blockSelectedReset() {
    var date = $('[name="ORDER_PROP_21"]').val();
    if (date) {
        var int = $('[name="ORDER_PROP_23"]').val();
        var rend=$('[name="ORDER_PROP_23"]').parents('.form-field').find('.select2-selection__rendered').html();
        var disabled='';
        $('[name="ORDER_PROP_23"] option').each(function( index ) {
            if( $(this).text()==rend){
                disabled=$(this).attr('disabled');
            }
        });
        if(disabled){
            resetProp23();
        }
    }
}

function resetProp23() {
    $('[name="ORDER_PROP_23"] option[value=""]').prop('selected', true);
    $('[name="ORDER_PROP_23"] option[value=""]').attr('selected', 'true');
    MainJs.customSelectInit();
    MainJs.customSelectFieldInit();
}

function blockSelected(val, reset = true) {
    var arD = explode('.', val);
    var dateN = new Date(parseInt(arD[2]), parseInt(arD[1]) - 1, parseInt(arD[0]));
    var yearN = dateN.getFullYear();
    var mothN = dateN.getMonth();
    var dayN = dateN.getDate();
    var date = new Date();
    var year = date.getFullYear();
    var moth = date.getMonth();
    var day = date.getDate();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var sumH = hours * 60 + minutes;
    var i1 = true;
    var i2 = true;
    var i3 = true;
    if (year == yearN && day == dayN && moth == mothN) {
        if (sumH >= 10 * 60 + 1 && sumH <= 14 * 60) {
            SetIntervalOrder(0, 1, 1, reset);
            i1 = false;
            i2 = true;
            i3 = true;
        }
        if (sumH >= 14 * 60 + 1 && sumH <= 18 * 60) {
            SetIntervalOrder(0, 0, 1, reset);
            i1 = false;
            i2 = false;
            i3 = true;
        }
    } else {
        SetIntervalOrder(1, 1, 1, reset);
    }

    if (NoDate[val] !== undefined) {
        var ins = NoDate[val];
        if (ins.IN1) {
            i1 = false;
        }
        if (ins.IN2) {
            i2 = false;
        }
        if (ins.IN3) {
            i3 = false;
        }
        SetIntervalOrder(i1, i2, i3, reset);
    }
}

function explode(delimiter, string) {	// Split a string by string
    var emptyArray = {0: ''};
    if (arguments.length != 2
        || typeof arguments[0] == 'undefined'
        || typeof arguments[1] == 'undefined') {
        return null;
    }

    if (delimiter === ''
        || delimiter === false
        || delimiter === null) {
        return false;
    }

    if (typeof delimiter == 'function'
        || typeof delimiter == 'object'
        || typeof string == 'function'
        || typeof string == 'object') {
        return emptyArray;
    }

    if (delimiter === true) {
        delimiter = '1';
    }

    return string.toString().split(delimiter.toString());
}

function changeCalendar() {
    var el = $('[id ^= "calendar_popup_"]'); //найдем div  с календарем
    var links = el.find(".bx-calendar-cell"); //найдем элементы отображающие дни
    $('.bx-calendar-left-arrow').attr({'onclick': 'changeCalendar();',}); //вешаем функцию изменения  календаря на кнопку смещения календаря на месяц назад
    $('.bx-calendar-right-arrow').attr({'onclick': 'changeCalendar();',}); //вешаем функцию изменения  календаря на кнопку смещения календаря на месяц вперед
    $('.bx-calendar-top-month').attr({'onclick': 'changeMonth();',}); //вешаем функцию изменения  календаря на кнопку выбора месяца
    $('.bx-calendar-top-year').attr({'onclick': 'changeYear();',}); //вешаем функцию изменения  календаря на кнопку выбора года
    var date = new Date();

    var year = date.getFullYear();
    var moth = date.getMonth();
    var day = date.getDate();
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var sumH = hours * 60 + minutes;

    for (var i = 0; i < links.length; i++) {
        var atrDate = links[i].attributes['data-date'].value;
        var d = date.valueOf();
        var g = links[i].innerHTML;
        if (date - atrDate > 24 * 60 * 60 * 1000) {
            $('[data-date="' + atrDate + '"]').addClass("bx-calendar-date-hidden disabled"); //меняем класс у элемента отображающего день, который меньше по дате чем текущий день
        }
        var dateN = new Date(parseInt(atrDate));
        var yearA = dateN.getFullYear();
        var mothA = dateN.getMonth();
        var dayA = dateN.getDate();
        var mothAn = mothA + 1;
        if (dayA < 10) {
            dayA = '0' + dayA;
        }
        if (mothAn < 10) {
            mothAn = '0' + mothAn;
        }
        var keyDate = dayA + '.' + mothAn + '.' + yearA;
        if (year == yearA && day == dayA && moth == mothA) {
            if (sumH >= 18 * 60 + 1) {
                $('[data-date="' + atrDate + '"]').addClass("bx-calendar-date-hidden disabled");
            }
        }
        if (NoDate[keyDate] !== undefined) {
            var ins = NoDate[keyDate];
            if (ins.IN1 && ins.IN2 && ins.IN3) {
                $('[data-date="' + atrDate + '"]').addClass("bx-calendar-date-hidden disabled");
            }
        }
    }

}

function SetIntervalOrder(i1, i2, i3, reset) {
    if (reset) {
        resetProp23();
    }

    if (i1 == true) {
        ViewIntervalOrder(1);
    } else {
        BlockIntervalOrder(1);
    }
    if (i2 == true) {
        ViewIntervalOrder(2);
    } else {
        BlockIntervalOrder(2);
    }
    if (i3 == true) {
        ViewIntervalOrder(3);
    } else {
        BlockIntervalOrder(3);
    }
}


function BlockIntervalOrder(val) {
    var int1 = $('[value="943d8315-20a6-11ea-a258-00505601048d"]');
    var int2 = $('[value="a1b75f33-20a6-11ea-a258-00505601048d"]');
    var int3 = $('[value="aa5bdb7e-20a6-11ea-a258-00505601048d"]');
    if (val == 1) {
        int1.attr("disabled", "disabled");
    }
    if (val == 2) {
        int2.attr("disabled", "disabled");
    }
    if (val == 3) {
        int3.attr("disabled", "disabled");
    }
}

function ViewIntervalOrder(val) {
    var int1 = $('[value="943d8315-20a6-11ea-a258-00505601048d"]');
    var int2 = $('[value="a1b75f33-20a6-11ea-a258-00505601048d"]');
    var int3 = $('[value="aa5bdb7e-20a6-11ea-a258-00505601048d"]');
    if (val == 1) {
        int1.removeAttr("disabled");
    }
    if (val == 2) {
        int2.removeAttr("disabled");
    }
    if (val == 3) {
        int3.removeAttr("disabled");
    }
}


function changeMonth() {
    var el = $('[id ^= "calendar_popup_month_"]'); //найдем div  с календарем
    var links = el.find(".bx-calendar-month");
    for (var i = 0; i < links.length; i++) {
        var func = links[i].attributes['onclick'].value;
        $('[onclick="' + func + '"]').attr({'onclick': func + '; changeCalendar();',}); //повесим событие на выбор месяца
    }
}

function changeYear() {
    var el = $('[id ^= "calendar_popup_year_"]'); //найдем div  с календарем
    var link = el.find(".bx-calendar-year-input");
    var func2 = link[0].attributes['onkeyup'].value;
    $('[onkeyup="' + func2 + '"]').attr({'onkeyup': func2 + '; changeCalendar();',}); //повесим событие на ввод года
    var links = el.find(".bx-calendar-year-number");
    for (var i = 0; i < links.length; i++) {
        var func = links[i].attributes['onclick'].value;
        $('[onclick="' + func + '"]').attr({'onclick': func + '; changeCalendar();',}); //повесим событие на выбор года
    }
}

document.addEventListener('DOMContentLoaded', function () {

    var shipmentWrap = document.querySelector('.basket-shipment__inner');

    if(shipmentWrap) {
        shipmentWrap.addEventListener('click', function (event) {

            if (!event.target.classList.contains('js_basket-radio')) {
                return false;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/cart/?AJAX_PAYMENT=Y&test=Y');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.querySelector('.basket-payment__inner').outerHTML = xhr.responseText;
                    MainJs.init({
                        fullWidthSlider: '.js_full-width-slider',
                        basketRadio: '.js_basket-radio',
                    });
                }
            };
            var deliveryId = event.target.value;
            xhr.send("AJAX_PAYMENT=" + encodeURIComponent('Y') + "&test=" + encodeURIComponent('Y')
                + "&DELIVERY_ID=" + encodeURIComponent(deliveryId));
        });
    }

    $('[name="ORDER_PROP_37"]').inputmask('999999');

    var locations = document.querySelector('[list="ORDER_PROP_5"]');

    if(locations){
        locations.addEventListener('input', function (event) {

            if(event.target.value.length >= 3){

                let val = event.target.value;

                setTimeout(function () {

                    if(val !== locations.value){
                        //console.log(val);
                        return;
                    }

                    let dataList = document.getElementById('ORDER_PROP_5');
                    dataList.innerHTML = "";

                    let xhr = new XMLHttpRequest();
                    xhr.open('GET', '/ajax/getLocation.php?loc=' + event.target.value);

                    xhr.onload = function () {

                        if (xhr.status === 200 && xhr.responseText) {

                            let resData = JSON.parse(xhr.responseText);

                            if( resData ){

                                for( let key in resData){
                                    let option = document.createElement('option');
                                    option.value = resData[key]['NAME_RU'] + ', ' + resData[key]['PARENT_RU_NAME'];
                                    //option.textContent = resData[key]['PARENT_RU_NAME'];
                                    dataList.appendChild(option);
                                }
                            }
                        }
                    };
                    xhr.send();

                }, 500);
            }
        })
    }

    if(window.DELIVERY_ID && window.DELIVERY_ID == 5) {

        let step2_btn = document.querySelector('.step2-submit');

        if (step2_btn) {
            step2_btn.addEventListener('click', function (event) {

                let zipNode = document.querySelector('[name="ORDER_PROP_37"]');
                let zip = parseInt(zipNode.value) + '';

                if(zip && zip.length === 6){
                    zipNode.classList.remove('error');
                }
                else{
                    zipNode.classList.add('error');
                    smoothScroll('personal_data');
                    //alert('Необходимо заполнить почтовый индекс');
                    event.preventDefault();
                }
                //event.preventDefault();
            }, true)
        }
    }
});

function currentYPosition() {
    // Firefox, Chrome, Opera, Safari
    if (self.pageYOffset) return self.pageYOffset;
    // Internet Explorer 6 - standards mode
    if (document.documentElement && document.documentElement.scrollTop)
        return document.documentElement.scrollTop;
    // Internet Explorer 6, 7 and 8
    if (document.body.scrollTop) return document.body.scrollTop;
    return 0;
}


function elmYPosition(eID) {
    var elm = document.getElementById(eID);
    var y = elm.offsetTop;
    var node = elm;
    while (node.offsetParent && node.offsetParent != document.body) {
        node = node.offsetParent;
        y += node.offsetTop;
    } return y;
}


function smoothScroll(eID) {
    var startY = currentYPosition();
    var stopY = elmYPosition(eID);
    var distance = stopY > startY ? stopY - startY : startY - stopY;
    if (distance < 100) {
        scrollTo(0, stopY); return;
    }
    var speed = Math.round(distance / 100);
    if (speed >= 20) speed = 20;
    var step = Math.round(distance / 25);
    var leapY = stopY > startY ? startY + step : startY - step;
    var timer = 0;
    if (stopY > startY) {
        for ( var i=startY; i<stopY; i+=step ) {
            setTimeout("window.scrollTo(0, "+leapY+")", timer * speed);
            leapY += step; if (leapY > stopY) leapY = stopY; timer++;
        } return;
    }
    for ( var i=startY; i>stopY; i-=step ) {
        setTimeout("window.scrollTo(0, "+leapY+")", timer * speed);
        leapY -= step; if (leapY < stopY) leapY = stopY; timer++;
    }
}
