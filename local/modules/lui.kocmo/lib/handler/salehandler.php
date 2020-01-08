<?php

namespace Lui\Kocmo\Handler;

use Bitrix\Sale\Order;

class SaleHandler extends BaseHandler
{
    public $module = 'sale';

    /**
     * ENTITY order  Объект заказа.
     * IS_NEW boolean Принимает одно из двух значений: true - если заказ новый, false - если нет.
     * VALUES array Старые значения полей заказа.
     *
     * @param \Bitrix\Main\Event $event
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentOutOfRangeException
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\NotImplementedException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    function OnSaleOrderSaved(\Bitrix\Main\Event $event)
    {
        /** @var Order $order */
        $order = $event->getParameter("ENTITY");
        $oldValues = $event->getParameter("VALUES");
        $isNew = $event->getParameter("IS_NEW");

    }

}
