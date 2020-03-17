<?php

use Lui\Kocmo\Data\IblockElement;

class EGift
{
    public $ID;
    public $IBLOCK_ID = 12;
    public $OB;

    public function __construct($elem_id = false)
    {

        if($elem_id){
            $this->ID = $elem_id;
            $arFilter = [
                'IBLOCK_ID' => $this->IBLOCK_ID,
                'ID' => $elem_id,
            ];
            $data = new IblockElement();
            $this->OB = $data->GetData($arFilter)[$elem_id];
        }

    }

    public static function TestNumberEGift(int $order_id, int $egift_id){
        $order = new MyOrder();

        $order = $order->GetOrder($order_id);
        $EGift = new EGift($egift_id);

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

        $arJson = [
            'uid_order'=>$uid_order,
            'uid_EGifts'=>$gui,
        ];
        $sJson = json_encode($arJson);
        $ob = new \Lui\Kocmo\Request\Get\EGifts($sJson);
        $num_egift = $ob->Send();

        PR('UID ORDER - '.$uid_order, true);
        PR('UID PAYMENT - '.$uid_payment, true);
        PR('VALUE - '.$value, true);
        PR($status_payment, true);
        PR($num_egift, true);
    }

    public function GetHtmlEmail($arData = []){

        if(empty($arData)){$arData = $this->OB;}

        $URL_IMG = "http://".$_SERVER['SERVER_NAME']."/".$arData['PROPERTY']['COLOR_SERTIFIKAT'];
        $SHTRIH_KOD = $arData['PROPERTY']['SHTRIH_KOD'];
        $SUM_SERTIFIKAT = $arData['PROPERTY']['SUM_SERTIFIKAT'];
        $PREVIEW_TEXT = $arData['PREVIEW_TEXT'];
        $NAME_OTPRAVITEL = $arData['PROPERTY']['NAME_OTPRAVITEL'];
        $FIO = $arData['PROPERTY']['FIO'];


        $HTML_FIO = '';
        if($this->OB['PROPERTY']['FIO']){
            $HTML_FIO  .= '<tr>
            <td align="center" height="22">
                <font face="Calibri, Tahoma, Verdana, Segoe, sans-serif"
                      style="font-size:20px;font-style: normal;font-weight: normal;color:#222222;line-height: 22px;">';
            $HTML_FIO .= $FIO.'</font>';//$HTML_FIO .= 'Уважаемый(ая) '.$FIO.'!</font>';
            $HTML_FIO .= '</td></tr>';
        }


        $html = <<<LABEL
<table cellspacing="0" cellpadding="0" border="0" width="100%" height="100%" bgcolor="#F5F3F6">
    <tbody>
    <tr>
        <td width="100%" align="center" valign="top">
            <table cellspacing="0" cellpadding="0" border="0" width="600" style="width: 600px">
                <tbody>
                <tr>
                    <td style="padding:0">
                        <table cellspacing="0" cellpadding="0" border="0" style="background-repeat: round; background-size: cover;" background="{$URL_IMG}"
                               width="600" height="722">
                            <tbody>
                            <tr>
                                <td height="2" bgcolor="#D01C60">
                                </td>
                            </tr>
                            <tr>
                                <td height="45">
                                </td>
                            </tr>
                            <tr>
                                <td height="154" align="center">
                                    <img src="https://{$_SERVER['SERVER_NAME']}/upload/assets/img/egift-logo.png"
                                         width="350" height="154"
                                         style="display:block">
                                </td>
                            </tr>
                            <tr>
                                <td height="30">
                                </td>
                            </tr>
                            <tr height="22">
                                <td align="center">
                                    <font face="Calibri, Tahoma, Verdana, Segoe, sans-serif"
                                          style="font-size:20px;font-style: normal;font-weight: normal;color:#222222;line-height: 22px;">
                                        номер сертификата {$SHTRIH_KOD}</font>
                                </td>
                            </tr>
                            <tr>
                                <td height="5">
                                </td>
                            </tr>
                            <tr>
                                <td align="center" height="22">
                                    <font face="Calibri, Tahoma, Verdana, Segoe, sans-serif"
                                          style="font-size:24px;font-style: normal;letter-spacing: 0.02em;font-weight: 300;color:#D01C60;line-height: 22px;">
                                        на сумму {$SUM_SERTIFIKAT} BYN</font>
                                </td>
                            </tr>
                            <tr>
                                <td height="30">
                                </td>
                            </tr>
                            <tr>
                                <td align="center" height="13">
                                    <img src="https://{$_SERVER['SERVER_NAME']}/upload/assets/img/line.png"
                                         width="505" height="13"
                                         style="display:block">
                                </td>
                            </tr>
                            <tr>
                                <td height="31">
                                </td>
                            </tr>
                            
                            {$HTML_FIO}
                            
                            <tr>
                                <td height="6">
                                </td>
                            </tr>
                            <tr>
                                <td  align="center">
                                    <table cellspacing="0" cellpadding="0" border="0"
                                           width="477" >
                                        <tr>
                                            <td align="center">
                                                <font face="Calibri, Tahoma, Verdana, Segoe, sans-serif"
                                                      style="font-size:20px;font-style: normal;font-weight: normal;color:#7C7C7C;line-height: 24px;">
                                                    {$PREVIEW_TEXT}
                                                </font>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                            <tr>
                                <td height="18">

                                </td>
                            </tr>
                            <tr>
                                <td height="22" align="center">
                                    <font face="Calibri, Tahoma, Verdana, Segoe, sans-serif"
                                          style="font-size:20px;font-style: normal;font-weight: normal;color:#222222;line-height: 22px;">
                                        {$NAME_OTPRAVITEL}
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>
                            <tr>
                                <td height=""></td>
                            </tr>
                            <tr>
                                <td height="22" align="center">
                                    <font face="Calibri, Tahoma, Verdana, Segoe, sans-serif"
                                          style="font-size:16px;font-style: normal;font-weight: normal;color:#9D9D9D;line-height: 23px;">
                                        Сертификат может быть использован в любом магазине <br>
                                        КОСМО и при покупке в интернет-магазине kocmo.by</font>
                                </td>
                            </tr>
                            <tr height="109">
                                <td></td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
LABEL;

        return $html;

    }


}