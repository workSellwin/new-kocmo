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
    $INPUT_ID = "title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if (strlen($CONTAINER_ID) <= 0)
    $CONTAINER_ID = "mob-search";
$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

if ($arParams["SHOW_INPUT"] !== "N"):?>
<div id="popup-mob-search" style="display: none;">
    <a href="#" onclick="$.fancybox.close();return false;" class="popup__fancybox-close" style="z-index:1;"></a>
    <form id="<? echo $CONTAINER_ID ?>" class="field-bordered"
          method="post" action="<? echo $arResult["FORM_ACTION"] ?>" name="">
        <button type="submit" value="" class="header-search__submit">
            <svg width="20" height="18">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-magnifier"></use>
            </svg>
        </button>
        <input id="<? echo $INPUT_ID ?>" class="header-search__text" type="text" name="q"
               value="<?= htmlspecialcharsbx($_REQUEST["q"]) ?>" autocomplete="off" class="header-search__text"
               placeholder="Найти"/>
    </form>
</div>
<? endif ?>
<script>
    BX.ready(function () {
        new JCTitleSearch({
            'AJAX_PAGE': '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
            'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
            'INPUT_ID': '<?echo $INPUT_ID?>',
            'MIN_QUERY_LEN': 2
        });
    });

    BX.addCustomEvent('onAjaxSuccess', function(){

        if(~arguments[1].data.indexOf('<?=$INPUT_ID?>')){


            $('#mob-filter-category-1').jScrollPane();

            if($.fancybox.isOpen){
                let results = document.querySelectorAll('.title-search-result');
                
                if(results){
                    results.forEach(function (element) {
                        element.style.bottom = 0;
                    })
                }

                setCategory1Height();
            }
            else{
                let results = document.querySelectorAll('.title-search-result');

                if(results){
                    results.forEach(function (element) {
                        element.style.bottom = '';
                    })
                }
            }
        }
    });
    window.addEventListener("resize", function() {
        setCategory1Height();
        $('#mob-filter-category-1').jScrollPane();
    });

    function setCategory1Height(){

        let mobFilterCategory1 = document.getElementById('mob-filter-category-1');
        let mobFilterCategoryAll = document.getElementById('mob-filter-category-all');
        let sectionUl = document.querySelector('.bx_searche .search-section-list');

        if(mobFilterCategoryAll && sectionUl) {
            let mobFilterCategoryAllStyle = window.getComputedStyle(mobFilterCategoryAll, null);
            let sectionUlStyle = window.getComputedStyle(sectionUl, null);


            let blockHeight = document.querySelector('.bx_searche').offsetHeight;

            let topBlockHeight = sectionUl.offsetHeight
                + parseFloat(sectionUlStyle.marginTop) + parseFloat(sectionUlStyle.marginBottom);

            let bottomBlockHeight = mobFilterCategoryAll.offsetHeight
                + parseFloat(mobFilterCategoryAllStyle.marginTop) + parseFloat(mobFilterCategoryAllStyle.marginBottom);

            mobFilterCategory1.style.height = (blockHeight - topBlockHeight - bottomBlockHeight) + 'px';

            console.log('mobFilterCategory1', mobFilterCategory1);
            console.log('blockHeight', blockHeight);
            console.log('topBlockHeight', topBlockHeight);
            console.log('topBlockHeight', bottomBlockHeight);
        }
    }
</script>

