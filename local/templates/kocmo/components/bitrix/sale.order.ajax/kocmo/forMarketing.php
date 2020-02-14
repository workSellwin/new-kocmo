<?php
$basket = $orderB->getBasket();
$basketItems = $basket->getBasketItems();
//pr($basket,14);
?>
<script>
    dataLayer.push({
        "ecommerce": {
            "purchase": {
                "actionField": {
                    "id" : "<?=$orderB->getId();?>"
                },
                "products": [
                    <?foreach($basketItems as $item):?>
                    {
                        "id": <?=$item->getProductId();?>,
                        "name": <?=$item->getField('NAME');?>,
                        "price": <?=$item->getPrice();?>,
                        "quantity": <?=$item->getQuantity();?>,
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
            {
                <?foreach($basketItems as $item):?>
                "id": <?=$item->getProductId();?>,
                "name": <?=$item->getField('NAME');?>,
                //"list_name": "Search Results",
                //"brand": "Google",
                //"category": "Apparel/T-Shirts",
                //"variant": "Black",
                //"list_position": 1,
                "quantity": <?=$item->getQuantity();?>,
                "price": <?=$item->getPrice();?>
                <?endforeach;?>
            },
        ]
    });
</script>
