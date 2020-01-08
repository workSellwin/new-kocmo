<?php


namespace Lui\Kocmo\Action;


use Lui\Kocmo\Interfaces\ActionsInterfaces;

class ajaxupdatesmartfilter implements ActionsInterfaces
{

    public function UpdateHtml($arParams){

        if(!empty($arParams['FILTER'])){
            global $APPLICATION;
            $APPLICATION->IncludeComponent(
                "bh:smart_filter_sale",
                ".default",
                $arParams['FILTER']['PARAMS'],
                false,
                array(
                    "HIDE_ICONS" => "N"
                )
            );
        }
    }


    public function Available()
    {
        return ['UpdateHtml'];
    }
}