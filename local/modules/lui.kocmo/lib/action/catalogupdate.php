<?php


namespace Lui\Kocmo\Action;


use Bitrix\Main\LoaderException,
    Lui\Kocmo\Interfaces\ActionsInterfaces,
    \Bitrix\Main\Loader,
    Kocmo\Exchange;

class CatalogUpdate implements ActionsInterfaces
{

    public function updSections($param){

        $returnVal = ["SUCCESS" => 0, "VALUE" => 0, "ERRORS" => []];
        $param = json_decode($param, true);

        try {
            Loader::includeModule('kocmo.exchange');
        } catch( LoaderException $e){
            $returnVal["ERRORS"][] =  $e->getMessage();
        }

        $bx = \Kocmo\Exchange\StaticFactory::factory(0);
        $bx->update();

        if(!$bx->isSuccess()){
            array_merge($returnVal["ERRORS"], $bx->getErrors());
        }
        else{
            //$returnVal["VALUE"] = $o->getProductId();
            $returnVal["SUCCESS"] = 1;
        }

        return $returnVal;
    }

    public function Available(){
        return ['update'];
    }
}