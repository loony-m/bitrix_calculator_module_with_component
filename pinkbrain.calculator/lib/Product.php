<?

namespace Pinkbrain\Calculator;

use Bitrix\Main\Config\Option;
use Bitrix\Catalog\PriceTable;
use Bitrix\Main\Data\Cache;

class Product
{
    public function getPrice()
    {

        $arResult = [];
        $module_id = pathinfo(dirname(__DIR__))["basename"];

        $cache = Cache::createInstance();
        $cacheDir = 'calculator_product_prices';
        $cacheKey = 'calculator_product_prices';

        if ($cache->initCache(86400, $cacheKey, $cacheDir)) {
            $arResult = $cache->getVars();
        } elseif ($cache->startDataCache()) {

            $arOption = [
                'W_57',
                'W_76',
                'W_89',
                'W_108',
                'W_114',
                'W_133',
                'W_159',
                'W_219',
                'B_57',
                'B_76',
                'B_89',
                'B_108',
                'B_114',
                'B_133',
                'B_159',
                'B_219',
            ];

            foreach ($arOption as $option) {
                $arResult['OPTION'][$option] = Option::get($module_id, $option);
                $arResult['COMPARISON'][$arResult['OPTION'][$option]] = $option;
            }

            foreach ($arResult['OPTION'] as $option) {
                if(!empty($option)){
                    $arResult['PRODUCT_ID'][] = $option;
                }
            }

           $arPrice = PriceTable::getList([
               'select' => ['*'],
               'filter' => ['PRODUCT_ID' => $arResult['PRODUCT_ID']],
           ])->FetchAll();

            foreach($arPrice as $price) {
                $arResult['PRICES'][$arResult['COMPARISON'][$price['PRODUCT_ID']]] = round($price['PRICE']);
            }

            $cache->endDataCache($arResult);
        }

        return $arResult['PRICES'];
    }
}