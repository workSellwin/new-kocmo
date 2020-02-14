<? use Lui\Kocmo\IncludeComponent as Component;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?><? if ($obPage && $obPage->isView('SHOW_SIDEBAR')) { ?>
    </div>
<? } ?>
</div>
</main>

<footer class="footer">
    <div class="footer-top">
        <div class="container footer-top__inner">
            <a <?= URL() == '/' ? '' : 'href="/"' ?> class="footer__logo">
                <img src="<?= KOCMO_TEMPLATE_PATH?>/images/footer-logo.png" alt="">
            </a>

            <div class="footer__contacts">
                <a href="tel:+375296261414" class="footer__contacts-phone ga_ym_t">626-14-14</a>
                <div class="footer__contacts-schedule">
                    <span class="footer__contacts-schedule-highlighted">Горячая линия</span> <span>Ежедневно с 10:00 до 21:00</span>
                </div>
            </div>
            <? Component::NewsList(['template' => 'footer_soc', 'PARENT_SECTION' => '23']) ?>
        </div>
    </div>
    <div class="footer-middle">
        <div class="container footer-middle__inner">
            <div class="footer-nav-column">
                <? Component::Menu(['template' => 'footer', 'ROOT_MENU_TYPE' => 'footer1',]); ?>
            </div>
            <div class="footer-nav-column">
                <? Component::Menu(['template' => 'footer', 'ROOT_MENU_TYPE' => 'footer2',]); ?>
            </div>
            <div class="footer-nav-column">
                <? Component::Menu(['template' => 'footer-loyalty', 'ROOT_MENU_TYPE' => 'footer3',]); ?>
            </div>

            <? $APPLICATION->IncludeComponent(
                "bh:footer_subscribe",
                "",
                array(), false
            ); ?>

        </div>
    </div>
    <? Component::NewsList(['template' => 'footer_payment', 'PARENT_SECTION' => '22']) ?>
    <div class="container text">
        ООО «Космо-М» <br>
        220014, Республика Беларусь, г. Минск, пер. С. Ковалевской, 60, офис 830
        УНП 191060401 <br>
        Регистрационный номер в Торговом реестре Республики Беларусь 465316 от 12 ноября 2019 года
    </div>
</footer>

<div id="popup-preorder-item" class="popup popup-preorder" style="display: none;">
    <h2 class="popup__title">ПРЕДВАРИТЕЛЬНЫЙ ЗАКАЗ</h2>

    <form action="" method="post" id="form_prod_popup" class="preorder__form">
        <input type="hidden" name="ACTION" value="Subscribe">
        <input type="hidden" name="METHOD" value="Add">
        <input type="hidden" class="input-popup-preorder-item" name="PARAMS[ITEM_ID]" value="">
        <input type="hidden" name="PARAMS[USER_ID]" value="<?=$_SESSION['SESS_AUTH']['USER_ID']?>">
        <input type="hidden" name="PARAMS[SITE_ID]" value="<?=SITE_ID?>">
        <div class="popup-preorder__content">
            <div class="popup-preorder__img">
                <img src="" alt="">
            </div>
            <div class="popup-preorder__product-info-wrap">
                <div class="popup-preorder__product-title"></div>
                <div class="popup-preorder__product-description"></div>
                <div class="popup-preorder__product-sku"></div>
                <div class="popup-preorder__product-color"></div>
            </div>
        </div>
        <div class="popup-preorder__info">
            Чтобы получить уведомление о появлении товара, укажите свой email:
        </div>
        <div class="alert alert-danger subscribe-message" style="display: none;"></div>
        <div class="popup-preorder__submit">
            <div class="popup-preorder__submit-field form-field__required-wrap">
                <input type="email" name="PARAMS[EMAIL]"
                       required
                       class="popup-preorder__input btn form-field__required-input js_field-required-input"
                       id="preorder-submit"
                       value="<?=$_SESSION['SESS_AUTH']['EMAIL']?>">
                <label class="form-field__required-label" for="preorder-submit">Введите ваш email <span
                            class="required">*</span></label>
            </div>

            <input type="submit" value="Отправить" class="popup-preorder__submit-btn">
        </div>
    </form>
</div>


<? Component::ShowSvg(); ?>

<div class="up-btn"></div>


<? global $OBJ_ITEMS; ?>

<script>
    var OBJ_ITEMS = <?echo CUtil::PhpToJSObject($OBJ_ITEMS['OBJ_ITEM'])?>;
</script>
<script>
    var phone = document.getElementsByClassName('ga_ym_t');
    var mail = document.getElementsByClassName('ga_ym_m');
    //console.log(mail);
    for (i=0; i< phone.length; i++){
        phone[i].onclick = function(e) {

            ga('send', 'event', 'tel-info', 'ClickTel');
            yaCounter47438272.reachGoal('ClickTelYM');
            return true;
        };
        phone[i].oncopy = function(e) {
            console.log('click');
            ga('send', 'event', 'tel-info', 'CopyTel');
            yaCounter47438272.reachGoal('CopyTelYM');
            return true;
        };
        phone[i].oncontextmenu = function(e) {
            ga('send', 'event', 'tel-info', 'RightTel');
            yaCounter47438272.reachGoal('RightClickTelYM');
            return true;
        }
    }
    for (i=0; i< mail.length; i++){
        mail[i].click = function(e) {
            ga('send', 'event', 'mail-info', 'ClickMail');
            yaCounter47438272.reachGoal('ClickMailYM');;
            return true;
        };
        mail[i].oncopy = function(e) {
            ga('send', 'event', 'mail-info', 'CopyMail');
            yaCounter47438272.reachGoal('CopyMailYM');
            return true;
        };
        mail[i].oncontextmenu = function(e) {
            ga('send', 'event', 'mail-info', 'RightMail');
            yaCounter47438272.reachGoal('RightClickMailYM');
            return true;
        }
    }
</script>
<?
if (intval($_GET['PAGEN_1']) > 1) {

    $addedText = ' - страница ' . intval($_GET['PAGEN_1']);
    $t = $APPLICATION->GetPageProperty('title');

    if (strpos($t, $addedText) === false) {
        $APPLICATION->SetPageProperty('title', $t . $addedText);
    }

    $d = $APPLICATION->GetPageProperty('description');
    if (strpos($d, $addedText) === false) {
        $APPLICATION->SetPageProperty('description', $d . $addedText);
    }
}
?>
<?
if (intval($_GET['PAGEN_2']) > 1) {

    $addedText = ' - страница ' . intval($_GET['PAGEN_2']);
    $t = $APPLICATION->GetPageProperty('title');

    if (strpos($t, $addedText) === false) {
        $APPLICATION->SetPageProperty('title', $t . $addedText);
    }

    $d = $APPLICATION->GetPageProperty('description');
    if (strpos($d, $addedText) === false) {
        $APPLICATION->SetPageProperty('description', $d . $addedText);
    }
}
?>
<?
///require_once $_SERVER['DOCUMENT_ROOT'] . '/include/first-visit.php';
?>
<?if($_GET['tsearch'] == 'new'):?>
    <?$APPLICATION->IncludeComponent("bitrix:search.title", "base_mob",Array(
            "SHOW_INPUT" => "Y",
            "INPUT_ID" => "title-search-input-mob",
            "CONTAINER_ID" => "mob-search",
            "PRICE_CODE" => array("ROZNICHNAYA","AKTSIONNAYA"),
            "PRICE_VAT_INCLUDE" => "Y",
            "PREVIEW_TRUNCATE_LEN" => "150",
            "SHOW_PREVIEW" => "Y",
            "PREVIEW_WIDTH" => "75",
            "PREVIEW_HEIGHT" => "75",
            "CONVERT_CURRENCY" => "Y",
            "CURRENCY_ID" => "BYN",
            "PAGE" => "#SITE_DIR#catalog/",
            "NUM_CATEGORIES" => "6",
            "TOP_COUNT" => "20",
            "ORDER" => "date",
            "USE_LANGUAGE_GUESS" => "Y",
            "CHECK_DATES" => "Y",
            "SHOW_OTHERS" => "Y",
            "CATEGORY_1_TITLE" => "Каталог",
            "CATEGORY_1" => array("iblock_catalog"),
            "CATEGORY_1_iblock_catalog" => "all",
            "CATEGORY_OTHERS_TITLE" => "Прочее"
        )
    );?>
<?else:?>
    <? $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        Array(
            "AREA_FILE_SHOW" => "file",
            "PATH" => SITE_DIR . "include/mob_search.php",
            "AREA_FILE_RECURSIVE" => "N",
            "EDIT_MODE" => "html",
        )
    ); ?>
<?endif;?>
<?if($USER->IsAdmin()):?>
    <script>
        var lazyLoadInstance = new LazyLoad({
            elements_selector: ".lazy"
        });
        lazyLoadInstance.update();
    </script>
<?endif;?>
</body>
</html>