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

$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
    'LIST' => array(
        'CONT' => 'bx_sitemap',
        'TITLE' => 'bx_sitemap_title',
        'LIST' => 'bx_sitemap_ul',
    ),
    'LINE' => array(
        'CONT' => 'bx_catalog_line',
        'TITLE' => 'bx_catalog_line_category_title',
        'LIST' => 'bx_catalog_line_ul',
        'EMPTY_IMG' => $this->GetFolder() . '/images/line-empty.png'
    ),
    'TEXT' => array(
        'CONT' => 'bx_catalog_text',
        'TITLE' => 'bx_catalog_text_category_title',
        'LIST' => 'bx_catalog_text_ul'
    ),
    'TILE' => array(
        'CONT' => 'bx_catalog_tile',
        'TITLE' => 'bx_catalog_tile_category_title',
        'LIST' => 'bx_catalog_tile_ul',
        'EMPTY_IMG' => $this->GetFolder() . '/images/tile-empty.png'
    )
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));

?>
<? if (!empty($arResult['SECTION']['PATH']) && !empty($arResult['SECTIONS']) || $arResult['SECTION']['DEPTH_LEVEL'] == 0): ?>

    <div class="category-subcategory">
        <div class="container">
            <ul class="category-subcategory__inner">
                <? if (!empty($arResult['SECTION']['PATH']) && !empty($arResult['SECTIONS'])): ?>
                    <? $fruit = array_pop($arResult['SECTION']['PATH']); ?>
                    <li class="category-subcategory__item">
                        <span class="category-subcategory__lnk"><?= $fruit['NAME'] ?></span>
                    </li>
                <? endif; ?>
                <? foreach ($arResult['SECTIONS'] as &$arSection): ?>
                    <li class="category-subcategory__item">
                        <a href="<?= $arSection['SECTION_PAGE_URL'] ?>?available_yes=y"
                           class="category-subcategory__lnk"><span><?= $arSection['NAME'] ?></span></a>
                    </li>
                <? endforeach; ?>
            </ul>
        </div>
    </div>
<? endif; ?>


<?//PR($arResult)?>
