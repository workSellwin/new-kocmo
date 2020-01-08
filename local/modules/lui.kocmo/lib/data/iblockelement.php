<?php

namespace Lui\Kocmo\Data;

class IblockElement extends Base implements IblockInterface
{
    use MinData;

    /**
     * @param array $arFilter
     * @return array
     */
    public function GetData(array $arFilter): array
    {
        $arData = [];
        $arSelect = Array("ID", "IBLOCK_ID", "*", "PROPERTY_*");
        $res = \CIBlockElement::GetList(
            ['ID' => 'ASC'],
            $arFilter,
            false,
            false,
            $arSelect
        );
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $key = $arFields[$this->key];
            $arData[$key] = $this->RemoveKeyT($arFields);
            $prop = array_column($ob->GetProperties(), 'VALUE', 'CODE');
            $prop = array_diff($prop, ['']);
            $arData[$key]['PROPERTY'] = $prop;
        }
        return $arData;
    }

    public function GetDataNew(array $arFilter): array
    {
        $arData = [];
        $arSelect = Array("ID", "IBLOCK_ID", "DETAIL_PICTURE","PROPERTY_ARTIKUL");
        $res = \CIBlockElement::GetList(
            ['ID' => 'ASC'],
            $arFilter,
            false,
            false,
            $arSelect
        );
        while ($arFields = $res->GetNext()) {
            $arData[] = $arFields;
        }
        return $arData;
    }

}
