<?php


namespace Lui\Kocmo\Action;


use Lui\Kocmo\Interfaces\ActionsInterfaces;

class ajaxupdatesmartfilter implements ActionsInterfaces
{

    public function UpdateHtml($arParams){
        if(!empty($arParams['PARAMS'])){

            global $APPLICATION;
            $APPLICATION->IncludeComponent(
                "bh:smart_filter_sale",
                ".default",
                $arParams['PARAMS'],
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