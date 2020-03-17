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

$INPUT_ID = trim($arParams["~INPUT_ID"]);
if (strlen($INPUT_ID) <= 0)
    $INPUT_ID = "title-search-input-desktop";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if (strlen($CONTAINER_ID) <= 0)
    $CONTAINER_ID = "title-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

if ($arParams["SHOW_INPUT"] !== "N"):?>

    <form id="<? echo $CONTAINER_ID ?>" class="header-search field-bordered"
          method="post" action="<? echo $arResult["FORM_ACTION"] ?>" name="">
        <button type="submit" value="" class="header-search__submit">
            <svg width="20" height="18">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-magnifier"></use>
            </svg>
        </button>
        <input id="<? echo $INPUT_ID ?>" class="header-search__text" type="text" name="q"
               value="<?= htmlspecialcharsbx($_REQUEST["q"]) ?>" autocomplete="off" class="header-search__text"
               placeholder="Введите свой поисковый запрос"/>
    </form>

<? endif ?>
<script>
    BX.ready(function () {
        new JCTitleSearchKocmo({
            'AJAX_PAGE': '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
            'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
            'INPUT_ID': '<?echo $INPUT_ID?>',
            'MIN_QUERY_LEN': 2
        });
    });

    BX.addCustomEvent('onAjaxSuccess', function(){
        if(~arguments[1].data.indexOf('<?=$INPUT_ID?>')){
            $('.title-search-result-desktop #filter-category-1').jScrollPane();
        }
    });
</script>

