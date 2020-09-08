<?php
namespace Page;

class Supplies
{
    // include url of current page
    public static $URL = '/supplies/';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL.$param;
    }

    /**
     * @var AcceptanceTester
     */
    protected $tester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->tester = $I;
    }
    
    /**
     * Выбор подкатегории "Подбери расходку", выбор конкретной модели из выпадающих списков, получение списка товаров.
     *
     * @return int $suppliesCount количество расходников
     * @throws \Exception
     */
    public function findSupplies() 
    {
        $I = $this->tester;

        $steps = ['brandsSelect', 'typesSelect', 'modelsSelect'];
        $value = '';
        $repeat = 0;
        $valuesCount = 0;
        do {
            foreach ($steps as $step) {
                $I->lookForwardTo('select random ' . $step);
                $I->wait(MIDDLE_WAIT_TIME);
                $I->waitAndClick('//select[@id="' . $step . '"]/..//div[contains(@class,"Select__selectable")]',
                    'open '.$step.' list');
                $stepXpath = '//select[@id="' . $step . '"]/..//div[contains(@class,"Select__items")]/div';
                $I->wait(SHORT_WAIT_TIME);
                $valuesCount = $I->getNumberOfElements($stepXpath);
                if ($valuesCount !== 0) {
                    $array = $I->grabMultiple($stepXpath);
                    $key = array_search('NONAME', $array);
                    unset($array[$key]);
                    codecept_debug($array);
                    $rndSelect = array_rand($array, 1);
                    $value = $array[$rndSelect];
                    codecept_debug('Selected brand - ' . $value);
                    $I->waitAndClick($stepXpath . '[contains(.,"' . $value . '")]',
                        'select ' . $step);
                }
            }

            $repeat++;
        }while($repeat <= 5 && $valuesCount == 0);

        $I->waitAndClick('//button[contains(.,"Найти")]', 'find supply');
        $I->waitForElementVisible(['class' => 'Supplies__header']);
        $header = $I->grabTextFrom(['class' => 'Supplies__header']);
        if (!strpos($header, $value)){
            Throw new \Exception('Wrong model');
        }

        $I->waitForElementVisible(Home::XPATH_PRODUCT_CARD);
        $suppliesCount = $I->getNumberOfElements(Home::XPATH_PRODUCT_CARD);

        return $suppliesCount;
    }

    /**
     * Проверка отсутствия блока подборки расходных материалов в листинге определенной категории или товаре.
     *
     * @param string $categoryOrProduct Наименование проверяемой категории или товара
     * @throws \Exception Если отображается блок подборки расходных материалов
     */
    public function checkSupplies($categoryOrProduct)
    {
        $I = $this->tester;

        $I->lookForwardTo('check supplies block');
        $selectedCategoryNameView = $I->translit($categoryOrProduct);
        try {
            $I->dontSeeElement(['xpath' => '//button[@class="pretty_button type1 find_supplies"]']);
        }catch(\Exception $ex) {
            Throw new \Exception('Supplies block is visible at category\product - '.$selectedCategoryNameView);
        }
    }
}
