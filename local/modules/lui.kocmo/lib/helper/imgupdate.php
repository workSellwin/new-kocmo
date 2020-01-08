<?php


namespace Lui\Kocmo\Helper;


class ImgUpdate
{
    protected $sPath = '';

    public function Run($path = '')
    {
        $this->sPath = $path;
        $getImg = $this->GetBarCode();
        PR(count($getImg), true);
        return $this->Update($getImg);
    }

    protected function Update($getImg)
    {
        $arUpd = [];
        $ob = new  \Lui\Kocmo\Data\IblockElement();
        $arCatalog = $ob->GetData(['IBLOCK_ID' => '2', 'PROPERTY_ARTIKUL' => array_keys($getImg)]);
        $arCode = array_keys($getImg);
        $arUpd['ALL_COUNT'] = count($arCode);
        $arUpd['ALL_CODE'] = $arCode;
        $arUpd['PRODUCTS']['COUNT'] = count($arCatalog);
        foreach ($arCatalog as $item) {
            $arUpd['PRODUCTS']['ITEM'] += $this->UpdR($item, $getImg);
        }

        $arCatalog = $ob->GetData(['IBLOCK_ID' => '3', 'PROPERTY_ARTNUMBER' => array_keys($getImg)]);
        $arUpd['OFFERS']['COUNT'] = count($arCatalog);
        foreach ($arCatalog as $item) {
            $arUpd['OFFERS']['ITEM'] += $this->UpdR($item, $getImg);
        }

        return $arUpd;
    }

    protected function UpdR($item, $getImg)
    {
        $code = $item['PROPERTY']['ARTIKUL'];
        if (!$code) {
            $code = $item['PROPERTY']['ARTNUMBER'];
        }
        $res = $this->UpdImg($item['ID'], $getImg[$code]);
        if ($res === true) {
            unlink($getImg[$code]);
        }
        return $res;
    }

    protected function GetBarCode()
    {
        $arFile = [];
        $dir = $this->sPath;
        $catalogD = opendir($dir);
        while ($filename = readdir($catalogD)) {
            $file = $dir . $filename;
            if (is_file($file)) {
                $stat = pathinfo($file);
                $arFile[$stat['filename']] = $file;
            }
        }
        return $arFile;
    }

    protected function UpdImg($id, $img)
    {
        $result = true;
        $el2 = new \CIBlockElement;
        $arLoadProductArray = Array(
            "DETAIL_PICTURE" => \CFile::MakeFileArray($img)
        );
        if (!$res = $el2->Update($id, $arLoadProductArray)) {
            $result = $el2->LAST_ERROR;
        }
        return $result;
    }

}
