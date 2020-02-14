<? use Lui\Kocmo\IncludeComponent;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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

?>

<? IncludeComponent::Menu(["NAME" => 'Акции', 'template' => 'sales', 'ROOT_MENU_TYPE' => 'aside-left-1', 'USE_EXT' => 'Y']); ?>


<div class="main-content">

    <? $ElementID = $APPLICATION->IncludeComponent(
        "bitrix:news.detail",
        "",
        Array(
            "DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
            "DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
            "DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
            "DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
            "PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
            "DETAIL_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["detail"],
            "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
            "META_KEYWORDS" => $arParams["META_KEYWORDS"],
            "META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
            "BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
            "SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
            "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
            "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
            "SET_TITLE" => $arParams["SET_TITLE"],
            "MESSAGE_404" => $arParams["MESSAGE_404"],
            "SET_STATUS_404" => $arParams["SET_STATUS_404"],
            "SHOW_404" => $arParams["SHOW_404"],
            "FILE_404" => $arParams["FILE_404"],
            "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
            "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
            "ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
            "GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
            "DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
            "DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
            "PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
            "PAGER_SHOW_ALWAYS" => "N",
            "PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
            "PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
            "CHECK_DATES" => $arParams["CHECK_DATES"],
            "ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
            "ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
            "SECTION_ID" => $arResult["VARIABLES"]["SECTION_ID"],
            "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
            "IBLOCK_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["news"],
            "USE_SHARE" => $arParams["USE_SHARE"],
            "SHARE_HIDE" => $arParams["SHARE_HIDE"],
            "SHARE_TEMPLATE" => $arParams["SHARE_TEMPLATE"],
            "SHARE_HANDLERS" => $arParams["SHARE_HANDLERS"],
            "SHARE_SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
            "SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
            "ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),
            'STRICT_SECTION_CHECK' => (isset($arParams['STRICT_SECTION_CHECK']) ? $arParams['STRICT_SECTION_CHECK'] : ''),
        ),
        $component
    ); ?>

    <?
    $filter_sales_prod2 = [];
    $flagN = true;
    $ob = new \Lui\Kocmo\Data\IblockElement();
    $el = $ob->GetData(['ID' => $ElementID]);
    $prod = false;
    if ($el) {
        $prod = reset($el);
        $prod = $prod['PROPERTY']['PROD'];
    }
    $arId = [];
    if ($prod) {
        $ob = new \Lui\Kocmo\BD\HB\ActionItems();
        $ob2 = new \Lui\Kocmo\BD\HB\ActionsList();
        $arId = $ob2->GetXml($prod);
        if (!$arId) {
            $flagN = false;
        } else {
            foreach ($arId as $link) {
                $link = $link['ID'];
                if ($link) {
                    $arData = $ob->GetLink($link);
                    $arIdEl = array_column($arData, 'UF_LINK');
                    if ($arIdEl) {
                        $filter_sales_prod2 = array_merge($filter_sales_prod2, array('ID' => $arIdEl));
                    }
                }
            }
        }
    } else {
        $flagN = false;
    }

    ?>




    <? if ($flagN) { ?>
        <h2 class="sale__title">товары на акции</h2>
        <?
        global $filter_sales_prod;
        $arParamsFilter = [
            "IBLOCK_ID" => 2,
            "SUB_FILTER" => $filter_sales_prod2,
            "FILTER_NAME" => "filter_sales_prod",
            "PRICE_CODE" => array(
                0 => "ROZNICHNAYA",
            ),
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_GROUPS" => "N",
            "SAVE_IN_SESSION" => "N",
            "XML_EXPORT" => "N",
            "SECTION_TITLE" => "NAME",
            "SECTION_DESCRIPTION" => "DESCRIPTION",
            "HIDE_NOT_AVAILABLE" => "N",
            "CONVERT_CURRENCY" => "N",
            "CURRENCY_ID" => $arParams["CURRENCY_ID"],
            "SEF_MODE" => "N",
            "SEF_RULE" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["smart_filter"],
            "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
            "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
            "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
            "PROD_SALE_ID" => $PROP["PROD"],
            "COMPONENT_TEMPLATE" => ".default",
            "SECTION_CODE" => "",
            "DISPLAY_ELEMENT_COUNT" => "Y",
            "SHOW_ALL_WO_SECTION" => "Y",
        ];

        $APPLICATION->IncludeComponent(
            "bh:smart_filter_sale",
            ".default",
            $arParamsFilter,
            false,
            array(
                "HIDE_ICONS" => "N"
            )
        );

        ?>
    <? } ?>

    <div id="AJAX_FILTER_CONTAINER">
        <? if ($flagN) { ?>
            <?
            if (isset($_REQUEST['ajax_filter']) && $_REQUEST['ajax_filter'] == 'Y') {
                $GLOBALS['APPLICATION']->RestartBuffer();
            }

            foreach ($arId as $link) {
                $link = $link['ID'];
                if ($link) {
                    $arData = $ob->GetLink($link);
                    $arIdEl = array_column($arData, 'UF_LINK');
                    if ($arIdEl) {
                        $filter_sales_prod = array_merge($filter_sales_prod, array('ID' => $arIdEl));
                    }
                }
            }


            if (isset($_REQUEST['available_not']) && $_REQUEST['available_not'] == 'y') {
                $filter_sales_prod['CATALOG_AVAILABLE'] = 'N';
            }

            if (isset($_REQUEST['available_yes']) && $_REQUEST['available_yes'] == 'y') {
                $filter_sales_prod['CATALOG_AVAILABLE'] = 'Y';
            }

            if ((isset($_REQUEST['available_not']) && $_REQUEST['available_not'] == 'y') && (isset($_REQUEST['available_yes']) && $_REQUEST['available_yes'] == 'y')) {
                unset($filter_sales_prod['CATALOG_AVAILABLE']);
            }


            //сортировка
            if (isset($_REQUEST['filter_sort']) && !empty($_REQUEST['filter_sort'])) {
                if ($_REQUEST['filter_sort'] == 'price_asc') {
                    $ELEMENT_SORT_FIELD = 'catalog_PRICE_2';
                    $ELEMENT_SORT_ORDER = 'asc';
                    $_SESSION['FILTER_SORT']['ELEMENT_SORT_FIELD'] = $ELEMENT_SORT_FIELD;
                    $_SESSION['FILTER_SORT']['ELEMENT_SORT_ORDER'] = $ELEMENT_SORT_ORDER;
                    $_SESSION['FILTER_SORT']['SELECT_FILTER_SORT'] = 'price_asc';
                }
                if ($_REQUEST['filter_sort'] == 'price_desc') {
                    $ELEMENT_SORT_FIELD = 'catalog_PRICE_2';
                    $ELEMENT_SORT_ORDER = 'desc';
                    $_SESSION['FILTER_SORT']['ELEMENT_SORT_FIELD'] = $ELEMENT_SORT_FIELD;
                    $_SESSION['FILTER_SORT']['ELEMENT_SORT_ORDER'] = $ELEMENT_SORT_ORDER;
                    $_SESSION['FILTER_SORT']['SELECT_FILTER_SORT'] = 'price_desc';
                }
                if ($_REQUEST['filter_sort'] == 'az_asc') {
                    $ELEMENT_SORT_FIELD = 'name';
                    $ELEMENT_SORT_ORDER = 'asc';
                    $_SESSION['FILTER_SORT']['ELEMENT_SORT_FIELD'] = $ELEMENT_SORT_FIELD;
                    $_SESSION['FILTER_SORT']['ELEMENT_SORT_ORDER'] = $ELEMENT_SORT_ORDER;
                    $_SESSION['FILTER_SORT']['SELECT_FILTER_SORT'] = 'az_asc';
                }
                if ($_REQUEST['filter_sort'] == 'az_desc') {
                    $ELEMENT_SORT_FIELD = 'name';
                    $ELEMENT_SORT_ORDER = 'desc';
                    $_SESSION['FILTER_SORT']['ELEMENT_SORT_FIELD'] = $ELEMENT_SORT_FIELD;
                    $_SESSION['FILTER_SORT']['ELEMENT_SORT_ORDER'] = $ELEMENT_SORT_ORDER;
                    $_SESSION['FILTER_SORT']['SELECT_FILTER_SORT'] = 'az_desc';
                }

            } else {
                $ELEMENT_SORT_FIELD = isset($_SESSION['FILTER_SORT']['ELEMENT_SORT_FIELD']) && !empty($_SESSION['FILTER_SORT']['ELEMENT_SORT_FIELD']) ? $_SESSION['FILTER_SORT']['ELEMENT_SORT_FIELD'] : 'name';
                $ELEMENT_SORT_ORDER = isset($_SESSION['FILTER_SORT']['ELEMENT_SORT_ORDER']) && !empty($_SESSION['FILTER_SORT']['ELEMENT_SORT_ORDER']) ? $_SESSION['FILTER_SORT']['ELEMENT_SORT_ORDER'] : 'asc';
                $_SESSION['FILTER_SORT']['SELECT_FILTER_SORT'] = isset($_SESSION['FILTER_SORT']['SELECT_FILTER_SORT']) && !empty($_SESSION['FILTER_SORT']['SELECT_FILTER_SORT']) ? $_SESSION['FILTER_SORT']['SELECT_FILTER_SORT'] : 'az_asc';
            }

            $FILTER['PARAMS'] = $arParamsFilter;
            $FILTER['PARAMS']['FILTER_PROPERTY_AJAX'] = !empty($filter_sales_prod) && isset($filter_sales_prod)? $filter_sales_prod : false;
            ?>
            <script type="text/javascript">
                var FILTER = <?echo CUtil::PhpToJSObject($FILTER)?>;
                updateSmartFilter();
            </script>

        <?

            $APPLICATION->IncludeComponent(
                "bitrix:catalog.section",
                "section",
                array(
                    "ACTION_VARIABLE" => "action",
                    "ADD_PICT_PROP" => "MORE_PHOTO",
                    "ADD_PROPERTIES_TO_BASKET" => "Y",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "ADD_TO_BASKET_ACTION" => "ADD",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "N",
                    "BACKGROUND_IMAGE" => "-",
                    "BASKET_URL" => "/personal/basket.php",
                    "BROWSER_TITLE" => "-",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "N",
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "N",
                    "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
                    "COMPARE_PATH" => "",
                    "COMPATIBLE_MODE" => "N",
                    "CONVERT_CURRENCY" => "Y",
                    "CURRENCY_ID" => "BYN",
                    "CUSTOM_FILTER" => "",
                    "DETAIL_URL" => "",
                    "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                    "DISCOUNT_PERCENT_POSITION" => "bottom-right",
                    "DISPLAY_BOTTOM_PAGER" => "Y",
                    "DISPLAY_COMPARE" => "Y",
                    "DISPLAY_TOP_PAGER" => "N",
                    "ELEMENT_SORT_FIELD" => $ELEMENT_SORT_FIELD,
                    "ELEMENT_SORT_ORDER" => $ELEMENT_SORT_ORDER,
                    "ENLARGE_PRODUCT" => "STRICT",
                    "FILTER_NAME" => "filter_sales_prod",
                    "HIDE_NOT_AVAILABLE" => "L",
                    "HIDE_NOT_AVAILABLE_OFFERS" => "Y",
                    "IBLOCK_ID" => "2",
                    "IBLOCK_TYPE" => "catalog",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "LABEL_PROP" => array(),
                    "LAZY_LOAD" => "N",
                    "LINE_ELEMENT_COUNT" => "3",
                    "LOAD_ON_SCROLL" => "N",
                    "MESSAGE_404" => "",
                    "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                    "MESS_BTN_BUY" => "Купить",
                    "MESS_BTN_COMPARE" => "Сравнить",
                    "MESS_BTN_DETAIL" => "Подробнее",
                    "MESS_BTN_SUBSCRIBE" => "Подписаться",
                    "MESS_NOT_AVAILABLE" => "Нет в наличии",
                    "META_DESCRIPTION" => "-",
                    "META_KEYWORDS" => "-",
                    "OFFERS_FIELD_CODE" => array(
                        0 => "NAME",
                        1 => "",
                    ),
                    "OFFERS_LIMIT" => "5",
                    "OFFERS_SORT_FIELD" => "sort",
                    "OFFERS_SORT_FIELD2" => "id",
                    "OFFERS_SORT_ORDER" => "asc",
                    "OFFERS_SORT_ORDER2" => "desc",
                    "OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => "main_btn",
                    "PAGER_TITLE" => "Товары",
                    "PAGE_ELEMENT_COUNT" => "9",
                    "PARTIAL_PRODUCT_PROPERTIES" => "Y",
                    "PRICE_CODE" => array(),
                    "PRICE_VAT_INCLUDE" => "Y",
                    "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
                    "PRODUCT_DISPLAY_MODE" => "Y",
                    "PRODUCT_ID_VARIABLE" => "id",
                    "PRODUCT_PROPS_VARIABLE" => "prop",
                    "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                    "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
                    "PRODUCT_SUBSCRIPTION" => "Y",
                    "PROPERTY_CODE_MOBILE" => array(),
                    "RCM_PROD_ID" => "",
                    "RCM_TYPE" => "personal",
                    "SECTION_CODE" => "",
                    "SECTION_ID" => "",
                    "SECTION_ID_VARIABLE" => "SECTION_ID",
                    "SECTION_URL" => "",
                    "SECTION_USER_FIELDS" => array(
                        0 => "",
                        1 => "",
                    ),
                    "SEF_MODE" => "N",
                    "SET_BROWSER_TITLE" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_STATUS_404" => "N",
                    "SET_TITLE" => "N",
                    "SHOW_404" => "N",
                    "SHOW_ALL_WO_SECTION" => "Y",
                    "SHOW_CLOSE_POPUP" => "N",
                    "SHOW_DISCOUNT_PERCENT" => "Y",
                    "SHOW_FROM_SECTION" => "Y",
                    "SHOW_MAX_QUANTITY" => "N",
                    "SHOW_OLD_PRICE" => "Y",
                    "SHOW_PRICE_COUNT" => "1",
                    "SHOW_SLIDER" => "N",
                    "SLIDER_INTERVAL" => "3000",
                    "SLIDER_PROGRESS" => "N",
                    "TEMPLATE_THEME" => "blue",
                    "USE_ENHANCED_ECOMMERCE" => "N",
                    "USE_MAIN_ELEMENT_SECTION" => "N",
                    "USE_PRICE_COUNT" => "N",
                    "USE_PRODUCT_QUANTITY" => "N",
                    "COMPONENT_TEMPLATE" => "section",
                    "ELEMENT_SORT_FIELD2" => "id",
                    "ELEMENT_SORT_ORDER2" => "desc"
                ),
                false
            ); ?>

            <? if (isset($_REQUEST['ajax_filter']) && $_REQUEST['ajax_filter'] == 'Y') {
                ?>
                <script>
                    $(document).ready(function () {
                        $('.fancybox').fancybox();
                    });
                </script>
                <?
                die();
            } ?>
        <? } ?>
    </div>

</div>


<? $this->SetViewTarget('DOP_CLASS_CONTAINER'); ?>
inner-with-aside
<? $this->EndViewTarget(); ?>
