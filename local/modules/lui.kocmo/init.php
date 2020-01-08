<?php

//\Lui\Kocmo\Handler\SetHandler::SetModuleHandler();

AddEventHandler("main", "OnBeforeUserRegister", ['Lui\Kocmo\Handler', 'OnBeforeUserRegister']);

AddEventHandler("sale", "OnOrderNewSendEmail", ['Lui\Kocmo\Handler', 'OnOrderNewSendEmail']);

AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array('Lui\Kocmo\Handler', "OnBeforeIBlockElementUpdateHandler"));
