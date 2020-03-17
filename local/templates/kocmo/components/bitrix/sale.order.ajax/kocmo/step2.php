<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @var array $arParams
 * @var array $arResult
 * @var CMain $APPLICATION
 * @var CUser $USER
 * @var SaleOrderAjax $component
 * @var string $templateFolder
 */

$ORDER_DATA = $arResult['ORDER_DATA'];
$APPLICATION->SetTitle("");
$BLOCK_PROPS = $arResult['BLOCK_PROPS'];
$BLOCK_PROPS_7 = $BLOCK_PROPS['7'];
$BLOCK_PROPS_6 = $BLOCK_PROPS['6'];
$BLOCK_PROPS_71 = [];
$BLOCK_PROPS_71[] = array_shift($BLOCK_PROPS_7);
$BLOCK_PROPS_71[] = array_shift($BLOCK_PROPS_7);
$BLOCK_PROPS_72 = $BLOCK_PROPS_7;
$BLOCK_PROPS_7 = [];
$BLOCK_PROPS_7[] = $BLOCK_PROPS_71;
$BLOCK_PROPS_7[] = $BLOCK_PROPS_72;
$BLOCK_PROPS_1 = $BLOCK_PROPS['1'];

$PAY_SYSTEM = [];
foreach ($arResult['PAY_SYSTEM'] as $arData) {
    if ($arData['CHECKED'] == 'Y') {
        $PAY_SYSTEM = $arData;
        break;
    }
    $PAY_SYSTEM = $arData;
}
$DELIVERY = [];
foreach ($arResult['DELIVERY'] as $arData) {
    if ($arData['CHECKED'] == 'Y') {
        $DELIVERY = $arData;
        break;
    }
    $DELIVERY = $arData;
}
$arDeliveryParams = $arDeliveryParams[$DELIVERY['ID']];

//pr($DELIVERY, 14);
?>
<div class="form-wrap">
    <form action="" method="post" id="bx-soa-order-form" enctype="multipart/form-data">
        <?= bitrix_sessid_post() ?>
        <input type="hidden" name="PERSON_TYPE" value="<?= (int)$_REQUEST['PERSON_TYPE'] ?>">
        <input type="hidden" name="PAY_SYSTEM_ID" value="<?= (int)$PAY_SYSTEM['ID'] ?>">
        <input type="hidden" name="DELIVERY_ID" value="<?= (int)$DELIVERY['ID'] ?>">
        <? foreach ($BLOCK_PROPS_6 as $block) { ?>
            <input type="hidden" name="ORDER_PROP_<?=$block['ID']?>" value="<?=$block['VALUE']?>">
        <? } ?>
        <input type="hidden" name="SITE_ID" value="<?= SITE_ID ?>">
        <input type="hidden" name="STEP" value="2">
        <input type="hidden" name="soa-action" value="saveOrderAjax">
        <h2 class="form-title container" id="personal_data">Персональные данные</h2>

        <div class="send-order-fields container">
            <? foreach (array_chunk($BLOCK_PROPS_1, 2) as $arB) { ?>
                <div class="send-order-fields__row">
                    <? foreach ($arB as $arProp) { ?>
                        <div class="send-order-fields__half">
                            <? if ($arProp['CODE'] == 'PHONE') { ?>
                                <div class="form-field">
                                    <input name="ORDER_PROP_<?= $arProp['ID'] ?>" value="<?= getPreparePhone2($arProp['VALUE']) ?>"
                                           class="form-field__input phone-mask js_phone-mask"
                                           type="text"
                                           placeholder="+375__-___-__-__">
                                </div>
                            <? } else { ?>
                                <div class="form-field">
                                    <input name="ORDER_PROP_<?= $arProp['ID'] ?>" value="<?= $arProp['VALUE'] ?>"
                                           class="form-field__input" type="text"
                                           placeholder="<?= $arProp['DESCRIPTION'] ?>">
                                </div>
                            <? } ?>
                            <? if ($arProp['CODE'] == 'PHONE') { ?>
                                <label class="checkbox js_checkbox send-order-fields__checkbox">
                                    <input type="checkbox" name="">Перезвонить мне для подтверждения заказа
                                </label>
                            <? } ?>
                        </div>
                    <? } ?>
                </div>
            <? } ?>

            <? foreach ($BLOCK_PROPS_7 as $b7) { ?>

                <div class="send-order-fields__row">
                    <?
                    switch(count($b7)){
                        case 2:
                            $class = 'send-order-fields__half';
                            break;
                        case 3:
                            $class = 'send-order-fields__third';
                            break;
                        case 4:
                            $class = 'send-order-fields__four';
                            break;
                        default:
                            $class = '';
                    }
                    ?>
                        <? foreach ($b7 as $prop) { ?>
                            <div class="<?= $class ?>">
                                <div class="form-field">

                                    <? switch ($prop['CODE']) {
                                        case 'CITY':
                                            ?>
                                            <input name="ORDER_PROP_<?= $prop['ID'] ?>" list="ORDER_PROP_<?= $prop['ID'] ?>"
                                                   value="<?= $prop['VALUE'] ?>" class="form-field__input"
                                                   placeholder="Введите населённый пункт">
                                            <datalist id="ORDER_PROP_<?= $prop['ID'] ?>" style="background: rgb(250, 249, 251);">
                                                <?foreach($arResult['BX_LOCATIONS'] as $location):?>
                                                    <option value="<?=$location['NAME_RU']?>, <?=$location['PARENT_RU_NAME']?>"></option>
                                                <?endforeach;?>
                                            </datalist>
                                            <?
                                            break;
                                        default:
                                            ?>
                                            <input name="ORDER_PROP_<?= $prop['ID'] ?>"
                                                   value="<?= $prop['VALUE'] ?>"
                                                   class="form-field__input"
                                                   type="text"
                                                   placeholder="<?= $prop['NAME'] ?>"
                                                   data-type="<?= $prop['CODE'] ?>">
                                            <?
                                            break;
                                    } ?>
                                </div>
                            </div>
                        <? } ?>
                </div>
            <? } ?>
            <div class="send-order-fields__row">

                    <div class="form-field" style="width:100%;">
                        <textarea name="ORDER_DESCRIPTION" cols="30" rows="5" placeholder="Комментарий к заказу"></textarea>
                    </div>

            </div>
        </div>
        <hr>
        <? if ($arBlockPrice = $arResult['BLOCK_PRICE']) { ?>
            <div class="send-order-details container">
                <div class="send-order-details__item">
                    <div class="send-order-details__title">Оплата:</div>
                    <div class="send-order-details__check">
                        <?= $PAY_SYSTEM['NAME'] ?>
                        <span><?= $PAY_SYSTEM['DESCRIPTION'] ?></span>
                    </div>
                </div>

                <div class="send-order-details__item">
                    <div class="send-order-details__title">Доставка:</div>

                    <div class="send-order-details__check">
                        <?= $DELIVERY['NAME'] ?>
                        <span><?= $DELIVERY['DESCRIPTION'] ?></span>
                    </div>

                    <? if ($STORE = $DELIVERY['STORE']) { ?>
                        <div class="send-order-details__shipment">
                            Адрес магазина:
                            <div class="send-order-details__shipment-inner">
                                <div class="form-field">
                                    <select name="BUYER_STORE" class="js_custom-select">
                                        <? foreach ($STORE as $story) { ?>
                                            <option <?= $story['SELECTED'] ? 'selected' : '' ?> value="<?= $story['ID'] ?>"><?= $story['TITLE'] ?></option>
                                        <? } ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <? } elseif ($arDeliveryParams && $DELIVERY['ID'] != 5) { ?>
                        <? $additional = $arDeliveryParams['additional']; ?>
                        <div class="send-order-details__shipment">
                            Ваш заказ доставят:
                            <div class="send-order-details__shipment-inner">
                                <? foreach ($additional as $prop) { ?>
                                    <? switch ($prop['TYPE']) {
                                        case 'SELECT':
                                            ?>
                                            <div class="form-field">
                                                <select name="ORDER_PROP_<?= $prop['ID'] ?>" class="js_custom-select">
                                                    <option value="" selected><?= $prop['NAME'] ?></option>
                                                    <?
                                                    foreach ($prop['VARIANTS'] as $opt) {
                                                        ?>
                                                        <option <?= $opt['SELECTED'] ? 'selected' : '' ?>
                                                                value="<?= $opt['VALUE'] ?>"><?= $opt['NAME'] ?></option>
                                                        <?
                                                    } ?>
                                                </select>
                                            </div>
                                            <?
                                            break;
                                        case 'DATE':
                                            ?>
                                            <div class="form-field form-field--date">
                                                <input name="ORDER_PROP_<?= $prop['ID'] ?>"
                                                       value="<?= $prop['VALUE_FORMATED'] ?>"
                                                       class="form-field__input"
                                                       type="text"
                                                       onclick="BX.calendar({node: this, field: this, bTime: false});changeCalendar();"
                                                       placeholder="<?= $prop['NAME'] ?>">
                                            </div>
                                            <?
                                            break;
                                    } ?>
                                <? } ?>
                            </div>
                        </div>
                    <? } ?>
                </div>
            </div>
        <? } ?>
        <hr>
        <div class="basket-price container">
            <? if ($arBlockPrice = $arResult['BLOCK_PRICE']) { ?>
                <div class="basket-price__details">
                    <div class="basket-price__item">
                        <div class="basket-price__item-title">Сумма заказа:</div>
                        <div class="basket-price__item-price">
                            <span class="basket-price__item-sum"><?= number_format_kocmo($arBlockPrice['Sum']) ?></span>
                            <span class="basket-price__item-currency"> руб</span>
                        </div>
                    </div>
                    <? if ($arBlockPrice['Economy']) { ?>
                        <div class="basket-price__item">
                            <div class="basket-price__item-title">Ваша экономия:</div>
                            <div class="basket-price__item-price">
                                <span class="basket-price__item-sum"><?= number_format_kocmo($arBlockPrice['Economy']) ?></span>
                                <span class="basket-price__item-currency"> руб</span>
                            </div>
                        </div>
                    <? } ?>
                    <? if ($arBlockPrice['Certificate']) { ?>
                        <div class="basket-price__item">
                            <div class="basket-price__item-title">Оплачено сертификатом:</div>
                            <div class="basket-price__item-price">
                                <span class="basket-price__item-sum"><?= number_format_kocmo($arBlockPrice['Certificate']) ?></span>
                                <span class="basket-price__item-currency"> руб</span>
                            </div>
                        </div>
                    <? } ?>
                    <? if ($arBlockPrice['Delivery']) { ?>
                        <div class="basket-price__item">
                            <div class="basket-price__item-title">Курьерская доставка:</div>
                            <div class="basket-price__item-price">
                                <span class="basket-price__item-sum"><?= number_format_kocmo($arBlockPrice['Delivery']) ?></span>
                                <span class="basket-price__item-currency"> руб</span>
                            </div>
                        </div>
                    <? } ?>
                </div>
            <? } ?>
            <div class="basket-price__divider"></div>

            <div class="send-order-footer">
                <a href="/cart/" class="basket-footer__back">
                    <svg wi\dth="25" height="17">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-pagination-left"></use>
                    </svg>

                    Вернуться в КОРЗИНУ
                </a>

                <div class="basket-price__total">
                    Итого:
                    <span class="basket-price__total-sum"><?= number_format_kocmo($arBlockPrice['Total']) ?></span>
                    <span class="basket-price__total-currency">руб</span>
                </div>

                <button type="submit" onclick="ga('send', 'event', 'pod_zakaz', 'btn_pod_zakaz'); yaCounter47438272.reachGoal('pod_zakaz'); return true;" class="basket-footer__submit btn step2-submit">
                    ПОДТВЕРДИТЬ ЗАКАЗ

                    <svg width="26" height="16">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-right-bold"></use>
                    </svg>
                </button>
            </div>
        </div>
    </form>
</div>
<script>
    var DELIVERY_ID = <?= (int)$DELIVERY['ID'];?>;
</script>
