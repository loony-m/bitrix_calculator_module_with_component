<?

namespace Pinkbrain\Calculator\Controller;

use \Pinkbrain\Calculator\Control;
use \Pinkbrain\Calculator\PriceList;
use \Pinkbrain\Calculator\Product;
use \Bitrix\Main\Engine\ActionFilter\Authentication;


class Api extends \Bitrix\Main\Engine\Controller
{
    public function configureActions(): array
    {
        return [
            'getSettings' => [
                '-prefilters' => [
                   Authentication::class
                ],
            ],
        ];
    }

    public function getSettingsAction()
    {
        $settings['MODULE'] = Control::GetOption();
        $settings['PRICELIST'] = PriceList::getList();
        $settings['PRODUCT_PRICES'] = Product::getPrice();

        return $settings;
    }
}