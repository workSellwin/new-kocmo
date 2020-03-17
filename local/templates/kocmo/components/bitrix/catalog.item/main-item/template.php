<div class="<?= $arResult['CLASS'] ?>">
    <a href="<?= $arResult['ITEM']['DETAIL_PAGE_URL'] ?>" class="products-item__img-wrap"
       style="width: 100%; height: 226px">
        <div class="products-item__labels">
            <? if ($arResult['minPriceOffer']['PERCENT']): ?>
                <div class="products-item__label products-item__label--sale">
                    -<?= $arResult['minPriceOffer']['PERCENT'] ?>%
                </div>
            <? endif; ?>
            <? if (in_array('Новинка', $arResult['PROP']['STATUS'])): ?>
                <div class="products-item__label products-item__label--new">new</div>
            <? endif; ?>
            <? if (in_array('Хит продаж', $arResult['PROP']['STATUS'])): ?>
                <div class="products-item__label products-item__label--bestseller">Хит продаж</div>
            <? endif; ?>
            <? if (in_array('Только в Космо', $arResult['PROP']['STATUS'])): ?>
                <div class="products-item__label products-item__label--kocmo">Только в Космо</div>
            <? endif; ?>

            <div class="products-item__label--add" style="display: none">
                <svg width="44" height="25">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-label-add"></use>
                </svg>
            </div>


        </div>
        <? //PR($arResult['PROP'], 17)?>
        <!-- 290px x 226px -->
        <img src="<?= $arResult['file_img']['src'] ? $arResult['file_img']['src'] : NO_IMG_PATH_225 ?>"
             width="290" height="226" class="products-item__img"
             alt="">
    </a>
    <div class="products-item__title-wrap">
        <a href="<?= $arResult['MARKA_BRAND']['DETAIL_PAGE_URL'] ?>"
           class="products-item__title"><?= $arResult['MARKA_BRAND']['NAME'] ?></a>
        <? if ($arResult['countOffers']): ?>
            <a href="<?= $arResult['ITEM']['DETAIL_PAGE_URL'] ?>"
               class="products-item__options"><?= $arResult['countOffers'] ? $arResult['countOffers'] . ' вариантов' : '' ?></a>
        <? endif; ?>
    </div>
    <div class="products-item__description">
        <?= $arResult['ITEM']['NAME'] ?>
    </div>
    <div class="products-item__reviews">
        <div class="products-item__stars">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:iblock.vote",
                "product_vote",
                array(
                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                    "IBLOCK_ID" => 2,
                    "ELEMENT_ID" => $arResult['ITEM']['ID'],
                    "ELEMENT_CODE" => "",
                    "MAX_VOTE" => "5",
                    "VOTE_NAMES" => array(
                        0 => "1",
                        1 => "2",
                        2 => "3",
                        3 => "4",
                        4 => "5",
                        5 => "",
                    ),
                    "SET_STATUS_404" => "N",
                    "DISPLAY_AS_RATING" => $arParams["VOTE_DISPLAY_AS_RATING"],
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                    "COMPONENT_TEMPLATE" => "stars_main",
                    "MESSAGE_404" => ""
                ),
                $component,
                array(
                    "HIDE_ICONS" => "Y"
                )
            ); ?>
            <? /* <img src="/assets/images/temp/stars.png" alt="">*/ ?>
        </div>
        <? if ($arResult['prop']['COUNT_REVIEWS']): ?>
            <a href="<?= $arResult['ITEM']['DETAIL_PAGE_URL'] ?>#reviews" class="products-item__reviews-lnk"><?= $arResult['prop']['COUNT_REVIEWS'] ?> отзыв<?=BITGetDeclNum($arResult['prop']['COUNT_REVIEWS'])?></a>
        <? endif; ?>
    </div>
    <div class="products-item__price-wrap">
        <? if ($arResult['minPriceOffer']['DISCOUNT'] != 0): ?>
            <div class="products-item__price"><?= number_format($arResult['minPriceOffer']['PRICE_NEW'], 2, '.', ' '); ?>
                <span> руб</span></div>
            <div class="products-item__old-price"><?= number_format($arResult['minPriceOffer']['PRICE_OLD'], 2, '.', ' '); ?>
                <span> руб</span></div>
        <? elseif ($arResult['minPriceOffer']['PRICE_NEW']): ?>
            <div class="products-item__price">
                <?= number_format($arResult['minPriceOffer']['PRICE_NEW'], 2, '.', ' '); ?>
                <span> руб</span>
            </div>
        <? else: ?>
            <div class="products-item__price">

                <span> </span>
            </div>
        <? endif; ?>

    </div>
    <div class="products-item__btns">
        <? if ($arResult['minPriceOffer']['PRICE_NEW'] && $arResult['ITEM']['CATALOG_QUANTITY'] > 0) { ?>
            <a href="javascript:void(0);"
               id="<?= $arResult['ID'] ?>"
               onclick="productsItemAdd(<?= $arResult['minPriceOffer']['PRODUCT_ID'] ?>);ga('send', 'event', 'v_korz2', 'btn_v_korz2'); yaCounter47438272.reachGoal('v_korz2'); return true;"
               class="btn btn--transparent products-item__add prod-items-id_<?= $arResult['minPriceOffer']['PRODUCT_ID'] ?> "
               data-prod-id="<?= $arResult['minPriceOffer']['PRODUCT_ID'] ?>" data-in-basket="<?=$arResult['IS_BASKET']?>">
                <svg width="25" height="25">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-basket"></use>
                </svg>
                <? if ($arResult["ITEM"]['CATALOG_QUANTITY'] > 0 ): ?>
                    <span><?= $arResult['IS_BASKET'] == 'N' ? 'В корзину' : 'Перейти в корзину' ?></span>
                <? else: ?>
                    <span>Предзаказ</span>
                <? endif; ?>
            </a>
        <? } else { ?>
            <a href="#popup-preorder-item" class="btn product__preorder js_product__preorder fancybox preorder"
               onclick="js_popup_preorder_item(<?= $arResult['ITEM']['ID'] ?>);ga('send', 'event', 'predzak2', 'btn_predzak2'); yaCounter47438272.reachGoal('predzak2'); return true;">
                ПРЕДЗАКАЗ
            </a>
        <? } ?>
        <a data-id="<?= $arResult['ITEM']['ID'] ?>" href="#"
           class="btn btn--transparent h2o_add_favor products-item__wishlist">
            <svg width="25" height="25">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-heart"></use>
            </svg>
        </a>
    </div>
</div>
<? //PR($arResult, 17)?>

