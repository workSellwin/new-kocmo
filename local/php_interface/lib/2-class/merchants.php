<?php

//require $_SERVER['DOCUMENT_ROOT'] . '/google_shopping/arrayToXML.php';

class Merchants{

    private $site = 'https://kocmo.by';
    private $name = "kocmo.by";
    private $priceId = 2;
    private $discountPriceId = 3;
    private $catalogIBlockId = 2;
    private $freeDeliverySum = 40;
    private $deliveryCost = "5.00";
    private $items = [];
    private $feedPath = "";

    function __construct()
    {
        \CModule::IncludeModule('iblock');
        $this->feedPath = $_SERVER['DOCUMENT_ROOT'] . '/google_shopping/google.xml';
    }

    public function checkParam(array $fields){

        if( empty($fields['DETAIL_TEXT']) && empty($fields['PREVIEW_TEXT']) ){
            return false;
        }

        if( empty($fields['DETAIL_PAGE_URL']) ){
            return false;
        }

        if( empty($fields['PREVIEW_PICTURE']) && empty($fields['DETAIL_PICTURE']) ){
            return false;
        }

        if( empty($fields['CATALOG_PRICE_' . $this->priceId]) ){
            return false;
        }

        return true;
    }

    public function create(){

        $res = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => $this->catalogIBlockId, "ACTIVE" => "Y", "CATALOG_AVAILABLE" => "Y"],
            false,
            false,
            [
                'ID', 'DETAIL_PAGE_URL', 'NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE',
                'CATALOG_GROUP_' . $this->priceId, 'PROPERTY_MARKA'
            ]
        );

        while($fields = $res->GetNext()){
            if(!$this->checkParam($fields)){
                continue;
            }
            $this->itemPush($fields);
        }

        $docArr = $this->getDocBase();
        $docArr['child'][0]['child'] = array_merge($docArr['child'][0]['child'], $this->items);

        try {
            $toXml = new ArrayToXML($docArr, $this->feedPath);
        }
        catch( \Exception $e){
            echo $e->getMessage();
        }
    }

    private function getPrice($id){
        //$arPrice = \CCatalogProduct::GetOptimalPrice($id);
        $arPrice = ElementPrice::getElementPrice($id);
        $arPrice['DISCOUNT_PRICE'] = number_format($arPrice['PRICE_OLD'], 2);
        return $arPrice;
    }

    private function getDescription(array $fields){

        $description = empty($fields['DETAIL_TEXT']) ? $fields['PREVIEW_TEXT'] : $fields['DETAIL_TEXT'];
        $description = "<![CDATA[\n" . $description . "\n]]>";

        return $description;
    }

    private function getFilePath(array $fields){

        $basePic = $fields['PREVIEW_PICTURE'];

        if( empty($basePic) ){
            $basePic = $fields['DETAIL_PICTURE'];
        }

        if(intval($basePic) > 0){
            $basePic = \CFile::GetPath($basePic);
        }
        else{
            $basePic = "";
        }

        return $basePic;
    }

    private function itemPush(array $fields){
        $arPrice = $this->getPrice($fields['ID']);
        $description = $this->getDescription($fields);
        $basePic = $this->getFilePath($fields);
        $fields['CATALOG_PRICE_' . $this->priceId] = number_format($fields['CATALOG_PRICE_' . $this->priceId], 2);
        $this->items[] = [

            "name" => "item",
            "child" => [
                [
                    "name" => 'g:id',
                    "text" => $fields['ID'],
                ],
                [
                    "name" => 'g:title',
                    "text" => $fields['NAME'],
                ],
                [
                    "name" => 'g:description',
                    "text" => $description,
                ],
                [
                    "name" => 'g:link',
                    "text" => $this->site . $fields['DETAIL_PAGE_URL'],
                ],
                [
                    "name" => 'g:image_link',
                    "text" => $this->site.$basePic,
                ],
                [
                    "name" => 'g:condition',
                    "text" => 'new',
                ],
                [
                    "name" => 'g:availability',
                    "text" => 'in_stock',
                ],
                [
                    "name" => 'g:price',
                    "text" => $fields['CATALOG_PRICE_' . $this->priceId] . ' BYN',
                ],
                [
                    "name" => 'g:sale_price',
                    "text" => $arPrice['PRICE_OLD'] ?  $arPrice['PRICE_OLD'] . ' BYN' : '',
                ],
                [
                    "name" => 'g:brand',
                    "text" => $fields['PROPERTY_MARKA_VALUE'],
                ],
//            [
//                "name" => 'g:google_product_category',
//                "text" => '1',
//            ],
//            [
//                "name" => 'g:product_type',
//                "text" => $fields['NAME'],
//            ],
                [
                    "name" => 'g:shipping',
                    "child" => [
                        [
                            "name" => 'g:country',
                            "text" => "BY",
                        ],
//                    [
//                        "name" => 'g:service',
//                        "text" => "BY",
//                    ],
                        [
                            "name" => 'g:price',
                            "text" => $arPrice['PRICE_OLD'] > $this->freeDeliverySum ? "0 BYN" : $this->deliveryCost . " BYN",
                        ],
                    ]
                ],
            ]
        ];
    }

    private function getDocBase(){

        return [
            "name" => "rss",
            "attr" => [
                "version" => "2.0",
                "xmlns:g" => "http://base.google.com/ns/1.0",
            ],
            "text" => "",
            "child" => [
                [
                    "name" => "channel",
                    "attr" => [],
                    "text" => "",
                    "child" => [
                        [
                            "name" => "title",
                            "text" => $this->name,
                        ],
                        [
                            "name" => "link",
                            "text" => $this->site,
                        ],
                        [
                            "name" => "description",
                            "text" => "description",
                        ],
                    ]
                ]
            ]
        ];
    }

    static function runAgent(){
        $merchants = new Merchants();
        $merchants->create();
        return "Merchants::runAgent();";
    }
}

//$merchants = new Merchants();
//$merchants->create();

