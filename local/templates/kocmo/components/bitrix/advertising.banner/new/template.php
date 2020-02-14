<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$frame = $this->createFrame()->begin("");
if ($img = $arResult['BANNER_PROPERTIES']['IMAGE_ID']) {
    $src = CFile::GetPath($img);
    $url = $arResult['BANNER_PROPERTIES']['URL'];
    $rsFile = CFile::GetByID($img);
    $arFile = $rsFile->Fetch();
    if ($url) {
        echo <<<BANER
 <a href="{$url}">
<div class="category-banner " style="height: {$arFile['HEIGHT']}px"><img  src="{$src}" alt=""></div>
     </a>
BANER;
    } else {
        echo <<<BANER
  <div class="category-banner " style="height: {$arFile['HEIGHT']}px"><img src="{$src}" alt=""></div>
BANER;
    }
} else {
    echo $arResult["BANNER"];
}
$frame->end();
