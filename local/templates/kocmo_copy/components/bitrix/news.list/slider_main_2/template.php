<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);
?>

<? if ($arResult["ITEMS"]) { ?>
    <div class="js_full-width-slider full-width-slider swiper-container swiper-container-no-flexbox main-slider">
        <div class="full-width-slider__wrapper swiper-wrapper">
            <? foreach ($arResult["ITEMS"] as $arItem) { ?>
                <?
                $prop = array_column($arItem['PROPERTIES'], '~VALUE', 'CODE');

                $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
                $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

                $video = false;

                if ( !empty($arItem['PROPERTIES']['VIDEO']["VALUE"]) ) {
                    $videoData = $arItem['PROPERTIES']['VIDEO']["VALUE"];
                    $video = true;
                    //autoplay="autoplay"
                    $videoContent = "<div class='thumb-wrap'><video loop='loop' controls preload='auto'>"
                        . "<source src='" . $videoData['path'] . "' type='video/mp4'>"
                    . "</video></div>";
                } elseif (!empty($arItem['PROPERTIES']['IFRAME_YOUTUBE']["VALUE"])) {
                    $video = true;
                    $videoContent = "<div class=\"thumb-wrap\">" . $arItem['PROPERTIES']['IFRAME_YOUTUBE']['~VALUE']['TEXT'] . "</div>";
                }

                if ($video) {
                    ?>
                    <a href="<?= $prop['LINK'] ?>" id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
                       class="full-width-slider__lnk swiper-slide">
                        <?
                        echo $videoContent; ?>
                    </a>
                    <?
                } else {
                    if (!$imgMobile = $arItem["DETAIL_PICTURE"]["SRC"]) {
                        $imgMobile = $arItem["PREVIEW_PICTURE"]["SRC"];
                    }
                    ?>
                    <a href="<?= $prop['LINK'] ?>" id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
                       class="full-width-slider__lnk swiper-slide">
                        <img src="" alt=""
                             data-desktop-src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                             data-mobile-src="<?= $imgMobile ?>"
                             class="full-width-slider__img">
                    </a>
                <? }
            } ?>
        </div>
        <div class="full-width-slider__pagination swiper-pagination"></div>
        <div class="full-width-slider__prev swiper-button-prev"></div>
        <div class="full-width-slider__next swiper-button-next"></div>
    </div>
<? } ?>
<script>
    // setTimeout(function () {
    //
    //     window.mySwiper = document.querySelector('.main-slider').swiper;
    //
    //     mySwiper.on('slideChangeTransitionStart', function () {
    //
    //         for (let i = 0; i < this.slides.length; i++) {
    //
    //             if (i === this.activeIndex) {
    //
    //                 let video = this.slides[this.activeIndex].querySelector('video');
    //
    //                 if (video) {
    //                     console.log('slideChangeTransitionStart ' + video.id);
    //                     video.play();
    //                 }
    //             } else {
    //
    //                 let video = this.slides[this.activeIndex].querySelector('video');
    //
    //                 if (video) {
    //                     //console.log('slideChangeTransitionStart ' + video.id);
    //                     video.pause();
    //                 }
    //             }
    //         }
    //     });
    //
    // }, 2000);

</script>



