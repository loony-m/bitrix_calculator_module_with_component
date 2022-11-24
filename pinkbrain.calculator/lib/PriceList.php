<?

namespace Pinkbrain\Calculator;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\Elements\ElementPricelistTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Data\Cache;

class PriceList
{
    const IBLOCK_CODE = 'calc_pricelist';

    public function getIblockId()
    {
        $iblockID = IblockTable::getList(
            [
                'select' => ['ID'],
                'filter' => ['=CODE' => self::IBLOCK_CODE],
                'cache' => ['ttl' => 86400]
            ]
        )->fetch()['ID'];

        return $iblockID;
    }

    public function getList()
    {
        $iblockID = self::getIblockId();

        $arResult = [];
        $sectionData = [];

        $cache = Cache::createInstance();
        $cacheDir = 'calculator_element';
        $cacheKey = 'calculator_element';

        if ($cache->initCache(86400, $cacheKey, $cacheDir)) {
            $arResult = $cache->getVars();
        } elseif ($cache->startDataCache()) {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cacheDir);


            $arSection = SectionTable::getList([
                'select' => ['ID', 'NAME', 'IBLOCK_SECTION_ID'],
                'filter' => ['IBLOCK_ID' => $iblockID, 'ACTIVE' => 'Y'],
            ])->FetchAll();

            // для идентификации - будем получать по id категории ее данные
            foreach ($arSection as $section) {
                $sectionData[$section['ID']] = $section;
            }

            // собираем итоговую структуру
            $arElement = ElementPricelistTable::getList([
                'select' => ['NAME', 'COUNT_RACK_' => 'COUNT_RACK', 'RACK_WEIGHT_' => 'RACK_WEIGHT', 'TOTAL_PRICE_' => 'TOTAL_PRICE', 'IBLOCK_SECTION_ID'],
                'filter' => ['IBLOCK_ID' => $iblockID, 'ACTIVE' => 'Y'],
            ])->FetchAll();

            foreach ($arElement as $element) {
                $lengthSection = $sectionData[$element['IBLOCK_SECTION_ID']];

                $register = $sectionData[$lengthSection['IBLOCK_SECTION_ID']]['NAME'];
                $length = $lengthSection['NAME'];

                $arResult[$register][$length][$element['NAME']] = [
                    'COUNT_RACK' => $element['COUNT_RACK_VALUE'],
                    'RACK_WEIGHT' => $element['RACK_WEIGHT_VALUE'],
                    'TOTAL_PRICE' => $element['TOTAL_PRICE_VALUE'],
                ];
            }

            $CACHE_MANAGER->RegisterTag('iblock_id_'.$iblockID);
            $CACHE_MANAGER->EndTagCache();
            $cache->endDataCache($arResult);
        }

        return $arResult;
    }

    public function parse()
    {
        $filePath = $_SERVER['DOCUMENT_ROOT'].'/upload/pricelist_1.xlsx';

        if(!file_exists($filePath)){
            return "Отсутствует прайс лист в папке upload";
        }

        $reader = IOFactory::createReader('Xlsx');
        $spreadsheet = $reader->load($filePath);
        $limitColumn = 13;
        $arResult = [];

        // Только чтение данных
        $reader->setReadDataOnly(true);

        // Данные в виде массива
        $arData = $spreadsheet->getActiveSheet()->toArray();

        // заполненные строки
        $highestRowColumn = $spreadsheet->getActiveSheet()->getHighestRowAndColumn();
        $countActiveRow = $highestRowColumn['row'];

        // оставляем строки с данными
        foreach ($arData as $key => $row):
            if($key >= $countActiveRow){
                unset($arData[$key]);
                continue;
            }

            foreach ($row as $number => $cell) {
                if($number >= $limitColumn){
                    unset($arData[$key][$number]);
                }
            }
        endforeach;

        // заголовки не нужны
        unset($arData[0]);

        // получим нужный формат данных
        foreach ($arData as $rowCells) {
            if(!empty($rowCells[0])){
                $register = $rowCells[0];
            }

            if(!empty($rowCells[1])){
                $length = $rowCells[1];
            }

            $arResult[$register][$length][] = [
                'COUNT_SECTION' => $rowCells[2],
                'COUNT_RACK' => $rowCells[3],
                'RACK_WEIGHT' => $rowCells[5],
                'TOTAL_PRICE' => $rowCells[12],
            ];
        }

        return $arResult;
    }

    private function clear($iblockID)
    {
        $arElement = ElementTable::getList([
            'select' => ['ID'],
            'filter' => ['IBLOCK_ID' => $iblockID],
        ])->FetchAll();

        foreach ($arElement as $element) {
            \CIBlockElement::Delete($element['ID']);
        }

        $arSection = SectionTable::getList([
            'select' => ['ID'],
            'filter' => ['IBLOCK_ID' => $iblockID],
        ])->FetchAll();

        foreach ($arSection as $section) {
            \CIBlockSection::Delete($section['ID']);
        }
    }

    public function add($arData)
    {
        $iblockID = self::getIblockId();

        $arResult = [];

        if(!empty($iblockID)) {

            self::clear($iblockID);

            $section = new \CIBlockSection;
            $element = new \CIBlockElement;

            $arFieldsSection = [
                'ACTIVE' => 'Y',
                'IBLOCK_ID' => $iblockID,
            ];

            // регистры это раздел 1 уровня
            foreach ($arData as $register => $arLength) {
                $arFieldsSection['NAME'] = $register;
                $arFieldsSection['IBLOCK_SECTION_ID'] = false;

                $registerSectionID = $section->Add($arFieldsSection);

                // длины это раздел 2 уровня
                foreach ($arLength as $length => $items) {
                    $arFieldsSection['NAME'] = $length;
                    $arFieldsSection['IBLOCK_SECTION_ID'] = $registerSectionID;

                    $lengthSectionID = $section->Add($arFieldsSection);

                    // количество секция это элемент
                    foreach ($items as $item) {
                        $arFieldsElement = [
                            'ACTIVE' => 'Y',
                            'IBLOCK_ID' => $iblockID,
                            'NAME' => $item['COUNT_SECTION'],
                            'IBLOCK_SECTION_ID' => $lengthSectionID,
                            'PROPERTY_VALUES' => [
                                'COUNT_RACK' => $item['COUNT_RACK'],
                                'RACK_WEIGHT' => $item['RACK_WEIGHT'],
                                'TOTAL_PRICE' => $item['TOTAL_PRICE'],
                            ]
                        ];

                        $element->Add($arFieldsElement);
                    }
                }
            }
        }

        return $arResult;
    }

    public function run()
    {
        $data = self::parse();
        $result = self::add($data);

        return $result;
    }
}