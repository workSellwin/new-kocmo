<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (empty($arResult["CATEGORIES"]) || !$arResult['CATEGORIES_ITEMS_EXISTS'])
    return;

$items = $arResult["CATEGORIES"][1]['ITEMS'];
$itemsId = array_column($items, 'ITEM_ID');
?>
<? //pr($arResult["ELEMENTS"],14);?>
<? // pr($arResult["CATEGORIES"], 14); ?>
<div class="bx_searche">
    <? if (count($arResult["SECTIONS"])): ?>
        <ul class="search-section-list">
            <? foreach ($arResult["SECTIONS"] as $section_id => $section): ?>
                <li data-id="<?= $section_id ?>"><a
                            href="<?= $section["SECTION_PAGE_URL"] ?>"><?= $section["NAME"] ?></a></li>
            <? endforeach; ?>
        </ul>
    <? endif; ?>
    <? foreach ($arResult["CATEGORIES"] as $category_id => $arCategory): ?>
        <div id="mob-filter-category-<?= $category_id ?>">
            <? foreach ($arCategory["ITEMS"] as $i => $arItem): ?>
                <? if ($category_id === "all"): ?>
                    <div class="bx_item_block all_result">
                        <div class="bx_img_element"></div>
                        <div class="bx_item_element">
                            <span class="all_result_title"><a
                                        href="<? echo $arItem["URL"] ?>"><? echo $arItem["NAME"] ?></a></span>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                <? elseif (isset($arResult["ELEMENTS"][$arItem["ITEM_ID"]])):

                    $arElement = $arResult["ELEMENTS"][$arItem["ITEM_ID"]]; ?>

                    <div class="bx_item_block">

                        <div class="bx_item_element">
                            <div class="brand-text-block">

                                <? if (is_array($arElement["PICTURE"])): ?>
                                    <div class="bx_img_element">
                                        <div class="bx_image"
                                             style="background-image: url('<?= $arElement["PICTURE"]["src"] ?>');
                                                     width: <?= $arParams['PREVIEW_WIDTH']; ?>px;
                                                     height: <?= $arParams['PREVIEW_HEIGHT']; ?>px;"></div>
                                    </div>
                                <? endif; ?>
                                <? if (!empty($arElement["PROPERTY_MARKA_VALUE"])): ?>
                                    <a href="<?= '/brands/' . CUtil::translit($arElement["PROPERTY_MARKA_VALUE"], 'ru') . '/' ?>"
                                       class="filter-brand-name">
                                        <?= $arElement["PROPERTY_MARKA_VALUE"] ?>
                                    </a>
                                <? endif; ?>
                                <a class="filter-elem-text" href="<?= $arItem["URL"] ?>"><?= $arItem["NAME"] ?></a>
                            </div>

                            <div class="filter-elem-price-block">
                                <?
                                $prices = $arElement["PRICES"];
                                //pr($prices, 14);
                                $oldPrice = false;

                                if (
                                    intval($prices['ROZNICHNAYA']["DISCOUNT_VALUE"]) > 0
                                    && isset($prices['AKTSIONNAYA'])
                                    && intval($prices['AKTSIONNAYA']["DISCOUNT_VALUE"]) > 0
                                ) {

                                    if ($prices['ROZNICHNAYA']["DISCOUNT_VALUE"] > $prices['AKTSIONNAYA']["DISCOUNT_VALUE"]) {
                                        $minPrice = $prices['AKTSIONNAYA']["PRINT_DISCOUNT_VALUE"];
                                        $oldPrice = $prices['ROZNICHNAYA']["PRINT_DISCOUNT_VALUE"];
                                    } else {
                                        $minPrice = $prices['ROZNICHNAYA']["PRINT_DISCOUNT_VALUE"];
                                        $oldPrice = $prices['AKTSIONNAYA']["PRINT_DISCOUNT_VALUE"];
                                    }
                                } else {
                                    $minPrice = $prices['ROZNICHNAYA']["PRINT_DISCOUNT_VALUE"];
                                }
                                if (intval($oldPrice) > 0):?>
                                    <? $discountPercent = round((($oldPrice - $minPrice) / $oldPrice) * 100, 2); ?>
                                    <div class="bx_price">
                                        <div class="old"><?= $oldPrice ?></div>

                                        <div class="filter-item__labels">
                                            <div class="filter-item__label filter-item__label--discount"><?= $discountPercent ?>
                                                %
                                            </div>
                                        </div>
                                        <div class="current_price"><?= $minPrice ?></div>
                                    </div>
                                <? else: ?>
                                    <div class="bx_price"><?= $minPrice ?></div>
                                <?endif;
                                ?>
                            </div>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                <? else: ?>
                    <!--                </div>-->
                    <!--                <div id="filter-other-result">-->
                    <!--                    <div class="bx_item_block others_result">-->
                    <!--                        <div class="bx_img_element"></div>-->
                    <!--                        <div class="bx_item_element">-->
                    <!--                            <a href="--><? // echo $arItem["URL"] ?><!--">-->
                    <!--                                <span>ПОКАЗАТЬ ВСЕ РЕЗУЛЬТАТЫ ПОИСКА</span>-->
                    <!--                                <svg width="21" height="8">-->
                    <!--                                    <use class="svg-arrow-right" xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-arrow-right"></use>-->
                    <!--                                </svg>-->
                    <!--                            </a>-->
                    <!--                        </div>-->
                    <!--                        <div style="clear:both;"></div>-->
                    <!--                    </div>-->
                    <!--                </div>-->
                    <? break; ?>
                <? endif; ?>
            <? endforeach; ?>
        </div>
    <? endforeach; ?>
</div>