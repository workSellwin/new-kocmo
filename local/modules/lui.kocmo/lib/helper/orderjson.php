<?php


namespace Lui\Kocmo\Helper;


use Lui\Kocmo\Request\Basket;

class OrderJson
{
    /**
     * @var \Bitrix\Sale\Order
     */
    protected $obOrder;
    protected $orderProp;
    protected $obBasket;
    protected $arJson = [];
    protected $sJson = '';

    /**
     * @param $orderId
     */
    public function __construct(int $orderId)
    {
        $this->Init($orderId);
    }

    /**
     * @param $orderId
     * @throws \Exception
     */
    protected function Init($orderId)
    {
        $order = \Bitrix\Sale\Order::load($orderId);
        if (is_object($order)) {
            $this->arJson = DataOrderJson::Order();
            $this->obOrder = $order;
            $this->obBasket = $order->getBasket();
            $this->orderProp = $this->GetOrderProp($order);
        } else {
            throw  new \Exception('No OrderID');
        }
    }

    protected function Run()
    {
        $this->SetOrderID();
        $this->SetDelivery();
        $this->SetPayment();
        $this->SetProperty();
        $this->SetBasket();
        $this->SetJson();
    }

    public function GetsJson()
    {
        $this->Run();
        return $this->sJson;
    }

    public function GetArJson()
    {
        $this->Run();
        return $this->arJson;
    }

    public function GetsJsonOrderId($orderId)
    {
        $this->Init($orderId);
        $this->Run();
        return $this->sJson;
    }

    /****************************/
    protected function SetOrderID()
    {
        $this->arJson['OrderId'] = $this->obOrder->getId();
    }

    protected function SetDelivery()
    {
        $deliveryIds = $this->obOrder->getDeliverySystemId();
        $arDelivery = \Bitrix\Sale\Delivery\Services\Manager::getById(reset($deliveryIds));
        if ($arDelivery) {
            $this->arJson['delivery']['name'] = $arDelivery['NAME'];
            $this->arJson['delivery']['uid'] = $arDelivery['XML_ID'];
            $this->arJson['delivery']['price'] = $this->obOrder->getDeliveryPrice();

            if ($this->orderProp['TIME_OF_DELIVERY']['value']) {
                $this->arJson['delivery']['interval'] = $this->orderProp['TIME_OF_DELIVERY']['value'];
            }

            if (true) {
                $arParamsDelivery = [
                    'date_time' => $this->orderProp['DATE_OF_DELIVERY']['value'] . ' 01:00:00',
                    'adress' => "Независимости 6"
                ];
                $this->arJson['delivery']['params'] = $arParamsDelivery;
            }
        }
    }

    protected function SetPayment()
    {
        $paymentIds = $this->obOrder->getPaymentSystemId();
        $arPaySys = \CSalePaySystem::GetByID(reset($paymentIds));

        if ($arPaySys) {
            $this->arJson['payment'] = [
                'name' => $arPaySys['NAME'],
                'uid' => $arPaySys['XML_ID'],
                'certificate' => [],
            ];

            if ($GIFT_CERTIFICATE = $this->orderProp['GIFT_CERTIFICATE']['value']) {
                if ($value = $this->orderProp['COPY']['value']) {
                    $ob = new \Lui\Kocmo\Request\Get\Certificate($GIFT_CERTIFICATE);
                    $data = $ob->Send();
                    if ($data['status'] == 'ok') {
                        $data = $data['certificate'][0];
                        $this->arJson['payment']['certificate'] = [[
                            'uid' => $data['uid'],
                            'number' => $GIFT_CERTIFICATE,
                            'value' => $value,
                            'face_value' => $data['face_value'],
                            'balance' => $data['balance'],
                        ]];
                    }
                }
            }
        }
    }

    protected function SetProperty()
    {
        if ($this->orderProp['NAME']['value']) {
            $this->arJson['personal'] ['name'] = $this->orderProp['NAME']['value'];
        }
        if ($this->orderProp['SURNAME']['value']) {
            $this->arJson['personal'] ['lastname'] = $this->orderProp['SURNAME']['value'];
        }
        if ($this->orderProp['EMAIL']['value']) {
            $this->arJson['personal'] ['email'] = $this->orderProp['EMAIL']['value'];
        }
        if ($this->orderProp['PHONE']['value']) {
            $this->arJson['personal'] ['phone'] = $this->orderProp['PHONE']['value'];
        }
        // Адресс
        if ($this->orderProp['STREET']['value']) {
            $this->arJson['personal']['adress']['street'] = $this->orderProp['STREET']['value'];
        }
        if ($this->orderProp['HOUSE']['value']) {
            $this->arJson['personal']['adress']['house'] = $this->orderProp['HOUSE']['value'];
        }
        if ($this->orderProp['CORPS']['value']) {
            $this->arJson['personal']['adress']['corps'] = $this->orderProp['CORPS']['value'];
        }
        if ($this->orderProp['APARTMENT']['value']) {
            $this->arJson['personal']['adress']['flat'] = $this->orderProp['APARTMENT']['value'];
        }


        if ($this->orderProp['CARD_COSMO']['value']) {
            $this->arJson['card'] = $this->orderProp['CARD_COSMO']['value'];
        }

        if ($this->orderProp['PROMO_CODE']['value']) {
            $this->arJson['promo'] = $this->orderProp['PROMO_CODE']['value'];
        }
    }

    protected function SetBasket()
    {
        $this->arJson['goods'] = [];
        $this->arJson['GIFT'] = [];
        $ob = new Basket();
        $arGoods = $ob->RunOrder($this->obOrder);
        $arGoods = array_column($arGoods['goods'], null, 'UID');

        foreach ($this->obBasket as $item) {
            $basketPropertyCollection = $item->getPropertyCollection();
            $arProperty = $basketPropertyCollection->getPropertyValues();
            $arProperty = array_values($arProperty);
            $arProperty = array_column($arProperty, 'VALUE', 'CODE');
            $uid = $arProperty['PRODUCT.XML_ID'];
            if ($goods = $arGoods[$uid]) {
                $this->arJson['goods'][] = $goods;
            } else {
                  $this->arJson['goods'][] = [
                      'UID' => $arProperty['PRODUCT.XML_ID'] ?? $item->getField('PRODUCT_XML_ID'),
                      'COUNT' => $item->getQuantity(),
                      'PRICE' => round($item->getPrice(), 2),
                      'SUMM' => round($item->getFinalPrice(), 2),
                      'DISCOUNT' => [],
                  ];
            }
        }
    }


    protected function SetJson()
    {
        $this->sJson = json_encode($this->arJson);
    }

    /********************************************/

    /**
     * @param \Bitrix\Sale\Order $obOrder
     * @return array
     */
    protected function GetOrderProp(\Bitrix\Sale\Order $obOrder)
    {
        $arOrderProp = [];
        $propertyCollection = $obOrder->getPropertyCollection();
        $ar = $propertyCollection->getArray();
        foreach ($ar['properties'] as $p) {
            $arOrderProp[$p['CODE']] = [
                'name' => $p['NAME'],
                'type' => $p['TYPE'],
                'value' => reset($p['VALUE']),
            ];
        }
        return $arOrderProp;
    }
}