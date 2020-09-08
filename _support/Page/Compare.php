<?php
namespace Page;

class Compare
{
    // include url of current page
    public static $URL = '';
    const PRODUCT_PAGE_IMG = 'image_container';
    const COMPARE_PRODUCT_NAME = '//div[contains(@class,"Compare__product-cell_name")]';// путь к наименованию товару в списке сравнения
    const COMPARE_PRODUCT_PRICE = '//div[contains(@class,"Compare__product-price-render")]/div[2]//div[contains(@class,"Compare__product-cell")]';// путь к блоку цены товара в списке сравнения

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
     * Добавить в корзину из сравнения.
     *
     * @param int $number Номер элемента на странице сравнения
     * @throws \Exception
     */
    public function addToCartFromCompare($number) {
        $I = $this->tester;

        $I->lookForwardTo("**** add to cart product " . $number . " from compare page");

        $I->waitAndClick(self::COMPARE_PRODUCT_PRICE.'[' . $number . ']//button[contains(.,"корзину")]',
            'add to cart button for product ' . $number, true);
        $I->wait(SHORT_WAIT_TIME);
        $I->continueShopping();
    }

    /**
     * Переход в карточку товара.
     *
     * @param string $imgNameLink Дополнение Xpath
     * @throws \Exception
     */
    public function goToProduct($imgNameLink)
    {
        $I = $this->tester;

        $I->lookForwardTo('go to product page');
        $I->checkElementOnPage(self::COMPARE_PRODUCT_NAME, 'comparing items');
        $number = $I->getNumberOfElements(self::COMPARE_PRODUCT_NAME, 'number of elements in compare');
        $numberRnd = mt_rand(1, $number);
        $I->waitAndClick(self::COMPARE_PRODUCT_NAME.'['. $numberRnd .']/a', 'product link', true);
    }

    /**
     * Получение массива данных о товарах.
     *
     * @return array $itemsData
     */
    public function getItemsData()
    {
        $I = $this->tester;

        $itemsData = ['id' => array(), 'url' => array()];
        $itemsCount = $I->getNumberOfElements(self::COMPARE_PRODUCT_NAME);
        for ($i = 1; $i <= $itemsCount; $i++) {
            $itemId = $I->grabAttributeFrom(self::COMPARE_PRODUCT_PRICE.'['. $i .']//button[contains(.,"корзину")]', 'id');
            array_push($itemsData['id'], $itemId);
            $itemUrl = $I->grabAttributeFrom(self::COMPARE_PRODUCT_NAME.'['. $i .']/a', 'href');
            array_push($itemsData['url'], $itemUrl);
        }

        return $itemsData;
    }

    /**
     * Проверка наличия добавленных товаров.
     *
     * @param array $itemsId
     * @throws \Exception
     */
    public function checkAddedItemsIsVisible($itemsId)
    {
        $I = $this->tester;

        $itemsData = $this->getItemsData();
        $I->seeValuesAreEquals(arsort($itemsData['id']), arsort($itemsId));
    }

    /**
     * Проверка что список пуст.
     *
     * @throws \Exception
     */
    public function checkCompareListIsEmpty()
    {
        $I = $this->tester;

        $I->reloadPage();
        $I->waitForElementVisible(['class' => 'Compare__empty-content']);
    }
}
