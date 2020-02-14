<?php

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Lui\Kocmo\Request\Post\SetPayment;
use Bitrix\Sale;

define('NO_IMG_PATH', '/upload/base/no_image.png');
define('NO_IMG_PATH_225', '/upload/base/no_image_225.png');
Loader::includeSharewareModule("htc.twigintegrationmodule");
// Сброс кеша твига при обычном сбросе кеша шаблонов
$request = Application::getInstance()->getContext()->getRequest();
if ($request->getQuery("clear_cache") == "Y") {
    TwigTemplateEngine::clearCacheFiles();
}

Loader::includeModule("lui.kocmo");
Loader::includeModule("catalog");
Loader::includeModule("iblock");
Loader::includeModule("sale");

include_once __DIR__ . '/lib.php';
include $_SERVER['DOCUMENT_ROOT'] . '/local/modules/lui.kocmo/init.php';


AddEventHandler('main', 'OnAdminContextMenuShow', 'OrderDetailAdminContextMenuShow');
function OrderDetailAdminContextMenuShow(&$items)
{
    global $APPLICATION;
    if ($_SERVER['REQUEST_METHOD'] == 'GET' && $GLOBALS['APPLICATION']->GetCurPage() == '/bitrix/admin/iblock_list_admin.php' && $_REQUEST['IBLOCK_ID'] == 5) {
        $button = array(
            "TEXT" => "Обновить акции из 1с",
            "LINK" => $APPLICATION->GetCurPageParam("ACTIONS-UPDATE=Y", ['ACTIONS-UPDATE']),
            "TITLE" => "Обновить акции из 1с",
            "ICON" => "btn_new",
        );
        array_push($items, $button);
    }
}

AddEventHandler('main', 'OnPageStart', 'OnPageStartActionUpdate');

function OnPageStartActionUpdate()
{
    if ($_REQUEST['ACTIONS-UPDATE'] == 'Y') {
        $obS = new \Lui\Kocmo\Request\Set\Actions();
        try {
            $obS->Update();
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            AddMessage2Log($e->getMessage());
        } catch (\Exception  $e) {
            AddMessage2Log($e->getMessage());
        }
    }
}


AddEventHandler('sale', 'OnSalePayOrder', 'OnSalePayOrderActionUpdateEGift');
function OnSalePayOrderActionUpdateEGift($order_id, $status)
{
    if ($status == 'Y') {
        CModule::IncludeModule("sale");
        CModule::IncludeModule("iblock");
        $order = new MyOrder();
        $order = $order->GetOrder($order_id);
        $propertyCollection = $order->getPropertyCollection();
        $arProperty = $propertyCollection->getArray();
        $arProperty = array_column($arProperty['properties'], 'VALUE', 'CODE');

        //проверка на сертификат
        if (count($arProperty['EGIFT']) == 1 && in_array('Y', $arProperty['EGIFT'])) {

            $payment = 8;
            $price_order = $order->getPrice(); // Сумма заказа
            $isPaid = $order->isPaid(); // true, если оплачен
            $price_paid = $order->getSumPaid(); // Оплаченная сумма

            if ($isPaid) {
                //Сумма заказа = Оплаченная сумма
                if ($price_order == $price_paid) {
                    /*$order->setBasket($basket);
                    $basket = $order->getBasket();*/
                    $paymentIds = $order->getPaymentSystemId();
                    if (in_array($payment, $paymentIds)) {

                        $basket = $order->getBasket();
                        foreach ($basket as $basketItem) {
                            $id = $basketItem->getProductId();
                        }

                        $EGift = new EGift($id);

                        $paymentCollection = $order->getPaymentCollection();
                        $data_payment = [];
                        foreach ($paymentCollection as $payment) {
                            $ps = $payment->getPaySystem();
                            $data_payment['XML_ID'] = $ps->getField('XML_ID');
                            $data_payment['VALUE'] = $payment->getSum();
                        }

                        $gui = $EGift->OB['PROPERTY']['GUI_SERTIFIKAT'];
                        $uid_order = $order->getField('XML_ID');
                        $uid_payment = $data_payment['XML_ID'];
                        $value = $data_payment['VALUE'];

                        $status_payment = new \Lui\Kocmo\Request\Post\SetPayment($uid_order, $uid_payment, $value);
                        $status_payment = $status_payment->Send();
                        //получение номера E-Gift
                        // не реализовано

                        if(!$status_payment['ERROR']){

                            $arJson = [
                                'uid_order'=>$order->getField('XML_ID'),
                                'uid_EGifts'=>$gui,
                            ];
                            $sJson = json_encode($arJson);
                            $ob = new \Lui\Kocmo\Request\Get\EGifts($sJson);
                            $num_egift = $ob->Send();
                        }

                        if ($num_egift['number']) {

                            CIBlockElement::SetPropertyValuesEx($id, false, ['OPLACHEN' => 1601, 'SHTRIH_KOD' => $num_egift['number']]);
                            CIBlockElement::SetPropertyValuesEx($id, false, ['DATE' => date('d.m.Y')]);
                            CIBlockElement::SetPropertyValuesEx($id, false, ['ORDER_ID' => $order_id]);

                            $arPropCode = ["SHTRIH_KOD", "OPLACHEN", "EMAIL", 'EMAIL_SENT', 'DATE'];
                            $PROP = [];
                            foreach ($arPropCode as $val) {
                                $prop = CIBlockElement::GetProperty(12, $id, ["sort" => "asc"], ["CODE" => $val]);
                                if ($ar_props = $prop->Fetch()) {
                                    $PROP[$ar_props['CODE']] = $ar_props;
                                }
                            }

                            $result = (strtotime($PROP['DATE']['VALUE']) <= strtotime(date('d.m.Y')));
                            if ($result || $PROP['DATE']['VALUE'] == '') {

                                //отпровляем сертификат на указанный email
                                if ($PROP['EMAIL']['VALUE']) {
                                    $EGift = new EGift($id);
                                    $html = $EGift->GetHtmlEmail();
                                    $arEventFields = [
                                        "HTML" => $html,
                                        "EMAIL_TO" => $PROP['EMAIL']['VALUE'],
                                    ];

                                    $sent_email = CEvent::Send('E-GIFT', 's1', $arEventFields);
                                }

                                if ($sent_email) {
                                    CIBlockElement::SetPropertyValuesEx($id, false, ['EMAIL_SENT' => 1602]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

if(!function_exists('exchangeChangeQuantity')) {
    function exchangeChangeQuantity($productId, $quantity, $quantityOld)
    {
        pr([$productId, $quantity, $quantityOld], 17);
        CModule::IncludeModule("iblock");
        if($quantity > 0){ 
            CIBlockElement::SetPropertyValuesEx($productId, false, ['ONLINE_STORE' => 249]);
            CIBlockElement::SetPropertyValuesEx($productId, false, ['UNDER_ORDER' => '']);
        }else{
            CIBlockElement::SetPropertyValuesEx($productId, false, ['ONLINE_STORE' => '']);
            CIBlockElement::SetPropertyValuesEx($productId, false, ['UNDER_ORDER' => 250]);
        }
    }
}

AddEventHandler("main", "OnBeforeEventSend", "sendMailNewOrder");

function sendMailNewOrder(&$arFields, &$eventMessage){

    if( $eventMessage['ID'] == 95 && intval($arFields['ORDER_ID']) > 0 ){

        $order = Sale\Order::load($arFields['ORDER_ID']);
        $deliveryIds = $order->getDeliveryIdList();

        if( in_array(2, $deliveryIds) || in_array(4, $deliveryIds)){

            $propertyCollection = $order->getPropertyCollection();
            $properties = $propertyCollection->getArray()['properties'];
            $properties = array_column($properties, null, 'CODE');
            $deliveryTime = $properties['TIME_OF_DELIVERY'];
            $deliveryTimeStr = $deliveryTime['OPTIONS'][$deliveryTime['VALUE'][0]];
            $arFields['DELIVERY_TIME'] = $deliveryTimeStr;
            $eventMessage['MESSAGE'] = str_replace('display:none;',' ',$eventMessage['MESSAGE']);
            $eventMessage['MESSAGE_PHP'] = str_replace('display:none;',' ',$eventMessage['MESSAGE_PHP']);
            //file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/MESSAGE_PHP.txt', print_r($eventMessage['MESSAGE_PHP'], true));
        }

        $basket = $order->getBasket();
        $fullPrice = $basket->getBasePrice();
        $discountPrice = $basket->getPrice();
        $economy = round($fullPrice - $discountPrice, 2);
        $economy = $economy > 0 ? $economy : 0;

        $arFields['YOUR_ECONOMY'] = $economy;

        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/TEST0.txt', print_r($eventMessage, true));
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/TEST2.txt', print_r($arFields, true));
    }

}

