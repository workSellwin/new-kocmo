<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (empty($arResult))
    return;

$arSectionsInfo = [];
if (IsModuleInstalled("iblock")) {
    $arFilter = [
        "TYPE" => "catalog",
        "SITE_ID" => SITE_ID,
        "ACTIVE" => "Y"
    ];

    $obCache = new CPHPCache();
    if ($obCache->InitCache(36000, serialize($arFilter), "/iblock/menu")) {
        $arSectionsInfo = $obCache->GetVars();
    } elseif ($obCache->StartDataCache()) {
        if (CModule::IncludeModule("iblock")) {
            $dbIBlock = CIBlock::GetList(array('SORT' => 'ASC', 'ID' => 'ASC'), $arFilter);
            $dbIBlock = new CIBlockResult($dbIBlock);
            $curIblockID = 0;
            if ($arIBlock = $dbIBlock->GetNext()) {
                $dbSections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arIBlock["ID"]), false, array("ID", "SECTION_PAGE_URL", "PICTURE", "DESCRIPTION", "DEPTH_LEVEL"));
                while ($arSections = $dbSections->GetNext()) {
                    $pictureSrc = CFile::GetFileArray($arSections["PICTURE"]);

                    if ($pictureSrc)
                        $arResizePicture = CFile::ResizeImageGet(
                            $arSections["PICTURE"],
                            array("width" => 1000, 'height' => 1000),
                            BX_RESIZE_IMAGE_PROPORTIONAL,
                            true
                        );

                    $arSectionsInfo[crc32($arSections["SECTION_PAGE_URL"])]["PICTURE"] = $pictureSrc ? $arResizePicture["src"] : false;
                    $arSectionsInfo[crc32($arSections["SECTION_PAGE_URL"])]["DESCRIPTION"] = $arSections["DESCRIPTION"];
                    $arBrandFinish = [];
                    if ($arSections['DEPTH_LEVEL'] == 1) {
                        $arBrand = [];
                        $propCode = 'MARKA';// свойства
                        $arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM",'IBLOCK_ID', 'PROPERTY_*');
                        $arFilter = Array("IBLOCK_ID" => $arIBlock["ID"], "SECTION_ID" => $arSections['ID'], "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y","CATALOG_AVAILABLE"=>'Y');
                        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                        $arXmlID=[];
                        while ($ob = $res->GetNextElement()) {
                            $arProps = $ob->GetProperties();
                            if ($brand = $arProps[$propCode]) {
                                $name = trim($brand['VALUE']);
                                $arXmlID[$name] = $brand['VALUE_XML_ID'];
                                $arBrand[$name] = [
                                    'NAME'=>$brand['VALUE'],
                                    'XML_ID'=>$brand['VALUE_XML_ID'],
                                ];
                            }
                        }

                        $arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM", 'DETAIL_PAGE_URL', 'IBLOCK_ID', 'PROPERTY_BRAND_BIND', "XML_ID");
                        $arFilter = Array("IBLOCK_ID" => 7, "ACTIVE" => "Y", "PROPERTY_BRAND_BIND" => array_values($arXmlID));
                        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                        $arURL=[];
                        while ($arFields = $res->GetNext()) {
                            $arURL[$arFields['PROPERTY_BRAND_BIND_VALUE']]=$arFields['DETAIL_PAGE_URL'];
                        }

                        ksort($arBrand);
                        $n = 0;

                        foreach ($arBrand as $item) {
                            $k = strtoupper(substr($item['NAME'], 0, 1));
                            $arBrandFinish[$k][$n] = [
                                'NAME'=>$item['NAME'],
                                'URL'=>$arURL[$item['XML_ID']],
                            ];
                            $n++;
                        }
                    }

                    $arSectionsInfo[crc32($arSections["SECTION_PAGE_URL"])]['BRAND'] = $arBrandFinish;

                }
                if (defined("BX_COMP_MANAGED_CACHE")) {
                    global $CACHE_MANAGER;
                    $CACHE_MANAGER->StartTagCache("/iblock/menu");
                    $CACHE_MANAGER->RegisterTag("iblock_id_" . $arIBlock["ID"]);
                    $CACHE_MANAGER->EndTagCache();
                }
            }
        }
        $obCache->EndDataCache($arSectionsInfo);
    }
}


foreach ($arResult as &$item) {
    if ($data = $arSectionsInfo[crc32($item['LINK'])]) {
        $item['INFO'] = $data;
    }
}

$arResult = getChilds($arResult);


$name = '';

$arParams['SELECTED_INDEX'] = getSelectedIndex($arResult);

function getSelectedIndex($arResult)
{
    $selectedIndex = false;

    foreach ($arResult as $item) {//найти выбранный пункт

        if (!empty($item['SELECTED'])) {

            if ($selectedIndex === false || $selectedIndex < $item['ITEM_INDEX']) {
                $selectedIndex = $item['ITEM_INDEX'];
            }
            if( count($item['CHILD']) ){
                $index = getSelectedIndex($item['CHILD']);

                if( intval($index) > $selectedIndex ){
                    $selectedIndex = $index;
                }
            }
        }
    }
    return $selectedIndex;
}
