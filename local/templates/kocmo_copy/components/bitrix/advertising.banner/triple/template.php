<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$frame = $this->createFrame()->begin("");

array_multisort(array_column($arResult['BANNERS_PROPERTIES'],'IMAGE_ALT'),SORT_ASC ,$arResult['BANNERS_PROPERTIES']);
$items=array_shift ($arResult['BANNERS_PROPERTIES']);
array_push($arResult['BANNERS_PROPERTIES'],$items);

if ($arResult['BANNERS']) {
    ?>
    <div class="triple-banner triple-banner--home">
        <div class="container">
            <div class="triple-banner__inner">
                <?
                foreach ($arResult['BANNERS_PROPERTIES'] as $arBanner) { ?>
                    <?
                    $img = $arBanner['IMAGE_ID'];
                    $src = CFile::GetPath($img);
                    ?>
                   <a href="<?=$arBanner['URL']?>" target="<?=$arBanner['URL_TARGET']?>" class="triple-banner__item bordered-inner <?=$arBanner['IMAGE_ALT']?>">
                        <img src="<?=WHITE_SQ_330_100?>" data-defer-src="<?= $src ?>">
                    </a>
                    <?
                } ?>
            </div>
        </div>
    </div>
    <?
}
$frame->end();

