<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Lui\Kocmo\IncludeComponent as Component;
use Lui\Kocmo\PropertyPage;

$redirects = include $_SERVER['DOCUMENT_ROOT'] . '/include/redirects.php';
//pr( $redirects, 14);
$url = $_SERVER['REQUEST_URI'];
$pos = strpos($_SERVER['REQUEST_URI'], '?');

if( $pos === false){
    $url = $_SERVER['REQUEST_URI'];
}
else{
    $url = substr($_SERVER['REQUEST_URI'], 0, $pos);
}

if($redirects[ $url ] && false){
    header('Location: ' . 'http://' . $_SERVER['SERVER_NAME'] . $redirects[$url] );
    exit;
}

global $OBJ_ITEMS;
CJSCore::Init(array("date"));
CAjax::Init();
global $USER;
if (!is_object($USER)) $USER = new \CUser;
Loc::loadMessages(__FILE__);
$obAsset = Asset::getInstance();
$obPage = new PropertyPage();
define('KOCMO_TEMPLATE_PATH', SITE_TEMPLATE_PATH . '/imposition/build/assets');
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="<?= SITE_CHARSET ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <title><? $APPLICATION->ShowTitle() ?></title>

<?if(false):?>
<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-117911007-1', 'auto');
	  ga('require', 'displayfeatures');
	  ga('send', 'pageview');
      ga ('require', 'ecommerce');
</script>
<?else:?>
    <!-- Global Site Tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-117911007-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-117911007-1');
        gtag('event', 'page_view', { 'send_to': 'UA-117911007-1' });
        //('require', 'ec');
    </script>
<?endif;?>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="theme-color" content="#ffffff">
    <?
    // CSS
    $obAsset->addCss("/bitrix/css/main/font-awesome.css");
    $obAsset->addCss(KOCMO_TEMPLATE_PATH . "/css/style.css");
    $obAsset->addCss(KOCMO_TEMPLATE_PATH . "/css/custom.css");
    $obAsset->addCss(SITE_TEMPLATE_PATH . "/css/style_custom.css");
    //  JS
    $obAsset->addJs(KOCMO_TEMPLATE_PATH . "/js/libs.js");
    $obAsset->addJs(KOCMO_TEMPLATE_PATH . "/js/main.js");
    $obAsset->addJs(SITE_TEMPLATE_PATH . "/js/jquery.maskedinput.min.js"); // https://itchief.ru/lessons/javascript/input-mask-for-html-input-element
    $obAsset->addJs(SITE_TEMPLATE_PATH . "/js/script_costum.js");
    $obAsset->addJs(SITE_TEMPLATE_PATH . "/js/retailrocket.js");
    $obAsset->addJs( "https://cdn.sendpulse.com/sp-push-worker-fb.js?ver=2.0");
    ?>
    <? $APPLICATION->ShowHead(); ?>
    <script charset="UTF-8" src="//web.webpushs.com/js/push/d4890b712f6fb2ad2f6031f129ac63a7_1.js" async></script>

    <?if($USER->IsAdmin()):?>
        <script src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@12.4.0/dist/lazyload.min.js"></script>
    <?endif;?>
</head>
<body>
<? $APPLICATION->IncludeComponent(
    "h2o:favorites.add",
    "list",
    Array()
); ?>
<!-- Yandex.Metrika counter -->
<script>
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
        m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(47438272, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true,
        ecommerce:"dataLayer"
    });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/47438272" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script>
    window.dataLayer = window.dataLayer || [];
</script>
<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '494031391144532');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?..."
/></noscript>
<!-- End Facebook Pixel Code -->
<? $APPLICATION->ShowPanel() ?>
<? Component::AdvertisingBanner(['template' => 'top', 'QUANTITY' => '1', 'TYPE' => 'MAIN']) ?>
<header class="header">
    <div class="header-inner">
        <div class="header__top">
            <div class="container header__top-inner">
                <div class="header-place">
                    <a href="#" class="header-place__item">
                        <svg width="16" height="21">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-location"></use>
                        </svg>
                        <span>Ваш город:</span> Минск
                    </a>
                    <a href="/about/stores/" class="header-place__item">
                        <svg width="21" height="21">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-find-shop"></use>
                        </svg>
                        Hайти магазин
                    </a>
                </div>
                <? Component::Menu(['template' => 'top', 'ROOT_MENU_TYPE' => 'top', 'MAX_LEVEL' => '1']) ?>
                <a href="/action-list/" class="header__top-promo">
                    <svg width="24" height="29">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-promo-page"></use>
                    </svg>
                    Акционная листовка
                </a>
            </div>
        </div>


        <div class="header__middle">
            <div class="container header__middle-inner">
                <div class="mobile-burger">
                    <span class="mobile-burger__line"></span>
                </div>

                <?if($_GET['tsearch'] == 'new'):?>
                    <?$APPLICATION->IncludeComponent("bitrix:search.title", "base",Array(
                            "SHOW_INPUT" => "Y",
                            "INPUT_ID" => "title-search-input",
                            "CONTAINER_ID" => "title-search",
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
                <form class="header-search field-bordered" method="get" action="/catalog/" name="">
                    <button type="submit" value="" class="header-search__submit">
                        <svg width="20" height="18">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-magnifier"></use>
                        </svg>
                    </button>
                    <input type="text" name="q"
                           value="<?= htmlspecialchars($_REQUEST['q']) ? htmlspecialchars($_REQUEST['q']) : '' ?>"
                           class="header-search__text"
                           placeholder="Введите свой поисковый запрос">
                </form>
                <?endif;?>
                <a <?= URL() == '/' ? '' : 'href="/"' ?> class="header__middle-logo">
                    <img src="<?= KOCMO_TEMPLATE_PATH?>/images/logo.png" alt="" class="header-logo">
                    <img src="<?= KOCMO_TEMPLATE_PATH?>/images/logo-mobile.png" alt="" class="header-logo-mobile">
                </a>

                <a href="/action-list/" class="header__middle-promo">
                    <svg width="24" height="29">
                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-promo-page"></use>
                    </svg>
                    Акционная листовка
                </a>

                <div class="personality-state">
                    <a href="#popup-mob-search"
                       class="fancybox personality-state__item personality-state__item--fixed-show personality-state__item--mobile-show">
                        <svg width="25" height="25">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-magnifier"></use>
                        </svg>
                        Поиск по сайту
                    </a>
                    <? $APPLICATION->IncludeComponent(
                        "h2o:favorites.line",
                        "header",
                        Array(
                            "URL" => "/user/favourites/",
                            "CACHE_TIME" => "36000000",
                            "CACHE_TYPE" => "A",
                            "URL_LIST" => ""
                        )
                    ); ?>

                    <? if (\Lui\Kocmo\Helper\Url::Not('/cart/')) { ?>
                        <? AjaxContent::Start('header_basket') ?>

                        <? Component::SaleBasketBasket(['template' => 'top_basket']) ?>

                        <? AjaxContent::Finish('header_basket') ?>
                    <? } ?>
                    <? if ($USER->IsAuthorized()) { ?>
                            <span  class="personality-state__item personality-state__item--registered">
                                <svg width="25" height="25">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-cabinet"></use>
                                </svg>
                                <div>
                                    <? $arUserGet = explode(' ', $USER->GetFullName()); ?>
                                    <div><a class="profile-name" href="/user/profile/"> <?= $arUserGet[0] ?> <?= $arUserGet[1] ?></a></div>
                                    <div> <a class="logout" href="/?logout=yes">Выйти</a></div>
                                </div>
                            </span>
                    <? } else { ?>
                        <a href="/auth/" class="personality-state__item">
                            <svg width="25" height="25">
                                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-cabinet"></use>
                            </svg>
                            <span>Личный кабинет</span>
                        </a>
                    <? } ?>
                </div>
            </div>
        </div>
        <? Component::Menu([
            'template' => 'catalog',
            'ROOT_MENU_TYPE' => 'catalog',
            'MAX_LEVEL' => '3',
            "CHILD_MENU_TYPE" => "catalog",
            "USE_EXT" => "Y"
        ]); ?>
    </div>
</header>
<div class="mobile-nav">
    <div class="mobile-nav-header">
        <a href="#" class="mobile-nav-header__item">
            <svg width="22" height="22">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-wishlist"></use>
            </svg>
            Избранное
        </a>

        <? if ($USER->IsAuthorized()) { ?>
            <a href="/user/profile/" class="mobile-nav-header__item personality-state__item--registered">
                <svg width="25" height="25">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-cabinet"></use>
                </svg>
                <div>
                    <? $arUserGet = explode(' ', $USER->GetFullName()); ?>
                    <div><?= $arUserGet[0] ?></div>
                    <div><?= $arUserGet[1] ?></div>
                </div>
            </a>
        <? } else { ?>
            <a href="/auth/" class="mobile-nav-header__item">
                <svg width="22" height="22">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-cabinet"></use>
                </svg>
                Личный кабинет
            </a>
        <? } ?>
    </div>
    <? Component::Menu([
        'template' => 'header_mobil_menu',
        'ROOT_MENU_TYPE' => 'catalog',
        'MAX_LEVEL' => '1',
        "CHILD_MENU_TYPE" => "catalog",
        "USE_EXT" => "Y"
    ]); ?>
    <div class="mobile-nav-footer">
        <? Component::NewsList(['template' => 'header_mob_soc', 'PARENT_SECTION' => '23']) ?>
        <div class="mobile-nav__contacts">
            <a href="tel:+375296261414" class="mobile-nav__phone">626-14-14</a>
            <div>
                <div class="mobile-nav__contacts-title">Горячая линия</div>
                <div class="mobile-nav__contacts-schedule">ежедневно с 10:00 до 21:00</div>
            </div>
        </div>
        <div class="mobile-nav__loc-wrap">
            <a href="/about/stores/" class="mobile-nav__loc-find">
                <svg width="21" height="21">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-find-shop"></use>
                </svg>
                Hайти магазин
            </a>
            <a href="#" class="mobile-nav__location">
                <svg width="16" height="21">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-location"></use>
                </svg>
                <span>Ваш город:</span> Минск
            </a>
        </div>
    </div>
</div>
<div class="mobile-nav-overlay"></div>

<main class="main">
    <? if (URL() != '/' and URL() != '/auth/'): ?>
        <div class="page-title-wrap">
            <div class="container">

                <? $APPLICATION->IncludeComponent("bitrix:breadcrumb", "header_breadcrumb", Array(
                    "PATH" => "",
                    "SITE_ID" => "s1",
                    "START_FROM" => "0",
                    "COMPONENT_TEMPLATE" => ".default"
                ),
                    false
                ); ?>

                <?= $APPLICATION->AddBufferContent("Hide_H1"); ?>
                <?= $APPLICATION->ShowViewContent("search-result-info"); ?>

            </div>
        </div>
    <? endif; ?>

    <div class="<? $APPLICATION->ShowViewContent('DOP_CLASS_CONTAINER'); ?>
        <?= $obPage->isView('SHOW_SIDEBAR') ? 'inner-with-aside' : '' ?>
        <? if (URL() != '/'): ?>
            <?= $obPage->getProp('class_container') ? $obPage->getProp('class_container') : 'container' ?>
        <? endif ?>
        ">
        <? if ($obPage->isView('SHOW_SIDEBAR')): ?>
        <div class="aside">
            <div class="aside-nav-wrap js_aside-nav-wrap">
                <? if ($obPage->isView('Show_aside_left_1')): ?>
                    <? Component::Menu(["NAME" => $obPage->getProp('aside-left-1-name'), 'template' => 'aside_left_1', 'ROOT_MENU_TYPE' => 'aside-left-1',]); ?>
                <? endif; ?>
                <? if ($obPage->isView('Show_aside_left_2')): ?>
                    <? Component::Menu(["NAME" => $obPage->getProp('aside-left-2-name'), 'template' => 'aside_left_2', 'ROOT_MENU_TYPE' => 'aside-left-2',]); ?>
                <? endif; ?>
            </div>
        </div>
        <div class="container">

<? endif; ?>
