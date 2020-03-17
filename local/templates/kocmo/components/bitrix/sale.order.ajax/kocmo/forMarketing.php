<?php
use Bitrix\Sale;
\Bitrix\Main\Loader::includeModule('sale');
\Bitrix\Main\Loader::includeModule('iblock');

$basket = $orderB->getBasket();
$basketItems = $basket->getBasketItems();
$products = [];

foreach($basketItems as $item){

    $products[] = [
        "id" =>  $item->getProductId(),
        "name" =>  $item->getField('NAME'),
        "price" =>  $item->getPrice(),
        "quantity" =>  $item->getQuantity(),
    ];
}

$res = CIBlockElement::GetList([], ['ID' => array_keys($products), "IBLOCK_ID" => 2], false, false, ['PROPERTY_MARKA', 'ID', 'NAME', 'SECTION_IBLOCK_ID']);

while( $fields = $res->fetch() ){
    $products[$fields['ID']]['brand'] = $fields['PROPERTY_MARKA_VALUE'];
}
?>
<script>
    dataLayer.push({
        "ecommerce": {
            "purchase": {
                "actionField": {
                    "id": "<?=$orderB->getId();?>"
                },
                "products": [
                    <?foreach($products as $product):?>
                    {
                        "id": <?=$product["id"];?>,
                        "name": '<?=$product["name"];?>',
                        "brand": '<?=$product["brand"];?>',
                        "price": <?=$product["price"];?>,
                        "quantity": <?=$product["quantity"];?>,
                    },
                    <?endforeach;?>
                ]
            }
        }
    });

    gtag('event', 'purchase', {
        "transaction_id": "<?=$orderB->getId();?>",
        "affiliation": "kocmo.by",
        "value": <?=$basket->getPrice();?>,
        "currency": "BYN",
        //"shipping": 0,
        "items": [
            <?foreach($products as $product):?>
            {
                "id": <?=$product["id"];?>,
                "name": '<?=$product["name"];?>',
                "brand": '<?=$product["brand"];?>',
                "price": <?=$product["price"];?>,
                "quantity": <?=$product["quantity"];?>,
            },
            <?endforeach;?>
        ]
    });
</script>
