<?php
namespace Page;

class Catalog
{
    // include url of current page
    public static $URL = '/catalog/';
    const CATEGORIES = '//div[@class="Category__items-block"]';
    const HIT_PRODUCTS = '//div[contains(@class,"SubgridScrollable__item")]';
    const PRODUCT_TITLE = '//div[@class="ProductCardVertical__name"]';

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
     * Переход в карточку товара из каталога.
     *
     * @param int $productNum Номер товара в строке подкатегории по порядку
     * @return string $productName
     * @throws \Exception
     */
    public function goToProductFromCatalog($productNum = null)
    {
        $I = $this->tester;

        $categoriesCount = $I->getNumberOfElements(self::CATEGORIES);
        $catNum = mt_rand(1, $categoriesCount);
        $itemsCount = $I->getNumberOfElements(self::CATEGORIES.'['.$catNum.']'.self::HIT_PRODUCTS,
            'hit products in category');
        codecept_debug($itemsCount);
        if($productNum == null || $productNum > $itemsCount){
            if ($itemsCount > 4){
                $itemsCount = 4;
            }

            $productNum = mt_rand(1, $itemsCount);
        }

        $I->lookForwardTo("go to product " . $productNum . " from listing page");
        $I->scrollTo(self::CATEGORIES.'['.$catNum.']'.self::HIT_PRODUCTS . '['. $productNum .']', 0, -100);
        $productName = $I->grabTextFrom(self::CATEGORIES.'['.$catNum.']'.self::HIT_PRODUCTS . '[' . $productNum . ']' . self::PRODUCT_TITLE);
        codecept_debug('Product name: '.$productName);
        $I->waitAndClick(self::CATEGORIES.'['.$catNum.']'.self::HIT_PRODUCTS . '[' . $productNum . ']' . self::PRODUCT_TITLE,
            'go to product ' . $productNum . ' page', true);
        $I->wait(SHORT_WAIT_TIME);

        return $productName;
    }

    /**
     * Проверка страницы Каталог товаров.
     *
     * @throws \Exception Если наименование заголовока не соответствует "Каталог товаров"
     */
    public function checkCatalogPage()
    {
        $I = $this->tester;

        $I->lookForwardTo('check catalog page');
        $name = $I->grabTextFrom('//div[@class="CategoryGrid"]//h1');
        codecept_debug($name .' - Catalog page name');
        if($name != "Каталог товаров"){
            Throw new \Exception('Wrong catalog page name - Grab name - '. $name);
        }
    }

    /**
     * Проверка наличия иконок на странице каталога уцененных товаров.
     *
     * @throws \Exception
     */
    public function checkIconsInDiscountedCatalogPage()
    {
        $I = $this->tester;

        $categoriesCount = $I->getNumberOfElements(['class' => 'CatalogLayout__category-title']);
        $iconsCount = $I->getNumberOfElements('//span[contains(@class,"CatalogLayout__category-icon")]');
        if($categoriesCount != $iconsCount){
            Throw new \Exception('no match for icons '.$iconsCount.' and categories '. $categoriesCount);
        }
    }
}
