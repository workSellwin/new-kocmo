<?php
$arResult['PROPERTIES']=array_column($arResult['PROPERTIES'],'~VALUE','CODE');

$resImage = CFile::ResizeImageGet(
    $arResult['PREVIEW_PICTURE'],
    array(
        'width'=>720,
        'height'=>486
    )
);

$arResult['PREVIEW_PICTURE']['SRC'] = $resImage['src'];
