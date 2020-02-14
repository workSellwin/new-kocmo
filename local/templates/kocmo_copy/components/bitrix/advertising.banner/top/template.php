<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$frame = $this->createFrame()->begin("");
if ($img = $arResult['BANNER_PROPERTIES']['IMAGE_ID']) {
    $src = CFile::GetPath($img);
    $url = $arResult['BANNER_PROPERTIES']['URL'];
    if ($url) {
        echo <<<BANER
   <a href="{$url}"><div class="top-banner" style="background-image: url('{$src}')"></div></a> 
BANER;
    } else {
        echo <<<BANER
  <div class="top-banner" style="background-image: url('{$src}')"></div>
BANER;
    }
} else {
    echo $arResult["BANNER"];
}
$frame->end();
