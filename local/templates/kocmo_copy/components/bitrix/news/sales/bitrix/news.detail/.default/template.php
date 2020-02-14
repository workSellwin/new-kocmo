<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$file = CFile::ResizeImageGet($arResult['DETAIL_PICTURE']['ID'], array('width' => 1041, 'height' => 360), BX_RESIZE_IMAGE_PROPORTIONAL, true);
$date = explode(' ', $arResult['DATE_ACTIVE_FROM']);
$PROP = array_column($arResult['PROPERTIES'], 'VALUE', 'CODE');
global $PROP_SALE;
$PROP_SALE = $PROP;
?>


    <div class="sale__img bordered-inner">
        <img src="<?= $file['src'] ?>" alt="">
    </div>

    <div class="sale__date">
        <?= $PROP['TEXT'] ?>
    </div>

    <div class="sale__article">
        <?= $arResult['DETAIL_TEXT'] ?>
    </div>
