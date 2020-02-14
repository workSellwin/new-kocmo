<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$PREVIEW_WIDTH = intval($arParams["PREVIEW_WIDTH"]);
if ($PREVIEW_WIDTH <= 0)
    $PREVIEW_WIDTH = 75;

$PREVIEW_HEIGHT = intval($arParams["PREVIEW_HEIGHT"]);
if ($PREVIEW_HEIGHT <= 0)
    $PREVIEW_HEIGHT = 75;

$arParams["PRICE_VAT_INCLUDE"] = $arParams["PRICE_VAT_INCLUDE"] !== "N";

$arCatalogs = false;

$arResult["ELEMENTS"] = array();
$arResult['SECTIONS'] = array();
$arResult["SEARCH"] = array();
foreach($arResult["CATEGORIES"] as $category_id => $arCategory)
{
    foreach($arCategory["ITEMS"] as $i => $arItem)
    {
        if(isset($arItem["ITEM_ID"]))
        {
            $arResult["SEARCH"][] = &$arResult["CATEGORIES"][$category_id]["ITEMS"][$i];
            if (
                $arItem["MODULE_ID"] == "iblock"
                && substr($arItem["ITEM_ID"], 0, 1) !== "S"
            )
            {
                if ($arCatalogs === false)
                {
                    $arCatalogs = array();
                    if (CModule::IncludeModule("catalog"))
                    {
                        $rsCatalog = CCatalog::GetList(array(
                            "sort" => "asc",
                        ));
                        while ($ar = $rsCatalog->Fetch())
                        {
                            if ($ar["PRODUCT_IBLOCK_ID"])
                                $arCatalogs[$ar["PRODUCT_IBLOCK_ID"]] = 1;
                            else
                                $arCatalogs[$ar["IBLOCK_ID"]] = 1;
                        }
                    }
                }

                if (array_key_exists($arItem["PARAM2"], $arCatalogs))
                {
                    $arResult["ELEMENTS"][$arItem["ITEM_ID"]] = $arItem["ITEM_ID"];
                }
            }
        }
    }
}

if (!empty($arResult["ELEMENTS"]) && CModule::IncludeModule("iblock"))
{
    $arConvertParams = array();
    if ('Y' == $arParams['CONVERT_CURRENCY'])
    {
        if (!CModule::IncludeModule('currency'))
        {
            $arParams['CONVERT_CURRENCY'] = 'N';
            $arParams['CURRENCY_ID'] = '';
        }
        else
        {
            $arCurrencyInfo = CCurrency::GetByID($arParams['CURRENCY_ID']);
            if (!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo)))
            {
                $arParams['CONVERT_CURRENCY'] = 'N';
                $arParams['CURRENCY_ID'] = '';
            }
            else
            {
                $arParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
                $arConvertParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
            }
        }
    }

    $obParser = new CTextParser;

    if (is_array($arParams["PRICE_CODE"]))
        $arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices(0, $arParams["PRICE_CODE"]);
    else
        $arResult["PRICES"] = array();

    $arSelect = array(
        "ID",
        "IBLOCK_ID",
        "PREVIEW_TEXT",
        "DETAIL_TEXT",
        "PREVIEW_PICTURE",
        "DETAIL_PICTURE",
        "IBLOCK_SECTION_ID",
        "PROPERTY_MARKA"
    );

    $arFilter = array(
        "IBLOCK_LID" => SITE_ID,
        "IBLOCK_ACTIVE" => "Y",
        "ACTIVE_DATE" => "Y",
        "ACTIVE" => "Y",
//		"CATALOG_AVAILABLE" => "Y",
        "CHECK_PERMISSIONS" => "Y",
        "MIN_PERMISSION" => "R",
    );

    foreach($arResult["PRICES"] as $value)
    {
        $arSelect[] = $value["SELECT"];
        $arFilter["CATALOG_SHOP_QUANTITY_".$value["ID"]] = 1;
    }

    $arFilter["=ID"] = $arResult["ELEMENTS"];
    $arResult["ELEMENTS"] = array();
    $rsElements = CIBlockElement::GetList(["CATALOG_AVAILABLE" => "asc"], $arFilter, false, false, $arSelect);

    while($arElement = $rsElements->Fetch())
    {
        $arElement["PRICES"] = CIBlockPriceTools::GetItemPrices($arElement["IBLOCK_ID"], $arResult["PRICES"], $arElement, $arParams['PRICE_VAT_INCLUDE'], $arConvertParams);
        if($arParams["PREVIEW_TRUNCATE_LEN"] > 0)
            $arElement["PREVIEW_TEXT"] = $obParser->html_cut($arElement["PREVIEW_TEXT"], $arParams["PREVIEW_TRUNCATE_LEN"]);

        $arResult["ELEMENTS"][$arElement["ID"]] = $arElement;
    }
}

foreach($arResult["SEARCH"] as $i=>$arItem)
{
    switch($arItem["MODULE_ID"])
    {
        case "iblock":
            if(array_key_exists($arItem["ITEM_ID"], $arResult["ELEMENTS"]))
            {
                $arElement = &$arResult["ELEMENTS"][$arItem["ITEM_ID"]];

                if ($arParams["SHOW_PREVIEW"] == "Y")
                {
                    if ($arElement["PREVIEW_PICTURE"] > 0)
                        $arElement["PICTURE"] = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], array("width"=>$PREVIEW_WIDTH, "height"=>$PREVIEW_HEIGHT), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                    elseif ($arElement["DETAIL_PICTURE"] > 0)
                        $arElement["PICTURE"] = CFile::ResizeImageGet($arElement["DETAIL_PICTURE"], array("width"=>$PREVIEW_WIDTH, "height"=>$PREVIEW_HEIGHT), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                }
                if( intval($arElement["IBLOCK_SECTION_ID"]) > 0 ) {
                    $arResult['SECTIONS'][$arElement["IBLOCK_SECTION_ID"]] = false;
                }
            }
            break;
    }

    $arResult["SEARCH"][$i]["ICON"] = true;
}

if( count($arResult["CATEGORIES"][1]['ITEMS']) ){

    foreach($arResult["CATEGORIES"][1]['ITEMS'] as $key => $item){

        if( strpos($item['ITEM_ID'], 'S') === 0){
            $sId = (int) preg_replace('/[^0-9]/', '', $item['ITEM_ID']);
            $arResult["SECTIONS"][$sId] = false;
            unset($arResult["CATEGORIES"][1]['ITEMS'][$key]);
        }
    }
}

if(count($arResult["SECTIONS"])){

    $res = CIBlockSection::GetList(
        [],
        ["ID" => array_keys($arResult["SECTIONS"]), "ACTIVE" => 'Y'],
        false,
        ["ID", "NAME", "SECTION_PAGE_URL"],
        ['nTopCount' => 6]
    );

    while($sectionAr = $res->getNext() ){
        $arResult["SECTIONS"][$sectionAr['ID']] = $sectionAr;
    }
}

//pr($arResult["SECTIONS"], 14);
?>