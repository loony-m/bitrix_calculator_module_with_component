<?php

namespace Pinkbrain\Calculator;

use Bitrix\Main\Config\Option;

class Control
{
    public static function GetOption()
    {
        $settings = [];

        $module_id = pathinfo(dirname(__DIR__))["basename"];

        $settings["PRICE_PIPE"] = Option::get($module_id, "PRICE_PIPE");
        $settings["DIAMETER_1"] = Option::get($module_id, "DIAMETER_1");
        $settings["DIAMETER_2"] = Option::get($module_id, "DIAMETER_2");
        $settings["DIAMETER_3"] = Option::get($module_id, "DIAMETER_3");
        $settings["PRICE_DU25"] = Option::get($module_id, "PRICE_DU25");

        $settings["RACK_MATERIAL_PRICE"] = Option::get($module_id, "RACK_MATERIAL_PRICE");
        $settings["RACK_WORK_PRICE"] = Option::get($module_id, "RACK_WORK_PRICE");
        $settings["RACK_L_VARIABLE"] = Option::get($module_id, "RACK_L_VARIABLE");

        return $settings;
    }
}