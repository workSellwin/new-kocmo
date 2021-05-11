<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)) { ?>
    <ul class="header__top-nav">
        <?
        foreach ($arResult as $arItem) {
            if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) continue;
            ?>
            <? if ($arItem["SELECTED"]) { ?>
                <li class="header__top-nav-item">
                    <a href="<?= $arItem["LINK"] ?>" class="selected"><?= $arItem["TEXT"] ?></a>
                </li>
            <? } else { ?>
                <li class="header__top-nav-item">
                    <a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>
                </li>
            <? } ?>
        <? } ?>
    </ul>
<? } ?>