<?php


namespace Lui\Kocmo;


class Agents
{
    /**
     * @return string
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ArgumentNullException
     * @throws \Bitrix\Main\NotImplementedException
     */
    public static function Sends1C()
    {
        \CModule::IncludeModule('sale');
        $dbRes = \Bitrix\Sale\Order::getList(['order' => ['ID' => 'DESC',], 'select' => ["ID",], 'limit' => 10]);
        $arOrder = array_column($dbRes->fetchAll(), 'ID');
        foreach ($arOrder as $orderId) {
            $order = \Bitrix\Sale\Order::load($orderId);
            $arProp = Lui\Kocmo\Helper\Order::GetProperty($order);
            if (!$arProp['UID']) {
                \Lui\Kocmo\Helper\Order::Send1c($orderId);
            }
        }
        return '\Lui\Kocmo\Agents::Sends1C();';
    }
}
