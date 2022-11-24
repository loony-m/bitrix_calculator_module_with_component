<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

$request = HttpApplication::getInstance()->getContext()->getRequest();
$module_id = htmlspecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);
Loader::includeModule($module_id);

$aTabs = [
    [
        "DIV" => "edit",
        "TAB" => Loc::getMessage("OPTIONS_TAB_NAME"),
        "TITLE" => Loc::getMessage("OPTIONS_TAB_NAME"),
        "OPTIONS" => [
            ["PRICE_PIPE", Loc::getMessage("OPTIONS_TAB_PRICE_PIPE"), "", ["text", 50]],
            ["DIAMETER_1", Loc::getMessage("OPTIONS_TAB_DIAMETER_1"), "", ["text", 50]],
            ["DIAMETER_2", Loc::getMessage("OPTIONS_TAB_DIAMETER_2"), "", ["text", 50]],
            ["DIAMETER_3", Loc::getMessage("OPTIONS_TAB_DIAMETER_3"), "", ["text", 50]],
            ["PRICE_DU25", Loc::getMessage("OPTIONS_TAB_PRICE_DU25"), "", ["text", 50]],
            ["RACK_MATERIAL_PRICE", Loc::getMessage("OPTIONS_TAB_RACK_MATERIAL_PRICE"), "", ["text", 50]],
            ["RACK_WORK_PRICE", Loc::getMessage("OPTIONS_TAB_RACK_WORK_PRICE"), "", ["text", 50]],
            ["RACK_L_VARIABLE", Loc::getMessage("OPTIONS_TAB_RACK_L_VARIABLE"), "", ["text", 50]],
        ]
    ],
    [
        "DIV" => "withdrawal",
        "TAB" => Loc::getMessage("OPTIONS_TAB_WITHDRAWAL_NAME"),
        "TITLE" => Loc::getMessage("OPTIONS_TAB_WITHDRAWAL_NAME"),
        "OPTIONS" => [
            ["W_57", Loc::getMessage("OPTIONS_TAB_W_57"), "", ["text", 50]],
            ["W_76", Loc::getMessage("OPTIONS_TAB_W_76"), "", ["text", 50]],
            ["W_89", Loc::getMessage("OPTIONS_TAB_W_89"), "", ["text", 50]],
            ["W_108", Loc::getMessage("OPTIONS_TAB_W_108"), "", ["text", 50]],
            ["W_114", Loc::getMessage("OPTIONS_TAB_W_114"), "", ["text", 50]],
            ["W_133", Loc::getMessage("OPTIONS_TAB_W_133"), "", ["text", 50]],
            ["W_159", Loc::getMessage("OPTIONS_TAB_W_159"), "", ["text", 50]],
            ["W_219", Loc::getMessage("OPTIONS_TAB_W_219"), "", ["text", 50]],
        ]
    ],
    [
        "DIV" => "bottom",
        "TAB" => Loc::getMessage("OPTIONS_TAB_BOTTOM_NAME"),
        "TITLE" => Loc::getMessage("OPTIONS_TAB_BOTTOM_NAME"),
        "OPTIONS" => [
            ["B_57", Loc::getMessage("OPTIONS_TAB_B_57"), "", ["text", 50]],
            ["B_76", Loc::getMessage("OPTIONS_TAB_B_76"), "", ["text", 50]],
            ["B_89", Loc::getMessage("OPTIONS_TAB_B_89"), "", ["text", 50]],
            ["B_108", Loc::getMessage("OPTIONS_TAB_B_108"), "", ["text", 50]],
            ["B_114", Loc::getMessage("OPTIONS_TAB_B_114"), "", ["text", 50]],
            ["B_133", Loc::getMessage("OPTIONS_TAB_B_133"), "", ["text", 50]],
            ["B_159", Loc::getMessage("OPTIONS_TAB_B_159"), "", ["text", 50]],
            ["B_219", Loc::getMessage("OPTIONS_TAB_B_219"), "", ["text", 50]],
        ]
    ]
];

if ($request->isPost() && check_bitrix_sessid()) {
    foreach ($aTabs as $aTab) {
        foreach ($aTab["OPTIONS"] as $arOption) {
            if (!is_array($arOption)) {
                continue;
            }
            if ($arOption["note"]) {
                continue;
            }
            if ($request["apply"]) {
                $optionValue = $request->getPost($arOption[0]);
                if ($arOption[0] == "hide_news") {
                    if ($optionValue == "") {
                        $optionValue = "N";
                    }
                }
                Option::set($module_id, $arOption[0],
                    is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
            } elseif ($request["default"]) {
                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }
    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . $module_id . "&lang=" . LANG);
}

$tabControl = new CAdminTabControl(
    "tabControl",
    $aTabs
);

$tabControl->Begin();
?>

<form action="<? echo($APPLICATION->GetCurPage()); ?>?mid=<? echo($module_id); ?>&lang=<? echo(LANG); ?>" method="post">
    <?
    foreach ($aTabs as $aTab) {
        if ($aTab["OPTIONS"]) {
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
        }
    }
    $tabControl->Buttons();
    ?>
    <input type="submit" name="apply" value="<? echo(Loc::GetMessage("OPTIONS_INPUT_APPLY")); ?>" class="adm-btn-save"/>
    <input type="submit" name="default" value="<? echo(Loc::GetMessage("OPTIONS_INPUT_DEFAULT")); ?>"/>
    <? echo(bitrix_sessid_post()); ?>
</form>

<?php
$tabControl->End();