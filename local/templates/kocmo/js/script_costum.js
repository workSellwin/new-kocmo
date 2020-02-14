$(document).ready(function () {
    //ОБЪЕКТ СО ВСЕМИ ITEM (OBJ_ITEMS)
    if (typeof OBJ_ITEMS != 'undefined') {
        initBtnItem(OBJ_ITEMS);
    }


    $('#popup-preorder-item #form_prod_popup').submit(function () {
        var data = $(this).serialize();
        $.ajax({
            url: '/ajax/index.php',
            data: data,
            success: function success(response) {
                response = JSON.parse(response);
                if (response['ERROR']) {
                    $('.subscribe-message').text(response['ERROR']);
                    $('.subscribe-message').css('background-color', '#d01c60');
                    $('.subscribe-message').show();
                } else {
                    $('.subscribe-message').text(response['MESSAGE']);
                    $('.subscribe-message').css('background-color', '#049804');
                    $('.subscribe-message').show();
                }
            },
        });
        return false
    });


    $('body').on('click', '.MY_AJAX_BTN_JS', function () {

        var el = $(this);
        var contener = $(el.attr('data-content'));
        var pagen = 'PAGEN_' + el.attr('data-pagen-nav');
        var data_pagen = contener.find('input:last').attr('data-NavPageNomer');

        contener.css('opacity', '0.5');

        var url = location.href;
        var regexp = /\?/gi;
        if (regexp.test(url)) {
            url += '&' + pagen + '=' + (+data_pagen + 1);
        } else {
            url += '?' + pagen + '=' + (+data_pagen + 1);
        }

        $.post(
            url,
            {
                CONTENT_ID: "AJAX_BTN_JS",
                ACTION: "ajax"
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(data) {
            var obj = $(data);
            var obj3 = $('<div>' + data + '</div>');
            var gg = obj3.find('.container_js');
            contener.append(gg);
            var NavPageCount = gg.find('input:last').attr('data-NavPageCount');
            var NavPageNomer = gg.find('input:last').attr('data-NavPageNomer');

            if (NavPageCount == NavPageNomer) {
                $('.MY_AJAX_BTN_JS').hide();
            } else {
                NavPageNomer++;
                el.attr('data-pagen', NavPageNomer);
            }

            ReloadAjax();

            contener.css('opacity', '1');
        }

    });

});

//сливает два объекта вместе
function mergeJsObj(OBJ_ITEMS, AJAX_ITEMS) {
    OBJ_ITEMS = Object.assign(OBJ_ITEMS, AJAX_ITEMS);
    return OBJ_ITEMS;
}

function initBtnItem(OBJ_ITEMS) {
    if (typeof OBJ_ITEMS != 'undefined') {
        for (ITEM in OBJ_ITEMS) {
            btnItem(ITEM);
        }
    }
}

//изменяет кнопку у продукта (item)
function btnItem(PRODUCT_ID) {
    if (OBJ_ITEMS[PRODUCT_ID]) {
        var item = OBJ_ITEMS[PRODUCT_ID];
        var btn = $('.prod-items-id_' + PRODUCT_ID);
        //лежит ли в карзине товар
        if (item['IS_BASKET'] == 'Y' && item['IS_OFFERS'] != 'Y') {
            btn.addClass('active_btn_basket');
        } else {
            btn.removeClass('active_btn_basket');
        }
        //изменение текста кнопки
        $('.prod-items-id_' + PRODUCT_ID + ' span').text(item['BTN_TEXT']);

    } else {
        console.log('Нет item с таким ID!!!')
    }
}

//Добавить продукт в карзину
function productsItemAdd(PRODUCT_ID) {
    if (OBJ_ITEMS[PRODUCT_ID]) {
        var item = OBJ_ITEMS[PRODUCT_ID];
    }
    if (item['IS_OFFERS'] == 'Y') {
        location = item['URL_DETAIL'];
    }
    if (item['QUANTITY'] <= 0) {
        location = item['URL_DETAIL'];
    } else {
        if (item['IS_BASKET'] == 'N') {
            $.post(
                "/ajax/",
                {
                    ACTION: "addbasket",
                    METHOD: "Add",
                    PARAMS: {
                        'PRODUCT_ID': PRODUCT_ID,
                        'QUANTITY': 1,
                        'ADD_BASKET': 'Y',
                    },
                },
                onAjaxSuccess
            );

            function onAjaxSuccess(response) {
                if (response) {
                    item['IS_BASKET'] = 'Y';
                    item['BTN_TEXT'] = 'Перейти в корзину';
                    btnItem(PRODUCT_ID);
                    addToBasketTracker(PRODUCT_ID);
                    ajaxContent('header_basket');
                    ReloadAjax();
                }
            }
        } else {
            location = item['URL_CART'];
        }
    }
}

//Удалить продукт из маленькой карзины
function productsItemDel(PRODUCT_ID, ID) {
    var item = false;
    if (typeof OBJ_ITEMS != 'undefined') {
        if (typeof OBJ_ITEMS[PRODUCT_ID] != 'undefined') {
            item = OBJ_ITEMS[PRODUCT_ID];
        }

    }
    $.post(
        "/ajax/",
        {
            ACTION: "delbasket",
            METHOD: "Del",
            PARAMS: {
                'PRODUCT_ID': PRODUCT_ID,
                'ID': ID,
                'DEL_BASKET': 'Y',
            },
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(response) {
        if (response) {
            if (item != '') {
                item['IS_BASKET'] = 'N';
                item['BTN_TEXT'] = 'В корзину';
                btnItem(PRODUCT_ID);
            }
            if (typeof offerDef != 'undefined') {
                offerDef(PRODUCT_ID);
            }
            ajaxContent('header_basket_count');
            ajaxContent('header_basket_content');
            ReloadAjax();
        }
    }
}


//Удалить продукт из карзины на странице cart
function BigBasketItemDel(PRODUCT_ID, ID) {

    var item = false;
    if (typeof OBJ_ITEMS != 'undefined') {
        if (typeof OBJ_ITEMS[PRODUCT_ID] != 'undefined') {
            item = OBJ_ITEMS[PRODUCT_ID];
        }
    }

    $.post(
        "/ajax/",
        {
            ACTION: "basket",
            METHOD: "Del",
            PARAMS: {
                'PRODUCT_ID': PRODUCT_ID,
                'ID': ID,
                'DEL_BASKET': 'Y',
            },
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(response) {
        if (response) {
            if (item != '') {
                item['IS_BASKET'] = 'N';
                item['BTN_TEXT'] = 'В корзину';
            }

            var flag = 0;
            $('.basket_item_container .basket__item').each(function () {
                flag++;
            });

            if (flag >= 1) {
                //ajaxContent('ajax_basket_item_container');
                ajaxContent('ajax_basket_price_container');
                ReloadAjax();
            } else {
                location = "/catalog/"
            }
        }
    }

}

// обновление количества товара в карзине
function UpdateProductBasket(PRODUCT_ID, QUANTITY) {
    setTimeout(function () {
        $.post(
            "/ajax/",
            {
                ACTION: "updatebasket",
                METHOD: "Update",
                PARAMS: {
                    'PRODUCT_ID': PRODUCT_ID,
                    'UPDATE_BASKET': 'Y',
                    'FIELDS': {
                        'QUANTITY': QUANTITY,
                    },
                },
            },
            onAjaxSuccess
        );

        function onAjaxSuccess(response) {
            if (response) {
                ajaxContent('header_basket_count');
                ajaxContent('header_basket_content');
                ajaxContent('ajax_basket_item_container');
                ajaxContent('ajax_basket_price_container');
            }
        }
    }, 500);
}

//встовляет контейнер по ID
function ajaxContent($CONTENT_ID) {
    var url = window.location.href;
    $.post(
        url,
        {
            ACTION: 'ajax',
            CONTENT_ID: $CONTENT_ID,
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(response) {
        $('#' + $CONTENT_ID).html(response);
    }
}

//трекинг для модуля Retail Rocket
function addToBasketTracker(productID) {
    rrApi.addToBasket(productID);
}

//трекинг для модуля Retail Rocket
function transactionTracker(obj_product) {
    var items = [];
    var transaction_id = getRandomInt(999999999999, 9999999999999);
    for (variable in obj_product) {
        var item = {
            id: obj_product[variable]['PRODUCT_ID'],
            qnt: obj_product[variable]['QUANTITY'],
            price: obj_product[variable]['BASE_PRICE']
        };
        items.push(item);
    }
}

//получить рендомное число в диопазоне
function getRandomInt(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max);
    return Math.floor(Math.random() * (max - min)) + min; //Максимум не включается, минимум включается
}

//init js
function ReloadAjax() {
    $(document).ready(function () {
        MainJs.setMaxHeights($('.products__container .products-item__description'));
        MainJs.counterInit();
        MainJs.filterDropDownInit();
        MainJs.headerBasketScrollInit();
        $('.fancybox').fancybox()
    });
}

//проверяет есть ли элементы в карзине
function EmptyBasket() {
    $.post(
        "/ajax/",
        {
            ACTION: "basket",
            METHOD: "EmptyBasket",
            PARAMS: {'IS_PRODUCT': 'Y'},
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(response) {
        return response;
    }
}

// нажатие на кнопки + -
function clickPlusMinusCounterButton($this, ID) {
    var this_btn = $($this);
    var input = this_btn.parent().children('input');
    var maxValue = input.attr('data-max-count');
    var input_val = input.val();

    if (this_btn.hasClass('counter__button--up')) {
        if (input_val >= maxValue) {
            input.val(maxValue);
            alert('Максимальное количество товара ' + maxValue);
        } else {
            input_val++;
            input.val(input_val);
            UpdateProductBasket(ID, input_val);
        }
    }
    if (this_btn.hasClass('counter__button--down')) {
        if (input_val <= 1) {
            input.val(1);
        } else {
            input_val--;
            input.val(input_val);
            UpdateProductBasket(ID, input_val);
        }
    }
}

// ввод количества товара
function keyupCounterButton($this, ID) {
    var maxValue = $($this).attr('data-max-count');
    var value = parseInt($($this).val());

    if (event.keyCode == 13) {
        event.preventDefault();
        return false;
    }

    $($this).val($($this).val().replace(/\D/g, '1'));

    if (value <= 0) {
        $($this).val('1');
        UpdateProductBasket(ID, 1);
    } else if (value > maxValue) {
        $($this).val(maxValue);
        UpdateProductBasket(ID, maxValue);
    } else {
        UpdateProductBasket(ID, value);
    }
}

function js_popup_preorder_item(item_id) {
    if (OBJ_ITEMS[item_id]) {
        $item_name = OBJ_ITEMS[item_id]['PROP']['NAME'];
        $item_artikul = OBJ_ITEMS[item_id]['PROP']['ARTIKUL'];
        $item_img = OBJ_ITEMS[item_id]['file_img']['src'];
        $item_text = OBJ_ITEMS[item_id]['PROP']['PREVIEW_TEXT'];

        $('#popup-preorder-item').find(".popup-preorder__product-title").text($item_name);
        $('#popup-preorder-item').find(".popup-preorder__product-sku").text('Артикул: ' + $item_artikul);
        $('#popup-preorder-item').find(".popup-preorder__product-description").text($item_text);
        $('#popup-preorder-item').find(".popup-preorder__img img").attr('src', $item_img);
        $('#popup-preorder-item input.input-popup-preorder-item').val(item_id);
        $('#popup-preorder-item .subscribe-message').hide();

    }
}

function setCookie(name, value, options) {

    options = {
        path: '/',
    };

    if (options.expires && options.expires.toUTCString) {
        options.expires = options.expires.toUTCString();
    }

    var updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

    for (var optionKey in options) {
        updatedCookie += "; " + optionKey;
        var optionValue = options[optionKey];
        if (optionValue !== true) {
            updatedCookie += "=" + optionValue;
        }
    }

    document.cookie = updatedCookie;
}

function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function deleteCookie(name) {
    setCookie(name, "", {
        'max-age': -1
    })
}

//------------------------------------------------ умный фильтр --------------------------------------------------------
function changeSmartFilter($this) {
    $(document).ready(function(){
        $('#AJAX_BTN_JS').css('opacity', '0.3');
        var data = $('#ajax_filter').serialize();
        var url = document.location.pathname + "?ajax=y&" + data;
        NumberElements(data, url);
        $('#AJAX_BTN_JS').css('opacity', '1');
    });

}

function ajaxSmartFilter(url) {
    $.post(
        url,
        {
            ajax_filter: "Y",
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(data)
    {
        var $preloader = $('#AJAX_FILTER_CONTAINER');
        $preloader.addClass('preloader--active');
        $('#AJAX_FILTER_CONTAINER').html(data);
        $preloader.removeClass('preloader--active');
        MainJs.setMaxHeights($('.products__container .products-item__description'));
    }
}

function NumberElements(data, url) {
    BX.ajax.loadJSON(
        url,
        data,
        function (dt) {
            var res = dt['FILTER_AJAX_URL'].replace(/&amp;/g,'&');
            ajaxSmartFilter(res);
            history.pushState(null, null, res)
        }
    );
}

function checkCurr(d) {
    if(window.event)
    {
        if(event.keyCode == 37 || event.keyCode == 39) return;
    }
    d.value = d.value.replace(/\D/g,'');

    changeSmartFilter(true);
}
//----------------------------------------------------------------------------------------------------------------------

//обновление фильтра
function updateSmartFilter() {
    if (typeof FILTER != 'undefined') {



        $.post("/ajax/", {ACTION: "ajaxupdatesmartfilter", METHOD: "UpdateHtml", PARAMS: FILTER}, onAjaxSuccess);

        function onAjaxSuccess(data) {
            var obj = JSON.parse(data);
            //console.log(obj);
            //свойства Статус (ID 54)
            if (obj[54]) {
                $('form#ajax_filter div#prop_54 label').each(function () {
                    $(this).hide();
                });
                var i = 0;
                for (key in obj[54]['VALUES']) {
                    var item = obj[54]['VALUES'][key];
                    $('form#ajax_filter div#prop_54 label[id=ajax_' + item['CONTROL_ID'] + ']').show();
                    i++;
                }
                //console.log(i);
                if (i > 0) {
                    $('form#ajax_filter div#prop_54').parent().parent().show();
                } else {
                    $('form#ajax_filter div#prop_54').parent().parent().hide();
                }
            }
            //свойства бренды (ID 45)
            if(typeof FILTER.PARAMS.FILTER_PROPERTY_AJAX['=PROPERTY_45'] == 'undefined'){
                //delete FILTER.PARAMS.FILTER_PROPERTY_AJAX['=PROPERTY_45'];
                if (obj[45]) {
                    $('form#ajax_filter div#prop_45 label').each(function () {
                        $(this).hide();
                    });
                    var i = 0;
                    for (key in obj[45]['VALUES']) {
                        var item = obj[45]['VALUES'][key];
                        $('form#ajax_filter div#prop_45 label[id=ajax_' + item['CONTROL_ID'] + ']').show();
                        i++;
                    }

                    if (i > 0) {
                        $('form#ajax_filter div#prop_45').parent().parent().show();
                    } else {
                        $('form#ajax_filter div#prop_45').parent().parent().hide();
                    }
                }
            }
            if(obj['ROZNICHNAYA']){
                if(obj['ROZNICHNAYA']['VALUES']['MIN']['VALUE']){
                    $('form#ajax_filter input#filter_catalog_prod_P2_MIN').attr('placeholder', obj['ROZNICHNAYA']['VALUES']['MIN']['VALUE']);
                }
                if(obj['ROZNICHNAYA']['VALUES']['MAX']['VALUE']){
                    $('form#ajax_filter input#filter_catalog_prod_P2_MAX').attr('placeholder', obj['ROZNICHNAYA']['VALUES']['MAX']['VALUE']);
                }
            }
            //var obj = $(data);
            //var obj3 = $('<div>' + data + '</div>');
            //var gg = obj3.find('.ajax_container_filter');
            //$('.ajax_container_filter').html(gg);
        }
    }
}
