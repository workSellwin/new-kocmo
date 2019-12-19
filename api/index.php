<?php
use Bitrix\Main\Application;
use Bitrix\Main\Loader;

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$request = Application::getInstance()->getContext()->getRequest();
$requestDirectory = $request->getRequestedPageDirectory();
$action = explode('/',$requestDirectory)[2];
$import = null;

$arResponse = ['status' => 'ok', 'error' => [],];

if (!$json = json_decode($request->get("param"), true)){
    $arResponse['error'][] = 'No Json!';
}

if (!$login = (string)trim($request->get("login"))) {
    $arResponse['error'][] = 'No login!';
}

if (!$password = (string)trim($request->get("password"))) {
    $arResponse['error'][] = 'No password!';
}

if (!$action) {
    $arResponse['error'][] = 'No action!';
}
else{
    Loader::includeModule('kocmo.exchange');

    $stage = Kocmo\Exchange\StaticFactory::getActionStage($action);

    if($stage !== false){
        $import = Kocmo\Exchange\StaticFactory::factory($stage);
    }
    else{
        $arResponse['error'][] = 'un correct url';
    }
}

if (empty($arResponse['error'])) {

    $USER = new \CUser;
    $arAuthResult = $USER->Login($login, $password, "N");
    $APPLICATION->arAuthResult = $arAuthResult;

    if (!$USER->IsAuthorized()) {
        $arResponse['error'][] = 'Authorization failed';
    } else {
        $flag = $import->update($json);

        $arResponse['error'] = array_merge($arResponse['error'], $import->getErrors());

//                pr($arResponse['error'], 50);
//        die();

//        if( !$import->update($json) ){
//            $arResponse['error'][] = 'un correct param';
//        }
       $USER->Logout();
    }
}

if ($arResponse['error']) {
    if($import) {
        $import->addLogErrorsArray($arResponse['error']);
    }
    $arResponse['status'] = 'no';
}
else{
    if($import) {
        $import->addLogInfo($request->getRequestUri());
        $import->addLogInfoArray($arResponse);
    }
}
header('Content-type: application/json');
echo json_encode($arResponse);