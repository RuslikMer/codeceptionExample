<?php
namespace Page;

use Facebook\WebDriver\WebDriverBy;

class Search
{
    // include url of current page
    public static $URL = '';
    const PRODUCT_PAGE_NAME = 'instant-search-result-item__body-container instant-search-result-item__body-container_products';
    const PRODUCT_PAGE_IMG = 'instant-search-result-item__image-container instant-search-result-item__image-container_products';
    // блок с разделами каталога в поисковой выдаче
    const SEARCH_CATEGORY_CLASS = '//div[@class="SearchResults__categories"]';
    // товары в быстром поиске
    const FAST_SEARCH_PRODUCTS_XPATH = '//div[@class="SearchQuickResult instant-search-result js--SearchQuickResult"]//div[@class="SearchQuickResult__products"]';

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
     * Переход в категорию со страницы поиска.
     *
     * @param string $param Название категории
     * @throws \Exception
     */
    public function goToCategoryFromSearch($param)
    {
        $I = $this->tester;

        $I->lookForwardTo("choose category [" . $param . "] from search page");
        $I->waitAndClick(self::SEARCH_CATEGORY_CLASS . '//li[contains(.,"' . $param . '")]/a', 'category link', true);
    }

    /**
     * Проверка быстрого поиска.
     *
     * @param string $param Поисковый запрос
     */
    public function searchFastString($param)
    {
        $I = $this->tester;

        $I->amOnPage('/search/fast/?text=' . $param);
        $I->seeInPageSource('preg_search_text');
    }

    /**
     * Заполнение строки поиска и эмуляция нажатия Enter с клавиатуры.
     *
     * @param string $param Поисковый запрос
     * @throws \Exception
     */
    public function searchStringByEnter($param)
    {
        $I = $this->tester;

        $I->searchString($param);
        $I->lookForwardTo("press Enter");
        $I->pressKey('//div[@class="MainHeader__search"]//input[@placeholder]', \WebDriverKeys::ENTER);
        $I->waitForElementVisible('//div[contains(.,"'.$param.'")]');
    }

    /**
     * Проверка корректного исправления опечаток поискового запроса.
     *
     * @param string $param ожидаемое исправление
     * @throws \Exception
     */
    public function checkTypoCorrection($param)
    {
        $I = $this->tester;

        $I->wait(SHORT_WAIT_TIME);
        $actual = $I->grabTextFrom('//p[@class="SearchResults__corrected-text"]/a[1]');
        $I->seeValuesAreEquals($param, $actual);
    }

    /**
     * Заполнение строки поиска и переход на страницу "Показать все результаты"
     *
     * @param string $param Поисковый запрос
     * @throws \Exception
     */
    public function searchStringAllResults($param)
    {
        $I = $this->tester;

        $I->searchString($param);

        $I->lookForwardTo("go to All Results");
        $I->waitAndClick('//div[@class="MainHeader__search"]//a[contains(@class,"SearchQuickResult__show-more")]',
            'show all results link');
    }

    /**
     * Заполнение строки поиска.
     *
     * @param string $param Поисковый запрос
     * @throws \Exception
     */
    public function searchString($param)
    {
        $I = $this->tester;

        $I->lookForwardTo("enter string " . $param . " to search field");
        $I->waitAndFill('//div[@class="MainHeader__search"]//input[@placeholder]', 'search field', $param);
    }

    /**
     * Переход в карточку товара из быстрого поиска.
     *
     * @param string $imgNameLink Дополнение Xpath перехода через наименование или картинку
     * @throws \Exception
     */
    public function goToProductFromSearchFast($imgNameLink)
    {
        $I = $this->tester;

        $productsCount = $I->getNumberOfElements(self::FAST_SEARCH_PRODUCTS_XPATH, 'the products in fast search');
        codecept_debug($productsCount);
        $rndNum = mt_rand(1, $productsCount);
        $I->waitAndClick(self::FAST_SEARCH_PRODUCTS_XPATH . '/../../..//div[@tabindex]['.$rndNum.']', 'select product');
    }

    /**
     * Выбор категории товаров в поисковой выдаче.
     *
     * @throws \Exception
     */
    public function selectSearchCategory()
    {
        $I = $this->tester;

        $numCat = $I->getNumberOfElements(self::SEARCH_CATEGORY_CLASS . '//li', 'number of categories in column');
        $numCatRnd = mt_rand(1, $numCat);
        $labelCategory = $I->grabTextFrom(['xpath' => self::SEARCH_CATEGORY_CLASS . '//li['. $numCatRnd .']/a']);
        $labelCategory = $I->translit($labelCategory);
        $I->lookForwardTo("choose category [" . $labelCategory . "] from search page");
        $I->waitAndClick(self::SEARCH_CATEGORY_CLASS . '//li['. $numCatRnd .']/a', 'category link');
    }

    /**
     * Получение списка ай ди товаров.
     *
     * @return array $itemsId список айди товаров
     * @throws \Exception
     */
    public function getItemsIdFromSearch()
    {
        $I = $this->tester;

        $productCardXpath = '//div[contains(@class,"js--ProductCardInListing")]';
        $I->waitForElementVisible($productCardXpath);
        $itemsId = $I->grabMultiple($productCardXpath, 'data-product-id');
        $I->seeArrayNotEmpty($itemsId);

        return $itemsId;
    }

    /**
     * Проверка ссылок на картинку и страницу товара.
     *
     * @throws \Exception
     */
    public function checkItemsImageUrl()
    {
        $I = $this->tester;

        $I->waitForElementVisible('//div[@class="MainHeader__search"]//div[@class="SearchQuickResult__categories"]');
        $categoryLink = $I->grabAttributeFrom('//div[@class="MainHeader__search"]//a[@class="SearchQuickResult__product-link"]', 'href');
        codecept_debug($categoryLink);
        if ($categoryLink == NULL) {
            Throw new \Exception('category url empty');
        }

        $imageLink = $I->grabAttributeFrom('//div[@class="MainHeader__search"]//a[@class="SearchQuickResult__product-link"]//img', 'src');
        codecept_debug($imageLink);
        if ($imageLink == NULL) {
            Throw new \Exception('jpg url empty');
        }
    }
}
