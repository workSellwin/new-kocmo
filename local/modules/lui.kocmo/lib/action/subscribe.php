<?php


namespace Lui\Kocmo\Action;

use Lui\Kocmo\Interfaces\ActionsInterfaces;
use Bitrix\Catalog\Product\SubscribeManager;
use Lui\Kocmo\Data\IblockElement;

class Subscribe implements ActionsInterfaces
{

    /**
     * Добовляет подписку на товар
     * @param $arParams
     * @return array arParams
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     */
    public function Add($arParams){

        if(!$arParams['ITEM_ID'])$arResult['ERROR'][] = 'No params ITEM_ID';
        if(!$arParams['EMAIL'])$arResult['ERROR'][] = 'No params EMAIL';

        if(!empty($arResult['ERROR']))return $arResult;

        $subscribeManager = new SubscribeManager;

        $subscribeData = array(
            'USER_CONTACT' => $arParams['EMAIL'],
            'ITEM_ID' => $arParams['ITEM_ID'],
            'SITE_ID' => $arParams['SITE_ID'] ? $arParams['SITE_ID'] : 's1' ,
            'CONTACT_TYPE' => $arParams['CONTACT_TYPE'] ? $arParams['CONTACT_TYPE'] : 1 ,
            'LANDING_SITE_ID' => $arParams['LANDING_SITE_ID'] ? $arParams['CONTACT_TYPE'] : '' ,
            'USER_ID' => $arParams['USER_ID'] ? $arParams['USER_ID'] : '' ,
        );

        $subscribeId = $subscribeManager->addSubscribe($subscribeData);

        if($arError=$subscribeManager->getErrors()){
            foreach ($arError as $obError){
                $arResult['ERROR']=$obError->GetMessage();
            }
        }

        if(!empty($arResult['ERROR']))return $arResult;



        $data = new IblockElement();
        $arFilter = ['IBLOCK_ID'=>2, 'ID'=>$arParams['ITEM_ID']];
        $data = $data->GetData($arFilter);
        $arEventFields = array(
            "MESSAGE" => 'Подписались на товар: <br>ID - '.$arParams['ITEM_ID'].'<br>Название - '.$data[$arParams['ITEM_ID']]['NAME'].'<br>Артикул - '.$data[$arParams['ITEM_ID']]['PROPERTY']['ARTIKUL'],
        );
        \CEvent::Send("SUBSCRIPTION_PRODUCT_USER", 's1', $arEventFields);
        return [
            'ADD_SUBSCRIBE' => 'Y',
            'MESSAGE' => 'Вы успешно подписались',
        ];
    }

    /**
     * Возвращает доступные методы запроса!
     *
     * @return array
     */
    public function Available()
    {
        return ['Add'];
    }
}