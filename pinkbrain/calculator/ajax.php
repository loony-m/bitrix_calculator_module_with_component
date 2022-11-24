<?
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Context;

$request = Context::getCurrent()->getRequest();


if($request['AJAX'] == 'Y'){
    $APPLICATION->IncludeComponent(
        "pinkbrain:calculator",
        "",
        [
            'AJAX' => 'Y',
            'AJAX_DATA' => $request['FIELDS'],
        ]
    );
}