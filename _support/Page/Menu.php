<?php
namespace Page;

class Menu
{
    // include url of current page
    public static $URL = '';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    const CATALOG = ['class' => 'MainHeader__catalog']; //каталог

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
     * Выбор из основного меню категорий.
     *
     * @param int $categoryNumber номер категории по порядку
     * @throws \Exception
     */
    public function selectMainCategory($categoryNumber)
    {
        $I = $this->tester;

        $I->lookForwardTo("**** click to main menu category");
        $I->waitAndClick(self::CATALOG, 'open catalog menu');
        $I->waitForElementVisible(Listing::CATEGORY_LIST);
        $categoryName = $I->grabAttributeFrom(Listing::CATEGORY_LIST.'[' . $categoryNumber . ']/a', 'data-title');
        codecept_debug($categoryName);
        $I->waitAndClick(Listing::CATEGORY_LIST.'[' . $categoryNumber . ']/a', 'main menu category ' . $categoryNumber);
        $I->waitForElementVisible('//h1[contains(.,"'. $categoryName .'")]');
    }

    /**
     * Раскрытие меню.
     *
     * @param int $categoryNumber номер категории по порядку
     * @throws \Exception
     */
    public function openMenuCategory($categoryNumber)
    {
        $I = $this->tester;

        $I->lookForwardTo("**** click to main menu category");
        $I->waitAndClick(self::CATALOG, 'open catalog menu');
        $I->waitForElementVisible(Listing::CATEGORY_LIST);
        $I->moveMouseOver(Listing::CATEGORY_LIST.'[' . $categoryNumber . ']');
        $I->waitForElementVisible(Listing::CATEGORY_LIST.'[' . $categoryNumber . ']//span[contains(@class,"CatalogMenu__category-item_selected")]');
    }

    /**
     * Выбор категории "Уцененные товары".
     *
     * @throws \Exception
     */
    public function selectDiscountMenu()
    {
        $I = $this->tester;

        $I->lookForwardTo("**** click to discount blue menu category");
        $I->waitAndClick(self::CATALOG, 'open catalog menu');
        $I->waitAndClick('//div[@class="CatalogMenu__category-bottom-block"]//a[contains(.,"Уцененные товары")]', 'discount category');
    }

    /**
     * Выбор категории "Сервисы и Услуги".
     *
     * @param string $serviceName название страницы сервиса\услуги
     * @throws \Exception
     */
    public function selectServiceMenu($serviceName)
    {
        $I = $this->tester;

        $I->lookForwardTo("**** click to service menu");
        $I->waitAndClick(self::CATALOG, 'open catalog menu');
        $I->waitForElementVisible('//div[@class="CatalogMenu__category-bottom-block"]//span[contains(.,"Сервисы и услуги")]');
        $I->moveMouseOver('//div[@class="CatalogMenu__category-bottom-block"]//span[contains(.,"Сервисы и услуги")]');
        $I->waitForElementVisible(Listing::SUBCATEGORY_LIST);
        if ($serviceName != null){
            $I->waitAndClick(Listing::SUBCATEGORY_LIST.'//a[contains(.,"'.$serviceName.'")]',
                'go to subcategory '.$serviceName);
        }
    }

    /**
     * Переход на страницу Обратной связи из шапки.
     *
     * @throws \Exception
     */
    public function selectFeedbackMenu()
    {
        $I = $this->tester;

        $I->waitAndClick('//a[contains(.,"Обратная связь")]', 'feedback link');
        $I->waitForElementVisible('//div[@class="FeedbackInPopupView js--FeedbackInPopupView"]');
    }
}
