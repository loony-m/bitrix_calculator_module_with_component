<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Mail\Event;

class PinkbrainCalculator extends CBitrixComponent {

    private const IBLOCK_CODE = 'calc_order';
    private const EVENT_TYPE = 'PINKBRAIN_CALCULATOR_ORDER';
    private $iblockId;

    private function _checkModules() {
        if (!Loader::includeModule('iblock')) {
            $this->arResult['ERROR'][] = Loc::getMessage("ERROR_MODULE");
        }

        if (!Loader::includeModule('pinkbrain.calculator')) {
            $this->arResult['ERROR'][] = Loc::getMessage("ERROR_MODULE_PINKBRAIN");
        }

        return true;
    }

    private function _checkIblock()
    {
        $this->$iblockId = IblockTable::getList(
            [
                'select' => ['ID'],
                'filter' => ['=CODE' => self::IBLOCK_CODE],
                'cache' => ['ttl' => 86400]
            ]
        )->fetch()['ID'];

        if(empty($this->$iblockId)){
            $this->arResult['ERROR'][] = Loc::getMessage("ERROR_IBLOCK_AVAILABLE", ['#IBLOCK_CODE#' => self::IBLOCK_CODE]);
        }
    }

    private function _checkEvent()
    {
        $event = new CEventMessage();
        $dbMessage = $event->GetList($by='id', $order='desc', ['TYPE_ID' => self::EVENT_TYPE]);
        if(!$arMessage = $dbMessage->Fetch()){
            $this->arResult['ERROR'][] = Loc::getMessage("ERROR_EVENT_AVAILABLE", ['#EVENT_TYPE#' => self::EVENT_TYPE]);
        }
    }

    public function saveOrder($data)
    {
        $el = new CIBlockElement;

        $orderStr = '';
        $orderStr .= 'Тип заглушек - '.$data['PLUG']."\n";
        $orderStr .= 'Тип конструкции - '.$data['CONSTRUCTION']."\n";
        $orderStr .= 'Диаметр трубы, mm - '.$data['DIAMETER']."\n";
        $orderStr .= 'Толщина стенки, mm - '.$data['WALL_THICKNESS']."\n";
        $orderStr .= 'Длина секции, mm - '.$data['LENGTH']."\n";
        $orderStr .= 'Количество секций - '.$data['QUANTITY_SECTIONS']."\n";
        $orderStr .= 'Кронштейны - '.$data['BRACKETS']."\n";
        $orderStr .= 'Стойки - '.$data['RACK']."\n";
        $orderStr .= 'Количество, шт - '.$data['QUANTITY']."\n";
        $orderStr .= 'Цена - '.$data['PRICE']."\n";
        $orderStr .= 'Имя - '.$data['USER_NAME']."\n";
        $orderStr .= 'Email - '.$data['USER_EMAIL']."\n";
        $orderStr .= 'Phone - '.$data['USER_PHONE']."\n";

        $arField = [
            "IBLOCK_ID" => $this->$iblockId,
            'IBLOCK_SECTION_ID' => false,
            "NAME" => $data['TITLE'],
            "ACTIVE" => "Y",
            "DETAIL_TEXT" => $orderStr,
        ];

        Event::send([
            "EVENT_NAME" => self::EVENT_TYPE,
            "LID" => SITE_ID,
            "C_FIELDS" => [
                "MESSAGE" => $orderStr
            ]
        ]);

        if($el->Add($arField)) {
            return json_encode(['success' => true, 'message' => Loc::getMessage("SUCCESS")]);
        } else {
            return json_encode(['success' => false, 'message' => $el->LAST_ERROR]);
        }
    }

    public function executeComponent() {
        $this->_checkModules();
        $this->_checkIblock();
        $this->_checkEvent();

        if($this->arParams['AJAX'] == 'Y' && !empty($this->arParams['AJAX_DATA'])){
            echo self::saveOrder($this->arParams['AJAX_DATA']);
        }else{
            $this->includeComponentTemplate();
        }
    }
}