<?php

//require $_SERVER['DOCUMENT_ROOT'] . '/google_shopping/arrayToXML.php';

class MerchantsYandex
{

    private $site = 'https://kocmo.by';
    private $name = "Beauty House";
    private $company = "Сэльвин-Логистик";
    private $arSection = [];
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
        $this->feedPath = $_SERVER['DOCUMENT_ROOT'] . '/yandex_shopping/yandex.xml';
    }

    public function checkParam(array $fields)
    {

        if (empty($fields['DETAIL_TEXT']) && empty($fields['PREVIEW_TEXT'])) {
            return false;
        }

        if (empty($fields['DETAIL_PAGE_URL'])) {
            return false;
        }

        if (empty($fields['PREVIEW_PICTURE']) && empty($fields['DETAIL_PICTURE'])) {
            return false;
        }

        if (empty($fields['CATALOG_PRICE_' . $this->priceId])) {
            return false;
        }

        return true;
    }

    public function create()
    {

        $res = \CIBlockElement::GetList(
            [],
            ["IBLOCK_ID" => $this->catalogIBlockId, "ACTIVE" => "Y", "CATALOG_AVAILABLE" => "Y"],
            false,
            false,
            [
                'ID', 'DETAIL_PAGE_URL', 'NAME', 'PREVIEW_TEXT', 'IBLOCK_SECTION_ID', 'DETAIL_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE',
                'CATALOG_GROUP_' . $this->priceId, 'PROPERTY_MARKA'
            ]
        );

        while ($fields = $res->GetNext()) {
            if (!$this->checkParam($fields)) {
                continue;
            }
            $this->itemPush($fields);
            continue;
        }
        $this->arSection = array_values($this->arSection);

        $docArr = $this->getDocBase();
        $docArr['child'][0]['child'] = array_merge($docArr['child'][0]['child'], $this->items);

        try {
            $toXml = new ArrayToXML($docArr, $this->feedPath);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    private function getPrice($id)
    {
        //$arPrice = \CCatalogProduct::GetOptimalPrice($id);
        $arPrice = ElementPrice::getElementPrice($id);
        $arPrice['DISCOUNT_PRICE'] = number_format($arPrice['PRICE_OLD'], 2);
        return $arPrice;
    }

    private function getDescription(array $fields)
    {

        $description = empty($fields['DETAIL_TEXT']) ? $fields['PREVIEW_TEXT'] : $fields['DETAIL_TEXT'];
        $description = "<![CDATA[\n" . $description . "\n]]>";

        return $description;
    }

    private function getFilePath(array $fields)
    {

        $basePic = $fields['PREVIEW_PICTURE'];

        if (empty($basePic)) {
            $basePic = $fields['DETAIL_PICTURE'];
        }

        if (intval($basePic) > 0) {
            $basePic = \CFile::GetPath($basePic);
        } else {
            $basePic = "";
        }

        return $basePic;
    }

    private function itemPush(array $fields)
    {
        $arPrice = $this->getPrice($fields['ID']);
        $description = $this->getDescription($fields);
        $basePic = $this->getFilePath($fields);
        $fields['CATALOG_PRICE_' . $this->priceId] = number_format($fields['CATALOG_PRICE_' . $this->priceId], 2);
        $this->items[] = [

            "name" => "offer",
            "attr" => ['id' => $fields['ID'], "available" => "true"],
            "child" => [
                [
                    "name" => "url",
                    "text" => $this->site . $fields['DETAIL_PAGE_URL'],
                ],
                [
                    "name" => "price",
                    "text" => $fields['CATALOG_PRICE_' . $this->priceId],
                ],
                [
                    "name" => "name",
                    "text" => $fields['NAME'],
                ],
                [
                    "name" => "currencyId",
                    "text" => "BYN",
                ],
                [
                    "name" => "categoryId",
                    "text" => $fields["IBLOCK_SECTION_ID"],
                ],
                [
                    "name" => "picture",
                    "text" => $this->site . $basePic,
                ],
                [

                    "name" => "oldprice",
                    "text" => $arPrice['PRICE_OLD'],
                ],
                [
                    "name" => "vendor",
                    "text" => $fields['PROPERTY_MARKA_VALUE'],
                ],
                /* [
                     "name" => "vendorCode",
                     "text" => $vendorCode,
                 ],*/
                /*[
                    "name" => "barcode",
                    "text" => $fields[''],
                ],*/
                [
                    "name" => "description",
                    "text" => $description,
                ],
            ]
        ];

        if(!$this->arSection[$fields['IBLOCK_SECTION_ID']]){
            $res = CIBlockSection::GetByID($fields['IBLOCK_SECTION_ID']);
            if($ar_res = $res->GetNext()) {
                $this->arSection[$fields['IBLOCK_SECTION_ID']] = [
                    "name" => "category",
                    "attr" => ['id' => $ar_res['ID'], "parentId" => $ar_res['IBLOCK_SECTION_ID']],
                    "text" => $ar_res['NAME'],
                ];
            }
        }
    }

    private function getDocBase()
    {

        return [
            "name" => "yml_catalog",
            "attr" => ["date" => date('Y-m-d H:i')],
            "text" => "",
            "child" => [
                [
                    "name" => "shop",
                    "attr" => [],
                    "text" => "",
                    "child" => [
                        [
                            "name" => "name",
                            "text" => $this->name,
                        ],
                        [
                            "name" => "company",
                            "text" => $this->company,
                        ],
                        [
                            "name" => "url",
                            "text" => $this->site,
                        ],
                        [
                            "name" => "currencies",
                            "child" => [
                                [
                                    "name" => "currency",
                                    "attr" => ["id" => "BYN", "rate" => "1"],
                                ]
                            ]
                        ],
                        [
                            "name" => "categories",
                            "child" =>  $this->arSection,
                        ],
                        /*[
                            "name" => "offers",
                            "child" => '$offers'//$offers
                        ]*/

                    ]
                ]
            ]

        ];
    }

    static function runAgent()
    {
        $merchants = new MerchantsYandex();
        $merchants->create();
        return "MerchantsYandex::runAgent();";
    }
}

//$merchants = new Merchants();
//$merchants->create();

