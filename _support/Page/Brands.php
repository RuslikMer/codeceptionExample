<?php
namespace Page;

class Brands
{
    // include url of current page
    public static $URL = '/brands/';

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
     * Добавление товара в корзину из раздела "Бренды". Выбор раздела бренды.
     * Далее случайный выбор из алфавитной панели, затем случайный выбор бренда из алфавитного раздела.
     * Выбор случайного товара из первого ротатора.
     *
     * @throws \Exception
     */
    public function addToCartFromBrands()
    {
        $I = $this->tester;

        $I->goToBrands();
        $lettersCount = $I->getNumberOfElements('//div[@class="BrandBook__row"]', 'letters list');
        $letterNumber = mt_rand(1, $lettersCount);
        $brandsCount = $I->getNumberOfElements('//div[@class="BrandBook__row"][' . $letterNumber . ']//ul[@class="BrandBook__row-list"]/li', 'number of elements in letters block');
        $brandNumber = mt_rand(1, $brandsCount);
        $I->waitAndClick('//div[@class="BrandBook__row"][' . $letterNumber . ']//ul[@class="BrandBook__row-list"]/li[' . $brandNumber . ']/a', 'brand element ' . $brandNumber . ' in letters block');
        $I->wait(SHORT_WAIT_TIME);
        $categoriesCount = $I->getNumberOfElements('//div[@class="BrandCategories__brand-category-item"]', 'categories');
        $categoryNumber = mt_rand(1, $categoriesCount);
        $productsCount = $I->getNumberOfElements('//div[@class="BrandCategories__brand-category-item"][' . $categoryNumber . ']'. Home::XPATH_PRODUCT_CARD, 'number of elements in category');
        $productNumber = mt_rand(1, $productsCount);
        $I->waitAndClick('//div[@class="BrandCategories__brand-category-item"][' . $categoryNumber . ']'. Home::XPATH_PRODUCT_CARD . '[' . $productNumber . ']//label[contains(@class,"ProductCardVertical__button-buy")]',
            'add product to cart');
    }

    /**
     * Проверка категорий бренда.
     *
     * @throws \Exception
     */
    public function checkBrandCategories()
    {
        $I = $this->tester;

        $I->goToBrands();
        $lettersCount = $I->getNumberOfElements('//div[@class="BrandBook__row"]', 'letters list');
        $letterNumber = mt_rand(1, $lettersCount);
        $brandsCount = $I->getNumberOfElements('//div[@class="BrandBook__row"][' . $letterNumber . ']//ul[@class="BrandBook__row-list"]/li', 'number of elements in letters block');
        $brandNumber = mt_rand(1, $brandsCount);
        $I->waitAndClick('//div[@class="BrandBook__row"][' . $letterNumber . ']//ul[@class="BrandBook__row-list"]/li[' . $brandNumber . ']/a', 'brand element ' . $brandNumber . ' in letters block');
        $I->wait(SHORT_WAIT_TIME);
        $categoriesCount = $I->getNumberOfElements('//div[@class="BrandCategories__brand-category-item"]', 'categories');
        $categoryNumber = mt_rand(1, $categoriesCount);
        for ($i = 1; $i <= $categoriesCount; $i++) {
            $I->waitAndClick('//div[@class="BrandCategories__brand-category-item"][' . $categoryNumber . ']//h2//a', 'go to brand page');
            $I->checkMandatoryElements();
            $I->moveBack();
        };
    }

    /**
     * Проверка названия страницы бренда.
     *
     * @param string $expectedName ожидаемое название бренда
     * @throws \Exception
     */
    public function checkBrandName($expectedName)
    {
        $I = $this->tester;

        $I->waitForElementVisible('//h1[@class="Heading Heading_level_1 BrandCategories__title"]');
        $brandName = $I->grabTextFrom('//h1[@class="Heading Heading_level_1 BrandCategories__title"]');
        $I->seeValuesAreEquals($expectedName,$brandName);
    }
}
