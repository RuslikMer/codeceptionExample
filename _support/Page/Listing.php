<?php

namespace Page;
use \Facebook\WebDriver\WebDriverElement;

class Listing {

    // include url of current page
    public static $URL = '';

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
    public static function route($param) {
        return static::$URL . $param;
    }

    /**
     * @var AcceptanceTester
     */
    protected $tester;

    public function __construct(\AcceptanceTester $I) {
        $this->tester = $I;
    }

    //Путь к блоку фильтров брендов
    const FILTER_BRAND_SECTION = '//div[@class="FilterBlockNewDesign"][contains(.,"Бренд")]//span[@class="FilterLabelNewDesign__name"]';
    //начало блока описания товара в листинге
    const XPATH_PRODUCT_ITEM = '//div[@class="ProductCardCategoryList__grid"]'.Home::XPATH_PRODUCT_CARD;
    //класс с блоком наличия товара
    const CLASS_ITEM_IN_STOCK = 'js--product-in-stock product-in-stock';
    //блок наличия товара
    const XPATH_ITEM_IN_STOCK = '//div[@class="' . self::CLASS_ITEM_IN_STOCK . '"]';
    //цена товара в листинге
    const XPATH_PRODUCT_PRICE = '//div[contains(@class,"ProductPrice_default")]';
    //таблица с товарами в листинге
    const XPATH_PRODUCT_TABLE = '//div[@class="product_category_list"]';
    const LISTING_TITLE = self::XPATH_PRODUCT_ITEM . self::PRODUCT_TITLE;
    //Путь к блоку брендов в нижней части листинга
    const BOTTOM_BRAND_LIST = '//span[@class="BrandList__item"]';
    //список тегов внутри блока со слайдером тегов
    const TAG_LIST_XPATH = '//li[@class="tags-slider__slide-item"]/a';
    //верхний список тегов
    const TOP_TAG = '//div[@class="main_content_inner"]/div/div[@class="tags-slider"]//div[@class="tags-slider__slider-list"]';
    //нижний список тегов
    const DOWN_TAG = '//div[@class="main_content_wrapper"]//div[@class="tags-slider"][2]//div[@class="tags-slider__slider-list"]';
    const SUBCATEGORY_LIST = '//div[contains(@class,"CatalogMenu__subcategory ps")]//div[@class="CatalogMenu__subcategory-list"]/div';
    const CATEGORY_LIST = '//div[@class="CatalogMenu__category ps"]/div[@class="CatalogMenu__category-items"]';
    const FILTER_NAME = '//span[@class="FilterLabelNewDesign__name"]';
    const FILTER_ITEMS = '//div[@class="FilterGroupNewDesign"]/div';
    const FILTERS = '//div[@class="FilterBlockNewDesign"]';
    //количество товаров в листинге
    const XPATH_PRODUCTS_COUNTER = '//span[@class="products_counter products_counter__js"]';
    //блок с хлебными крошками
    const XPATH_BREADCRUMBS = '//div[@class="Breadcrumbs"]/a';
    const GRID_SECTIONS = '//div[@class="ProductCardCategoryList__grid-container"]//section';
    const GRID_PRODUCTS = self::GRID_SECTIONS.'/div';
    const LIST_PRODUCTS = '//section[@class="ProductGroupList"]/div';
    const CART_ITEMS = '//a[contains(@href,"order")]//div[contains(@class,"HeaderMenu__count")]';
    const GRID_PRODUCTS_TITLE = self::GRID_SECTIONS.self::PRODUCT_TITLE;
    const PRODUCT_TITLE = '//div[@class="ProductCardVertical__name"]';
    const PRODUCT_IMAGE = '//div[@class="ProductCardVertical__image-wrap"]';
    const CATEGORY_TITLE = '//div[@class="Subcategory__title-container"]/h1';
    const GRID_PRODUCT_PRICE = '//span[@class="ProductCardVertical__price-current_current-price"]';
    const SEO_FILTERS = '//div[@class="FilterSeoGroup"]//div/a';
    const XPATH_SORT = '//main//div[@class="ProductCardCategoryList__sorting-list"]//div';
    const VIEW_GRID = 'grid';
    const VIEW_LIST = 'list';
    const GRID_BUYBUTTON = '//div[contains(@class,"ProductCardVertical__button-buy")]';
    const LIST_BUYBUTTON = '//button[contains(@class,"ProductCardHorizontal__button-buy")]';
    const SHOW_ITEM = 'Показывать товар';
    const STATUS = 'Статус';
    const INFO_RATE = 'рейтинг';
    const INFO_REVIEW = 'отзыв';
    const INFO_OVERVIEW = 'обзор';

    /**
     * Добавить в корзину из списка-таблицы.
     *
     * @param int $sectionNum Порядковый номер секции в листинге
     * @param int $itemNum Порядковый номер товара в секции листинга
     * @param string $viewType тип отображения листинга
     * @param string $buyButton путь к кнопке "в корзину"
     * @return string $itemId Id товара
     * @throws \Exception
     */
    public function addToCart($sectionNum, $itemNum, $viewType, $buyButton)
    {
        $I = $this->tester;

        $this->checkListingViewIs($viewType);
        if($sectionNum == null){
            $itemData = $I->getRandomItemNumberInListing();
            $sectionNum = $itemData[0];
            $itemNum = $itemData[1];
        }

        $cartItems = $I->getNumberFromLink(self::CART_ITEMS, 'cart items before add');
        $I->lookForwardTo('go to product ' . $itemNum . ' from ' .$sectionNum. ' section ' .$viewType." view");
        $itemId = $I->getItemIdFromListing($sectionNum, $itemNum);
        $I->waitAndClick(self::GRID_SECTIONS . '[' . $sectionNum . ']/div['. $itemNum .']' . $buyButton,
            'add to cart');
        $this->waitBasketLoader();
        $cartItemsAfterAdd = $I->getNumberFromLink(self::CART_ITEMS, 'cart items after add');
        if ($cartItems >= $cartItemsAfterAdd){
            Throw new \Exception('cart items before add ' . $cartItems . ' more or equals cart items after add ' . $cartItemsAfterAdd);
        }

        return $itemId;
    }

    /**
     * Переход к списку с главной страницы.
     *
     * @param int $mainCat Порядковый номер категории, счет сверху вниз
     * @param int $subCat Порядковый номер подкатегории, счет сверху вниз
     * @param int $lowCat Порядковый номер подкатегории, счет сверху вниз
     * @return string $selectedCategoryName название конечной категории
     * @throws \Exception
     */
    public function goToListing($mainCat = null, $subCat = null, $lowCat = null)
    {
        $I = $this->tester;

        $I->lookForwardTo('**** click to menu category: first parameter is ' . $mainCat . ', second is ' . $subCat . ' and third is ' . $lowCat);
        $I->waitAndClick(Menu::CATALOG, 'open main menu category', true);
        $I->waitForElementVisible(self::CATEGORY_LIST);
        if ($mainCat == null){
            $categoryList = $I->grabMultipleDesc(self::CATEGORY_LIST, "main category");
            $categoryList = array_filter($categoryList);
            codecept_debug($categoryList);
            $mainCat = mt_rand(1, count($categoryList));
        }

        $I->moveMouseOver(self::CATEGORY_LIST . '[' . $mainCat . ']');
        $I->waitForElementVisible(self::CATEGORY_LIST.'//span[contains(@class,"CatalogMenu__category-item_selected")]');
        $I->wait(SHORT_WAIT_TIME);
        $I->waitForElementVisible(self::SUBCATEGORY_LIST);
        if ($subCat == null){
            $subCategoryList = $I->grabMultipleDesc(self::SUBCATEGORY_LIST, "sub category");
            $subCategoryList = array_filter($subCategoryList);
            codecept_debug($subCategoryList);
            $subCat = mt_rand(1, count($subCategoryList));
        }

        $categoryName = $I->grabTextFrom(self::SUBCATEGORY_LIST . '[' . $subCat . ']');
        $lowCatCount = $I->getNumberOfElements(self::SUBCATEGORY_LIST . '[' . $subCat . ']//div[@class="CatalogMenu__subcategory-item"]/a');
        if ($lowCatCount != 0) {
            if ($lowCat == null) {
                $lowCat = mt_rand(1, $lowCatCount);
            }

            $I->moveMouseOver(self::SUBCATEGORY_LIST . '[' . $subCat . ']//div[@class="CatalogMenu__subcategory-item"][' . $lowCat . ']/a');
            $categoryName = $I->grabTextFrom(self::SUBCATEGORY_LIST . '[' . $subCat . ']//div[@class="CatalogMenu__subcategory-item"][' . $lowCat . ']/a');
            $I->waitAndClick(self::SUBCATEGORY_LIST . '[' . $subCat . ']//div[@class="CatalogMenu__subcategory-item"][' . $lowCat . ']/a',
               'low category');
        }else{
            $I->moveMouseOver(self::SUBCATEGORY_LIST . '[' . $subCat . ']//a');
            $I->waitAndClick(self::SUBCATEGORY_LIST . '[' . $subCat . ']//a',
                'low category');
        }

        $I->waitForElementVisible('//div[@class="FilterContainer"]');
        $I->waitForElementVisible(['class' => 'Subcategory']);
        $I->waitForElementVisible('//h1');
        $selectedCategoryName = $I->grabTextFrom('//h1');
        codecept_debug('Selected category is - '.$selectedCategoryName);
        $categoryName = str_replace('"', '',$categoryName.'"');
        if (!stristr($selectedCategoryName, $categoryName)) {
            Throw new \Exception($selectedCategoryName . ' is not equal '. $categoryName);
        }

        return $selectedCategoryName;
    }

    /**
     * Выбор случайной подкатегории из каталога уцененных товаров.
     *
     * @throws \Exception
     */
    public function goToDiscountListing()
    {
        $I = $this->tester;

        $k = 0;
        do {
            $categoryQuantity = $I->getNumberOfElements('//ul[@class="CatalogLayout__item"]', 'discount categories');
            $category = mt_rand(1, $categoryQuantity);
            $subCategoryQuantity = $I->getNumberOfElements('//ul[@class="CatalogLayout__item"][' . $category . ']//li[@class="CatalogLayout__children-item"]',
                'discount subcategories - for ' . $category . ' category');
            $k = $k + 1;
        } while ($subCategoryQuantity == 0 AND $k < 3);

        $subCategory = mt_rand(1, $subCategoryQuantity);
        $I->waitAndClick('//ul[@class="CatalogLayout__item"][' . $category . ']//li[@class="CatalogLayout__children-item"][' . $subCategory . ']/a',
            'select subcategory');
        $I->checkMandatoryElements();
        $subCategoriesNavigation = $I->getNumberOfElements('//div[@class="CategoryGrid__item"][not(contains(.,"Каталог"))]');
        if ($subCategoriesNavigation > 0){
            $subCatNum = mt_rand(1, $subCategoriesNavigation);
            $I->waitAndClick('//div[@class="CategoryGrid__item"][not(contains(.,"Каталог"))]['.$subCatNum.']//h2', 'select low category');
        }

        $I->waitForElementVisible(self::CATEGORY_TITLE);
        $selectedCategoryName = $I->grabTextFrom(self::CATEGORY_TITLE);
        $selectedCategoryNameView = $I->translit($selectedCategoryName);
        codecept_debug('Selected category is - '.$selectedCategoryNameView);
    }

    /**
     * Переход к списку с главной страницы, добавление товара из списка и переход по изменяемой кнопке в корзину.
     *
     * @param int $param Порядковый номер категории, счет сверху вниз
     * @param int $subCat Порядковый номер подкатегории, счет сверху вниз
     * @return string $itemId
     * @throws \Exception
     */
    public function goToListAddToCart($param, $subCat = null)
    {
        $I = $this->tester;

        $I->goToListing($param, $subCat);
        $itemId = $I->addToCartFromListing(null, null, self::VIEW_LIST, self::LIST_BUYBUTTON);
        $I->continueShopping();
        $I->goToCartByButtonFromListing($itemId);

        return $itemId;
    }

    /**
     * Сортировка товаров в листинге по возрастанию, с выбором типа сортировки.
     *
     * @param string $sortType Тип сортировки: 'price', 'name', 'rating', 'reviews', 'opinions'
     * @throws \Exception
     */
    public function sortProductBy($sortType)
    {
        $I = $this->tester;

        $I->lookForwardTo('sort by ' . $sortType);
        $activeSort = $I->grabAttributeFrom(self::XPATH_SORT . '[@data-alias="' . $sortType . '"]', 'class');
        if ( stripos($activeSort, 'active') == 0 ) {
            $I->waitAndClick(self::XPATH_SORT . '[@data-alias="' . $sortType . '"]', 'choose sort by ' . $sortType, true);
            $I->wait(SHORT_WAIT_TIME);
        }

        $currentDescAsc = $I->grabAttributeFrom(self::XPATH_SORT . '[@data-alias="' . $sortType . '"]/div[2]', 'class');
        if ( stripos($currentDescAsc, 'asc') > 1 ) {
            codecept_debug('current sorting attribute is [' . $currentDescAsc . '] so nothing to change');
        } else {
            codecept_debug('current sorting attribute is [' . $currentDescAsc . '] so change it to ASC');
            $I->waitAndClick(self::XPATH_SORT . '[@data-alias="' . $sortType . '"]', 'sort switching link', true);
            $I->wait(SHORT_WAIT_TIME);
        }

        $initialDescAsc = $I->grabAttributeFrom(self::XPATH_SORT . '[@data-alias="' . $sortType . '"]/div[2]', 'class');
        codecept_debug('sorting attribute after click is [' . $initialDescAsc . ']');
    }

    /**
     * Сортировка товаров в листинге по убыванию, с выбором типа сортировки.
     *
     * @param string $sortType Тип сортировки: 'price', 'name', 'rating', 'reviews', 'opinions'
     * @throws \Exception
     */
    public function sortProductByDesc($sortType)
    {
        $I = $this->tester;

        $I->lookForwardTo('sort by ' . $sortType);
        $activeSort = $I->grabAttributeFrom(self::XPATH_SORT . '[@data-alias="' . $sortType . '"]', 'class');
        if ( stripos($activeSort, 'active') == 0 ) {
            $I->waitAndClick(self::XPATH_SORT . '[@data-alias="' . $sortType . '"]', 'choose sort by ' . $sortType, true);
            $I->wait(SHORT_WAIT_TIME);
        }

        $currentDescAsc = $I->grabAttributeFrom(self::XPATH_SORT . '[@data-alias="' . $sortType . '"]/div[2]', 'class');
        if ( stripos($currentDescAsc, 'desc') > 1 ) {
            codecept_debug('current sorting attribute is [' . $currentDescAsc . '] so nothing to change');
        } else {
            codecept_debug('current sorting attribute is [' . $currentDescAsc . '] so change it to DESC');
            $I->waitAndClick(self::XPATH_SORT . '[@data-alias="' . $sortType . '"]', 'sort switching link', true);
            $I->wait(SHORT_WAIT_TIME);
        }

        $initialDescAsc = $I->grabAttributeFrom(self::XPATH_SORT . '[@data-alias="' . $sortType . '"]/div[2]', 'class');
        codecept_debug('sorting attribute after click is [' . $initialDescAsc . ']');
    }

    /**
     * Добавление элемента и переход в корзину из списка-таблицы.
     *
     * @return string $itemId Id товара
     * @throws \Exception
     */
    public function addAndGoToCartFromListing()
    {
        $I = $this->tester;
        $itemId = $I->addToCartFromListing();
        $I->goToCartFromListingPopup();

        return $itemId;
    }

    /**
     * Переход к списку с главной страницы, добавление товара из списка-таблицы.
     *
     * @param int $param Порядковый номер категории, счет сверху вниз
     * @param int $subCat Порядковый номер подкатегории, счет сверху вниз
     * @return string $itemId Id товара
     * @throws \Exception
     */
    public function goToListAddToCartOnly($param, $subCat = null)
    {
        $I = $this->tester;

        $I->goToListing($param, $subCat);
        $itemId = $I->addToCartFromListing();
        $I->continueShopping();

        return $itemId;
    }

     /**
     * Получение рейтинга ПЕРВОГО товара из списка.
     *
     * @return string Рейтинг в условном формате (4.5) -> (45)
     */
    public function getFirstRateFromListingPage()
    {
        $I = $this->tester;

        $starListing = $I->getNumberFromLink('//div[@class="ProductCardVertical__meta"]/div[1]', 'rate');
        codecept_debug($starListing);

        return $starListing;
    }

    /**
     * Проверка сортировки по цене.
     *
     * @throws \Exception В случае если сортировка на сайте не совпала с сортировкой в пхп
     */
    public function checkSortByPrice()
    {
        $I =  $this->tester;

        $I->lookForwardTo('find most expensive item');
        $I->sortProductBy('price');
        $sortArray = $I->grabMultiple(self::GRID_PRODUCTS . self::GRID_PRODUCT_PRICE);
        $I->seeArrayNotEmpty($sortArray);
        codecept_debug("grabbed (initial) array of prices");
        codecept_debug($sortArray);
        $ascArray = $sortArray = preg_replace("/[^0-9]/", "", $sortArray);
        codecept_debug("initial array of prices (only digits)");
        codecept_debug($ascArray);
        $I->lookForwardTo('sort array');
        sort($sortArray, $sort_flags = SORT_NUMERIC);
        codecept_debug("sorted array of prices");
        codecept_debug($sortArray);
        $diffArray = array_diff_assoc($ascArray, $sortArray);
        codecept_debug("difference between two arrays");
        codecept_debug($diffArray);
        if ($ascArray != $sortArray) {
            Throw new \Exception('WRONG SORT BY PRICE, difference between sorted and initial arrays is not empty');
        }
    }

    /**
     * Проверка сортировки по наименованию, кликает на сортировку по наименованию, собирает массив наименований со страницы, сортирует массив
     *
     * @throws \Exception В случае если сортировка на сайте не совпала с сортировкой в пхп
     */
    public function checkSortByName()
    {
        $I =  $this->tester;
        $I->lookForwardTo('grab array');
        $I->sortProductBy('name');

        $ascArray = $sortArray = $I->grabMultipleDesc(self::GRID_PRODUCTS_TITLE, 'products title name');
        $I->seeArrayNotEmpty($ascArray);
        codecept_debug($ascArray);
        $I->lookForwardTo('sort array');
        sort($sortArray, $sort_flags = SORT_NATURAL | SORT_FLAG_CASE);
        codecept_debug($sortArray);
        $diffArray = array_diff_assoc($ascArray, $sortArray);
        codecept_debug($diffArray);
        if ($ascArray != $sortArray) {
            Throw new \Exception('WRONG SORT BY NAME');
        }
    }

    /**
     * Проверка сортировки по количеству обзоров, кликает на сортировку по обзорам,
     * собирает массив количества обзоров по товарам со страницы, сортирует массив.
     *
     * @return int $reviewsProduct количество товаров с обзорами
     * @throws \Exception В случае если отличается порядок результата сортировок
     */
    public function checkSortByReview()
    {
        $I =  $this->tester;

        $I->lookForwardTo('grab reviews array');
        $reviewsProduct = $I->getNumberOfElements(self::GRID_PRODUCTS.'[contains(.,"обзор")]');
        if ($reviewsProduct != 0) {
            $listing = $this->getProductsListingArray();
            $I->sortProductBy('reviews');
            $listingAfterSort = $this->getProductsListingArray();
            $I->seeProductsListingNotEquals($listing, $listingAfterSort);
        }

        return $reviewsProduct;
    }

    /**
     * Проверка сортировки по количеству отзывов, кликает на сортировку по отзывам,
     * собирает массив количества отзывов по товарам со страницы, сортирует массив.
     *
     * @throws \Exception В случае если отличается порядок результата сортировок
     */
    public function checkSortByReport()
    {
        $I =  $this->tester;

        $I->lookForwardTo('grab array');
        $I->sortProductByDesc('opinions');
        $ascArray = $sortArray = $I->grabMultipleDesc('//div[@class="ProductCardVertical__meta"]/div[2]', 'opinions count');
        codecept_debug($ascArray);
        $I->seeArrayNotEmpty($ascArray);
        $I->lookForwardTo('sort array');
        sort($sortArray, $sort_flags = SORT_NUMERIC);
        $sortArray = array_reverse($sortArray);
        codecept_debug($sortArray);
        $diffArray = array_diff_assoc($ascArray, $sortArray);
        codecept_debug($diffArray);
        if ($ascArray != $sortArray) {
            Throw new \Exception('WRONG SORT BY OPINIONS');
        }
    }

    /**
     * Сравнение наименования Категории с наименованием в хлебных крошках.
     *
     * @throws \Exception В случае если наименования не совпадают
     */
    public function checkCategoryName()
    {
        $I =  $this->tester;

        $nameCategory = $I->grabTextFrom(self::CATEGORY_TITLE);
        codecept_debug($nameCategory);
        $breadArray = $I->grabMultiple('//div[@class="Breadcrumbs"]', 'data-breadcrumb-name');
        $nameLink = array_pop($breadArray);
        codecept_debug($nameLink);
        if ($nameCategory != $nameLink) {
            Throw new \Exception('WRONG CATEGORY NAME');
        }
    }

    /**
     * Проверка фильтров.
     *
     * @param string $filter название категории фильтра
     * @throws \Exception В случае отсутствия изменения выдычи после применения фильтра
     */
    public function checkFilter($filter)
    {
        $I =  $this->tester;

        $filterXpath = self::FILTERS.'[contains(.,"'.$filter.'")]'.self::FILTER_NAME;
        $arrayFilters = $I->grabMultipleDesc($filterXpath, 'current filter');
        codecept_debug('array of filters');
        codecept_debug($arrayFilters);
        $listingProducts = [];

        foreach ($arrayFilters as $key => $value) {
            codecept_debug('chose value for filter ' . $value);
            $I->scrollTo($filterXpath . '[contains(.,"'. $value .'")]',0,-300);
            $I->waitAndClick($filterXpath . '[contains(.,"'. $value .'")]', 'select filter', true);
            $I->checkElementOnPage('//div[contains(@class,"FilterLabelNewDesign_selected")]'.self::FILTER_NAME.'[contains(.,"'. $value .'")]',
                $value . ' filter selected');
            $I->wait(SHORT_WAIT_TIME);
            $listingProductsAfterFilter = $I->grabMultipleDesc(self::GRID_PRODUCTS_TITLE, 'listing items');
            codecept_debug('listing products after filter');
            codecept_debug($listingProductsAfterFilter);
            if ($value != 'Есть в Магазине'){
                $I->seeProductsListingNotEquals($listingProducts, $listingProductsAfterFilter);
            }

            $listingProducts = $listingProductsAfterFilter;
        }
    }

    /**
     * Проверка фильтра по цене.
     *
     * @throws \Exception Если первый товар в списке до и после фильтрации совпадает по цене
     */
    public function checkFilterPrice()
    {
        $I =  $this->tester;

        $minPrice = $I->getNumberFromLink('//input[@name="input-min"]', 'min price');
        codecept_debug('Minimum price from filter ' . $minPrice);
        $maxPrice = $I->getNumberFromLink('//input[@name="input-max"]', 'max price');
        codecept_debug('Maximum price from filter ' . $maxPrice);
        $minItemPrice = $I->getNumberFromLink(self::GRID_PRODUCTS . '[1]' . self::XPATH_PRODUCT_PRICE, 'get min item price');
        $secondMinItemPrice = $I->getNumberFromLink(self::GRID_PRODUCTS . '[5]' . self::XPATH_PRODUCT_PRICE, 'get second min item price');
        $I->fillField('//input[@name="input-min"]', $secondMinItemPrice);
        $I->wait(SHORT_WAIT_TIME);
        $minFilterItemPrice = $I->getNumberFromLink(self::GRID_PRODUCTS . '[1]' . self::XPATH_PRODUCT_PRICE, 'get min filter item price');
        if($minItemPrice > $minFilterItemPrice){
            Throw new \Exception('The list is not filtered by min PRICE');
        }

        $I->sortProductBy('price');
        $maxItemPrice = $I->getNumberFromLink(self::GRID_PRODUCTS . '[1]' . self::XPATH_PRODUCT_PRICE, 'get max item price');
        $secondMaxItemPrice = $I->getNumberFromLink(self::GRID_PRODUCTS . '[5]' . self::XPATH_PRODUCT_PRICE, 'get second max item price');
        $I->fillField(['xpath' => '//input[@name="input-max"]'], $secondMaxItemPrice);
        $I->wait(SHORT_WAIT_TIME);
        $maxFilterItemPrice = $I->getNumberFromLink(self::GRID_PRODUCTS . '[1]' . self::XPATH_PRODUCT_PRICE, 'get max filter item price');
        if($maxItemPrice < $maxFilterItemPrice){
            Throw new \Exception('The list is not filtered by max PRICE');
        }
    }

    /**
     * Получение списка брендов.
     *
     * @throws \Exception
     */
    public function getBrandsFromListing()
    {
        $I =  $this->tester;

        $I->waitAndClick('//span[@class="FilterBlockNewDesign__title"][contains(.,"Бренд")]', 'open brand filter dropdown', true);
        $arrayFilter = $I->grabMultipleDesc(self::FILTER_BRAND_SECTION, 'Brand filter');
        $I->seeArrayNotEmpty($arrayFilter);
        codecept_debug($arrayFilter);

        return $arrayFilter;
    }

    /**
     * Проверка фильтра по Брендам.
     *
     * @param int $brandsQuantity количество брендов для выбора
     * @throws \Exception Если в листинге присутствуют объекты, не соответствующие фильтру
     */
    public function checkFilterBrand($brandsQuantity)
    {
        $I =  $this->tester;

        $brandsArray = $this->getBrandsFromListing();
        do {
            $rndBrandsArray = array_rand(array_flip($brandsArray), $brandsQuantity);
            codecept_debug($rndBrandsArray);
            if (!is_array($rndBrandsArray)){
                $rndBrandsArray = explode(' ', $rndBrandsArray);
                codecept_debug($rndBrandsArray);
            }
        } while (in_array("NONAME", $rndBrandsArray) == true);

        if($rndBrandsArray === NULL){
            Throw new \Exception('will not run check - not enough brands for test');
        }

        foreach ($rndBrandsArray as $brand) {
            $I->waitAndClick(self::FILTER_BRAND_SECTION . '[contains(.,"'. $brand .'")]', 'brand filter', true);
            $I->checkElementOnPage('//div[contains(@class,"FilterLabelNewDesign_selected")]'.self::FILTER_NAME.'[contains(.,"'. $brand .'")]',
                $brand . ' filter selected');
            $I->wait(SHORT_WAIT_TIME);
        }

        $arrayProductName = $this->getProductsListingArray();
        codecept_debug(mb_internal_encoding());
        foreach ($arrayProductName as $value) {
            codecept_debug($arrayProductName);
            for ($i = 0; $i < count($rndBrandsArray); $i++){
                codecept_debug("check product name " . $value . " with " . $rndBrandsArray[$i] . " brand");
                $result = mb_stripos($value, $rndBrandsArray[$i]);
                if($result !== false){
                    codecept_debug(array_search($value, $arrayProductName));
                    unset($arrayProductName[array_search($value, $arrayProductName)]);
                }
                codecept_debug($arrayProductName);
            }

            if (empty($arrayProductName)){
                break;
            }
        }
    }

    /**
     * Проверка блока пагинации.
     *
     * @param string $path
     * @throws \Exception Проверка выбранной страницы
     */
    public function checkPagination($path)
    {
        $I = $this->tester;

        $pagination = $I->getNumberOfElements('//div[@class="page_listing"]/section/ul/li[1]');
        if ($pagination !== 0) {
            $I->lookForwardTo('check that the first page is selected');
            $selected = $I->grabAttributeFrom(['xpath' => '//div[@class="page_listing"]/section/ul/li[1]'], "class");
            codecept_debug("The first page is " . $selected);
            if ($selected != "selected") {
                Throw new \Exception('The first page is not selected');
            }

            $arrayFirstPage = $I->grabMultiple(['xpath' => $path]);
            codecept_debug($arrayFirstPage);
            $I->lookForwardTo('check that the second page is selected');
            $I->waitAndClick('//div[@class="page_listing"]/section/ul/li[@class="next"]', "second page");
            $I->smartWait(SHORT_WAIT_TIME);

            $next = $I->grabAttributeFrom(['xpath' => '//div[@class="page_listing"]/section/ul/li[2]'], "class");
            codecept_debug("The second page is " . $next);
            $previous = $I->grabAttributeFrom(['xpath' => '//div[@class="page_listing"]/section/ul/li[1]'], "class");
            codecept_debug("The first page is " . $previous . "");

            if (($next != "selected") OR ($previous != "previous")) {
                Throw new \Exception('The second page is not selected');
            }

            $arraySecondPage = $I->grabMultiple(['xpath' => $path]);
            codecept_debug($arraySecondPage);
            if ($arrayFirstPage == $arraySecondPage) {
                Throw new \Exception('List of products was not changed');
            }
        }

        $dotsCount = $I->getNumberOfElements('//div[@class="page_listing"]/section/ul/li[@class="more"]');
        if($dotsCount !== 0) {
            $I->lookForwardTo('check selected three dots page');
            $I->waitAndClick('//div[@class="page_listing"]/section/ul/li[@class="more"]', "three dots page");
            $I->smartWait(SHORT_WAIT_TIME);

            $threeDots = $I->grabAttributeFrom(['xpath' => '//div[@class="page_listing"]/section/ul/li[7]'], "class");
            codecept_debug("The right dots is " . $threeDots . "");
            $less = $I->grabAttributeFrom(['xpath' => '//div[@class="page_listing"]/section/ul/li[2]'], "class");
            codecept_debug("The left dots is " . $less);
            if (($threeDots != "selected") OR ($less != "less")) {
                Throw new \Exception('The three dots page is not selected');
            }

            $arrayDotsPage = $I->grabMultiple(['xpath' => $path]);
            codecept_debug($arrayDotsPage);
            if ($arrayDotsPage == $arraySecondPage) {
                Throw new \Exception('List of products was not changed');
            }

            $I->lookForwardTo('check that the left dots page is selected');
            $I->waitAndClick('//div[@class="page_listing"]/section/ul/li[@class="less"]', "The left dots");
            $I->smartWait(SHORT_WAIT_TIME);

            $less = $I->grabAttributeFrom(['xpath' => '//div[@class="page_listing"]/section/ul/li[2]'], "class");
            codecept_debug("The left dots is " . $less);
            if ($less != "selected") {
                Throw new \Exception('The left dots page is not selected');
            }

            $arrayLeftDotsPage = $I->grabMultiple(['xpath' => $path]);
            codecept_debug($arrayLeftDotsPage);
            if ($arrayDotsPage == $arrayLeftDotsPage) {
                Throw new \Exception('List of products was not changed');
            }

            $I->lookForwardTo('check that the last page is selected');
            $I->waitAndClick('//div[@class="page_listing"]/section/ul/li[@class="last"]', "The last page");
            $I->smartWait(SHORT_WAIT_TIME);

            $lastPage = $I->getNumberOfElements('//div[@class="page_listing"]/section/ul/li', 'pagination block');
            codecept_debug($lastPage);
            $last = $I->grabAttributeFrom(['xpath' => '//div[@class="page_listing"]/section/ul/li[' . $lastPage / 2 . ']'], "class");
            codecept_debug("The last page is " . $last . "");
            if ($last != "selected") {
                Throw new \Exception('The last page is not selected');
            }

            $arrayLastPage = $I->grabMultiple(['xpath' => $path]);
            codecept_debug($arrayLastPage);
            if ($arrayLastPage == $arrayLeftDotsPage) {
                Throw new \Exception('List of products was not changed');
            }

            $I->lookForwardTo('check that the first page is selected');
            $I->waitAndClick('//div[@class="page_listing"]/section/ul/li[@class="first"]', "The first page");
            $I->smartWait(SHORT_WAIT_TIME);

            $first = $I->grabAttributeFrom(['xpath' => '//div[@class="page_listing"]/section/ul/li[1]'], "class");
            codecept_debug("The first page is " . $first . "");
            if ($first != "selected") {
                Throw new \Exception('The first page is not selected');
            }

            $arrayFirstPage = $I->grabMultiple(['xpath' => $path]);
            codecept_debug($arrayFirstPage);
            if ($arrayLastPage == $arrayFirstPage) {
                Throw new \Exception('List of products was not changed');
            }
        }
    }

    /**
     * Переход на страницу бренда в категории.
     *
     * @throws \Exception Если выбранный бренд не соответсвует бренду в наименовании категории.
     */
    public function selectBaseBrand()
    {
        $I = $this->tester;

        $I->lookForwardTo('brand in footer');
        $number = $I->getNumberOfElements(self::BOTTOM_BRAND_LIST . '/a', 'Brands in footer');
        $numberRnd = mt_rand(1, $number);
        $brandName = $I->grabTextFrom(self::BOTTOM_BRAND_LIST . '['. $numberRnd .']');
        codecept_debug($brandName . ' Brand name');
        $I->scrollTo(self::BOTTOM_BRAND_LIST . '['. $numberRnd .']', 0, -100);
        $I->waitAndClick(self::BOTTOM_BRAND_LIST . '['. $numberRnd .']', 'brand in footer', true);
        $I->see($brandName, self::CATEGORY_TITLE);
    }

    /**
     * Проверка брендов в футере.
     *
     * @throws \Exception Если выбранный бренд не соответсвует бренду в хлебных крошках.
     */
    public function checkFooterBrand()
    {
        $I = $this->tester;

        $I->lookForwardTo('brand in footer');
        $arrayBrand = $I->grabMultipleDesc(self::BOTTOM_BRAND_LIST . '/a', 'brands');
        codecept_debug($arrayBrand);
        $number = count($arrayBrand);
        codecept_debug('number of brands in bottom block ' . $number);
        if($number > 1){
            $number = $number - 1;
            foreach ($arrayBrand as $key => $value) {
                codecept_debug('brand number from array is ' . $key . ' initial');
                $keyForClick = $key + 1;
                codecept_debug('brand number from array is ' . $keyForClick . ' for click');
                $I->scrollTo(self::BOTTOM_BRAND_LIST . '['. $keyForClick .']', 0, -100);
                $I->waitAndClick(self::BOTTOM_BRAND_LIST . '['. $keyForClick .']', 'brand in footer', true);
                $brandName = $I->grabTextFrom(self::BOTTOM_BRAND_LIST . '[' . $keyForClick . ']');
                $I->see($brandName, self::CATEGORY_TITLE);
                codecept_debug('brand name clicked is "' . $brandName . '"');
                if($keyForClick == $number){
                    break;
                }
            }
        }
    }

    /**
     * Переход в КТ по клику на товар в листинге.
     *
     * @param int $sectionNum Порядковый номер секции в листинге
     * @param int $itemNum Порядковый номер товара в секции листинга
     * @param string $info переход в КТ с отзывом, обзором или рейтингом
     * @param string $xpath путь к картинке или названию
     * @return string $productName название товара
     * @throws \Exception
     */
    public function goToProductFromListing($sectionNum, $itemNum, $info, $xpath)
    {
        $I = $this->tester;

        if($sectionNum == null){
            $itemData = $this->getRandomItemNumber($info);
            $sectionNum = $itemData[0];
            $itemNum = $itemData[1];
        }

        $I->lookForwardTo('go to product ' . $itemNum . ' from ' .$sectionNum. ' section ');
        $I->scrollTo(self::GRID_SECTIONS . '['. $sectionNum .']/div['. $itemNum .']', 0, -100);
        $productName = $I->grabTextFrom(self::GRID_SECTIONS . '[' . $sectionNum . ']/div['. $itemNum .']' . self::PRODUCT_TITLE);
        codecept_debug('Product name: '.$productName);
        $I->waitAndClick(self::GRID_SECTIONS . '[' . $sectionNum . ']/div['. $itemNum .']' . $xpath,
            'go to product ' . $itemNum . ' from ' .$sectionNum. ' section ', true);
        $I->wait(SHORT_WAIT_TIME);

        return $productName;
    }

    /**
     * Переход в КТ по клику на товар в листинге по айди товра.
     *
     * @param string $itemId айди товара в листинге
     * @return string $productName название товара
     * @throws \Exception
     */
    public function goToProductFromListingById($itemId)
    {
        $I = $this->tester;

        if($itemId == null){
            $itemId = $I->getItemIdFromListing();
        }

        $I->lookForwardTo("go to product " . $itemId . " from listing page");
        $I->scrollTo(self::GRID_PRODUCTS . '[@data-product-id="'. $itemId .'"]', 0, -100);
        $productName = $I->grabTextFrom(self::GRID_PRODUCTS . '[@data-product-id="'. $itemId .'"]' . self::PRODUCT_TITLE);
        codecept_debug('Product name: '.$productName);
        $I->waitAndClick(self::GRID_PRODUCTS . '[@data-product-id="'. $itemId .'"]' . self::PRODUCT_TITLE,
            'go to product ' . $itemId . ' page', true);
        $I->wait(SHORT_WAIT_TIME);

        return $productName;
    }

    /**
     * Выбрать фильтр Показывать товар - Любой.
     *
     * @throws \Exception Если фильтр останется не выбранным
     */
    public function selectFilterShowProductAny()
    {
        $I = $this->tester;

        $I->lookForwardTo('select filter - Any');
        $I->scrollTo(self::FILTERS.'[contains(.,"'.self::SHOW_ITEM.'")]//span[contains(.,"Показать все")]',0,-300);
        $I->waitAndClick(self::FILTERS.'[contains(.,"'.self::SHOW_ITEM.'")]//span[contains(.,"Показать все")]',
            'show all product', true);
        $I->wait(SHORT_WAIT_TIME);
        $I->scrollTo(self::FILTERS.'[contains(.,"'.self::SHOW_ITEM.'")]'.self::FILTER_ITEMS.self::FILTER_NAME. '[contains(.,"Любой")]',0,-300);
        $I->waitAndClick(self::FILTERS.'[contains(.,"'.self::SHOW_ITEM.'")]'.self::FILTER_ITEMS.self::FILTER_NAME. '[contains(.,"Любой")]',
            'select filter', true);
        $this->checkSelectedFilter(self::SHOW_ITEM, 'Любой');
    }

    /**
     * Переход в карточку товара не в наличии.
     *
     * @throws \Exception Если отсутсвуют товары не в наличии
     */
    public function goToOutOfStockProduct()
    {
        $I = $this->tester;

        $I->lookForwardTo('look out of stock items');
        $outOfStockNumber =  -1;
        for ($i= 1; $i<=20; $i++){
            $buttonTitleXpath = ['xpath' => ''. self::XPATH_PRODUCT_TABLE . self::XPATH_PRODUCT_ITEM .'['. $i .']//div[@class="buttons no_visited"]/button[@type="submit"]'];
            $I->scrollTo($buttonTitleXpath);
            $outOfStock = $I->grabTextFrom($buttonTitleXpath);
            $stock = $I->translit($outOfStock);
            codecept_debug($stock);
            if($outOfStock == 'Нет в наличии') {
                $outOfStockNumber = $i;
                break;
            }
        }
        if($outOfStockNumber == -1) {
            Throw new \Exception("Not Found Product - Out of stock");
        }

        $I->waitAndClick(self::XPATH_PRODUCT_TABLE . self::XPATH_PRODUCT_ITEM .'['. $outOfStockNumber .']//span/a', 'out of stock product');
        $I->waitForElementVisible('//div[@class="specification_view product_view"]');
        $I->checkElementOnPage('//aside//div[@class="line-block standart_price out-of-stock_js"]', 'out of stock block');
    }

    /**
     * Переход в корзину через попап.
     *
     * @throws \Exception
     */
    public function goToCartFromPopup()
    {
        $I = $this->tester;

        $I->lookForwardTo("go to cart from popup");
        $I->waitAndClick(['class' => 'UpsaleBasket__header-link'], 'go to cart', true);
    }

    /**
     * Переход в корзину по изменяющейся кнопке "в корзину".
     *
     * @param int $itemId id товара
     * @throws \Exception
     */
    public function goToCartByButton($itemId)
    {
        $I = $this->tester;

        $I->lookForwardTo("go to cart from product button");
        $I->waitAndClick(Home::XPATH_PRODUCT_CARD . '[@data-product-id='. $itemId .']' . '//span[contains(.,"Оформить заказ")]',
            'go to cart button for product ' . $itemId, true);
    }

    /**
     * Проверка тэгов фильтров в листинге.
     *
     * @param string $tag Верхний или нижний тэг
     * @param int $half Проверяемая половина тэгов - 0,1
     * @throws \Exception Если тэги отсуствуют или после применения список товаров не изменился
     */
    public function checkListingTag($tag, $half)
    {
        $I = $this->tester;

        $I->lookForwardTo('grab tag');
        $numberTag = $I->getNumberOfElements($tag . self::TAG_LIST_XPATH, 'tags ' . $tag);
        if($numberTag == 0){
            Throw new \Exception('Tag filter not found');
        }

        $arrayTag = $I->grabMultipleDesc($tag . self::TAG_LIST_XPATH, 'tags ' . $tag, 'href');
        $countTag = count($arrayTag);
        if($countTag >= 6) {
            $numberOfParts = $countTag / 7;
            $rest = $countTag % $numberOfParts;
            if ($rest == 0) {
                $countSize = $countTag / $numberOfParts;
            } else {
                $countSize = $countTag / $numberOfParts + 1;
            }

            $trimArray = array_chunk($arrayTag, $countSize);
            $arrayTag = $trimArray[$half];
        }

        //в случае проверки второй половины тегов - прокручиваем оба списка тегов для корректного попадания в тег вместо стрелки
        if ($half > 0) {
            $I->waitAndClick('//div[@class="tags-slider__arrow-next js--tags-slider__arrow-next"]', 'next arrow - top tags');
            $I->waitAndClick('//div[@class="tags-slider"][2]//div[@class="tags-slider__arrow-next js--tags-slider__arrow-next"]', 'next arrow - bottom tags');
        }

        $listingProductSecond = $I->grabMultiple(['xpath' => self::LISTING_TITLE]);
        $numberOfProductsSecond = $I->getNumberFromLink(self::XPATH_PRODUCTS_COUNTER, 'number of products');
        foreach ($arrayTag as $key => $value){
            $listingProductFirst = $listingProductSecond;
            $numberOfProductsFirst = $numberOfProductsSecond;
            if($key == 0){
                $key = $key + 1;
            }
            $I->lookForwardTo('go to tag ' . $key);
            $textTag = $I->grabTextFrom(['xpath' => $tag . self::TAG_LIST_XPATH . '[@href="'. $value .'"]']);
            $textTagTrans = $I->translit($textTag);
            codecept_debug('Tag - ' . $textTagTrans);
            $I->waitAndClick($tag . self::TAG_LIST_XPATH . '[@href="'. $value .'"]', 'tags '. $value);
            $textTagArray = $I->grabMultipleDesc($tag . self::TAG_LIST_XPATH, 'tags');
            $resultFind = in_array($textTag, $textTagArray);
            codecept_debug('Check Tag - ' . $resultFind);
            codecept_debug($textTagArray);
            if($resultFind == true){
                Throw new \Exception('The tag - ' . $textTagTrans . ' - was found in the list of not clicked');
            }

            $listingProductSecond = $I->grabMultiple(['xpath' => self::LISTING_TITLE]);
            codecept_debug($listingProductSecond);
            $numberOfProductsFirst = $I->getNumberFromLink(self::XPATH_PRODUCTS_COUNTER, 'number of products');

            if ($listingProductFirst == $listingProductSecond) {
                if ($numberOfProductsFirst == $numberOfProductsSecond) {
                    codecept_debug('array before');
                    codecept_debug($listingProductFirst);
                    codecept_debug('array after');
                    codecept_debug($listingProductSecond);
                    Throw new \Exception("Tag filter not apply, arrays before and after are the same, product's counters are also the same");
                }
            }
            $I->moveBack();
        }
    }

    /**
     * Переход в карточку товара, который отсутствует в наличии в полноформатном магазине.
     *
     * @throws \Exception Если все товары из листинга присутствуют в наличии в магазине
     */
    public function goToProductOutOfMarket()
    {
        $I = $this->tester;

        $I->lookForwardTo('grab item listing');
        $itemOutMarket = $I->grabMultipleDesc(self::XPATH_PRODUCT_ITEM . self::XPATH_ITEM_IN_STOCK, 'item out of market','class');
        codecept_debug($itemOutMarket);
        foreach(array_keys($itemOutMarket, 'item') as $key){
            unset($itemOutMarket[$key]);
        }
        codecept_debug($itemOutMarket);
        $countItemOutMarket = count($itemOutMarket);
        codecept_debug($countItemOutMarket);
        if($countItemOutMarket == 0){
            Throw new \Exception('Not Find Item Out of Stock at Market');
        }

        $rndKey = array_rand($itemOutMarket, 1) + 1;
        $I->goToProductFromListing($rndKey);
    }

    /**
     * Проверка баннера на второй позиции.
     *
     * @throws \Exception Если баннер не найден на второй позиции
     */
    public function checkListingBanner()
    {
        $I = $this->tester;

        $listingTries = 0;
        do {
            $I->goToListing(2);
            $listingTries++;
        } while (($listingTries < 5) AND (0 == $I->getNumberOfElements('//div[@class="block_data__gtm-js block_data__pageevents-js listing_block_data__pageevents-js"]/div[@class="banner_tr"]',
                'banner at the second product')));
        $divName = $I->grabAttributeFrom(['xpath' => '//div[@class="block_data__gtm-js block_data__pageevents-js listing_block_data__pageevents-js"]/div[2]'], 'class');
        if($divName != 'banner_tr'){
                Throw new \Exception('No banner found in the second div, div name is - '.$divName);
        }
    }

    /**
     * Выбор конкретного фильтра в блоке "Показывать товар".
     *
     * @param string $filter название категории фильтра
     * @param string $filterName название конкретного фильтра
     * @throws \Exception
     */
    public function selectFilter($filter, $filterName)
    {
        $I = $this->tester;

        $I->lookForwardTo('select filter' . $filter);
        $filterItemName = $I->grabTextFrom(self::FILTERS.'[contains(.,"'.$filter.'")]'.self::FILTER_ITEMS.'[contains(.,"'.$filterName.'")]'.self::FILTER_NAME,
            'select filter');
        $I->waitAndClick(self::FILTERS.'[contains(.,"'.$filter.'")]'.self::FILTER_ITEMS.'[contains(.,"'.$filterName.'")]'.self::FILTER_NAME,
            'select filter');
        $this->checkSelectedFilter($filter, $filterItemName);
    }

    /**
     * Проверка, что конкретный фильтр выбран.
     *
     * @param string $filter название категории фильтра
     * @param string $filterItemName название категории фильтра
     * @throws \Exception
     */
    public function checkSelectedFilter($filter, $filterItemName)
    {
        $I = $this->tester;

        $I->waitForElementVisible(self::FILTERS.'[contains(.,"'.$filter.'")]'.self::FILTER_ITEMS.'[contains(.,"'.$filterItemName.'")]//div[@class="FilterCheckbox FilterCheckbox_checked"]');
    }

    /**
     * Проверка перехода по ссылкам хлебных крошек.
     *
     * @throws \Exception Если заголовок на странице не содержит наименование хлебной крошки
     */
    public function checkBreadcrumbs()
    {
        $I = $this->tester;

        $I->lookForwardTo('check Breadcrumbs');
        $hrefArray = $I->grabMultipleDesc(self::XPATH_BREADCRUMBS,'url of breadcrumbs', "href");
        $hrefArray = array_reverse($hrefArray);
        codecept_debug('breadcrumbs array reversed');
        codecept_debug($hrefArray);
        $i = 0;
        do{
            $titleCrumbs = $I->grabTextFrom(self::XPATH_BREADCRUMBS . '[@href="' . $hrefArray[$i] . '"]');
            $titleCrumbs = stristr($titleCrumbs.',', ',', true);
            codecept_debug($titleCrumbs . ' - Title Crumbs');
            $I->waitAndClick(self::XPATH_BREADCRUMBS . '[@href="' . $hrefArray[$i] . '"]', $hrefArray[$i]);
            $titleHeader = $I->grabTextFrom(['xpath' => '//h1']);
            codecept_debug($titleHeader . ' - Title Header');
            $result = mb_stristr($titleHeader, $titleCrumbs);
            codecept_debug($result . ' - Header Contain Crumbs');
            if($result == FALSE){
                Throw new \Exception('Breadcrumbs - ' . $titleCrumbs . ', but header from page - ' . $titleHeader);
            }

            $i++;
            $littleCrumbs = $I->getNumberOfElements(self::XPATH_BREADCRUMBS);
        }while($littleCrumbs != 0);
    }

    /**
     * Выбор случайного фильтра из блока full filter.
     *
     * @return string $rndFilter filter name
     * @throws \Exception
     */
    public function selectFullFilterSection()
    {
        $I = $this->tester;

        $I->lookForwardTo('select random section from full filters');
        $sectionsCount = $I->getNumberOfElements(['class' => 'FilterBlockNewDesign']);
        $rndSection = mt_rand(1, $sectionsCount);
        $opened = $I->getNumberOfElements(self::FILTERS.'[' . $rndSection . ']//div[contains(@class,"FilterDropdown__header_opened")]');
        if ($opened == 0) {
            $I->waitAndClick(self::FILTERS.'[' . $rndSection . ']//span[@class="FilterBlockNewDesign__title"]', 'open section');
        }

        $I->waitForElementVisible(self::FILTERS.'['.$rndSection.']//div[@class="FilterLabelNewDesign"]'.self::FILTER_NAME);
        $filtersArray = $I->grabMultiple(self::FILTERS.'['.$rndSection.']//div[@class="FilterLabelNewDesign"]'.self::FILTER_NAME);
        $rndFilter = $filtersArray[mt_rand(0, count($filtersArray)-1)];
        $rndFilter = str_replace(' "', '', $rndFilter);
        codecept_debug($rndFilter);
        $I->waitAndClick(self::FILTERS.'['.$rndSection.']//div[contains(@class,"FilterGroupNewDesign__filter-item")]'.self::FILTER_NAME.'[contains(.,"'.$rndFilter.'")]',
            'select filter');
        $I->waitForElementVisible(self::FILTERS.'['.$rndSection.']//div[contains(@class,"FilterLabelNewDesign_selected")][contains(.,"'.$rndFilter.'")]');

        return $rndFilter;
    }

    /**
     * Отмена выбора фильтра из блока full filter.
     *
     * @param string $rndFilter название фильтра
     * @throws \Exception
     */
    public function unSelectFullFilterSection($rndFilter)
    {
        $I = $this->tester;

        $I->lookForwardTo('unselect filter from full section');
        $I->waitAndClick('//div[contains(@class,"FilterLabelNewDesign_selected")]//span[contains(.,"'.$rndFilter.'")]', 'filter');
        $I->waitForElementNotVisible('//div[contains(@class,"FilterLabelNewDesign_selected")][contains(.,"'.$rndFilter.'")]');
    }

    /**
     * Проверка работы фильтра из блока full filter. Выбор, а затем отмена фильтра.
     *
     * @throws \Exception
     */
    public function checkSelectFullFilterSection()
    {
        $rndFilter = $this->selectFullFilterSection();
        $this->unSelectFullFilterSection($rndFilter);
    }

    /**
     * Получить листинг товаров.
     *
     * @return \string[] Листинг товаров
     */
    public function getProductsListingArray()
    {
        $I = $this->tester;

        $arrayProductName = $I->grabMultipleDesc(self::GRID_PRODUCTS_TITLE, 'products listing');
        codecept_debug($arrayProductName);
        $I->seeProductsListingNotEmpty($arrayProductName);

        return $arrayProductName;
    }

    /**
     * Получить кол-во товаров не в наличии.
     *
     * @return int Кол-во товаров не в наличии.
     */
    public function checkListingOutOfStock()
    {
        $I = $this->tester;

        $outOfStockItems = $I->getNumberOfElements('//div[contains(@class,"ProductCardVertical_not-available")]',
            'out of stock items');

        return $outOfStockItems;
    }

    /**
     * Переход в карточку товара в наличии.
     *
     * @throws \Exception
     */
    public function goToProductInStockFromListing()
    {
        $I = $this->tester;

        $I->waitAndClick(self::XPATH_PRODUCT_TABLE . self::XPATH_PRODUCT_ITEM . '//button[@class="add_to_cart pretty_button type4 add_to_cart_text_for_user"]/../../../..//span/a[@class="link_gtm-js link_pageevents-js ddl_product_link"]',
            'products in stock');
    }

    /**
     * Переход в карточку товара, для которой есть клубная цена.
     *
     * @return mixed Id карточки товара
     * @throws \Exception
     */
    public function goToProductWithClubPrice()
    {
        $I = $this->tester;

        $I->waitAndClick('//div[contains(@class,"ProductPrice_club")]/../../../..//div[@class="ProductCardVertical__name"]',
            'products with club price');
        $itemId = $I->getItemIdFromProductPage();

        return $itemId;
    }

    /**
     * Проверка наличия клубных цен в листинге.
     *
     * @return int $clubPriceCount количество клубных цен в листинге
     */
    public function checkClubPriceAvailability()
    {
        $I = $this->tester;

        $clubPriceCount = $I->getNumberOfElements('//div[contains(@class,"ProductPrice_club")]');

        return $clubPriceCount;
    }

    /**
     * Выбор последней страницы пагинации.
     *
     * @param string $path Xpath листинга товаров
     * @throws \Exception
     */
    public function selectLastPaginationPage($path)
    {
        $I = $this->tester;

        $arrayInitListing = $I->grabMultiple(['xpath' => $path]);
        codecept_debug($arrayInitListing);
        $I->lookForwardTo('check that the last page is selected');
        $I->waitBasketLoader();
        $lastPage = $I->getNumberFromLink('//section//a[contains(@class,"PaginationWidget__page_last")]', "The last page");
        $I->waitAndClick('//section//a[contains(@class,"PaginationWidget__page_last")]', "select last page");
        $I->waitForElementNotVisible('//span[contains(@class,"PaginationWidget__page_current")][contains(.,"'.$lastPage.'")]');
        $I->waitBasketLoader();
        $arrayLastPage = $I->grabMultiple(['xpath' => $path]);
        codecept_debug($arrayLastPage);
        if($arrayLastPage == $arrayInitListing){
            Throw new \Exception('List of products was not changed');
        }
    }

    /**
     * Получение всех товаров из списка на странице и проверка на соответствие.
     *
     * @param string$categoryName название категории
     * @throws \Exception если имеется товар не из данной категории
     */
    public function getItemsFromListing($categoryName = null)
    {
        $I = $this->tester;

        $categoryName = strstr($categoryName. ' ', ' ', true);
        $numbersOfProduct = $I->getNumberOfElements(self::XPATH_PRODUCT_ITEM);
        $categories = [];

        for ($i = 0; $i <= $numbersOfProduct; $i++) {
            $ProductsAttribute = $I->grabAttributeFrom(self::XPATH_PRODUCT_ITEM .'['.$i.']', 'data-params');
            array_push($categories, $ProductsAttribute);
        }

        foreach ($categories as $product){
            if (!stripos($product, $categoryName)){
                Throw new \Exception('List of products has another categories');
            }
        }
    }

    /**
     * Получение всех товаров из списка на странице и проверка наличия плашки "уценка".
     *
     * @throws \Exception если у товара в листинге нет уценки
     */
    public function checkItemsForDiscountFromListing()
    {
        $I = $this->tester;

        $numbersOfProduct = $I->getNumberOfElements(self::GRID_PRODUCTS);
        $numbersOfDiscount = $I->getNumberOfElements('//span[contains(.,"УЦЕНКА")]');

        if ($numbersOfProduct != $numbersOfDiscount){
            codecept_debug($numbersOfProduct);
            codecept_debug($numbersOfDiscount);

            Throw new \Exception('some product dont have discount');
        }
    }

    /**
     * Получение id товара.
     *
     * @param int $sectionNum Порядковый номер секции в листинге
     * @param int $itemNum Порядковый номер товара в секции листинга
     * @return string $itemId id товара из листинга
     * @throws \Exception
     */
    public function getItemId($sectionNum, $itemNum)
    {
        $I = $this->tester;

        if ($sectionNum == null) {
            $itemData = $I->getRandomItemNumberInListing();
            $sectionNum = $itemData[0];
            $itemNum = $itemData[1];
        }

        $itemId = $I->grabAttributeFrom(self::GRID_SECTIONS . '[' . $sectionNum . ']/div['. $itemNum .']', 'data-product-id');

        return $itemId;
    }

    /**
     * Переход на страницу товара из ротатора "популярные товары в категории".
     *
     * @return string $itemId id товара из листинга
     * @throws \Exception
     */
    public function goToProductCartFromListingRotator()
    {
        $I = $this->tester;

        $itemId = $I->grabAttributeFrom('//div[contains(@class,"Subcategory__popular-items")]'.Home::XPATH_PRODUCT_CARD, 'data-product-id');
        $I->waitAndClick('//div[contains(@class,"Subcategory__popular-items")]'.Home::XPATH_PRODUCT_CARD.'//a', 'click on product');
        $itemIdOnProductPage = $I->getItemIdFromProductPage();
        $I->seeProductIdEquals($itemId, $itemIdOnProductPage);

        return $itemId;
    }

    /**
     * Получение номера случайного товара.
     *
     * @param string $info переход в КТ с отзывом, обзором или рейтингом
     * @return array $itemData номер секции и порядковый номер товара
     * @throws \Exception
     */
    public function getRandomItemNumber($info)
    {
        $I = $this->tester;

        $itemData = [];
        $I->waitForElementVisible('//div[contains(@class,"ProductCardCategoryList__products-container")]');
        $sections = $I->getNumberOfElements(self::GRID_SECTIONS.'[contains(.,"корзину")][contains(.,"'. $info .'")]');
        $section = mt_rand(1, $sections);
        $items = $I->grabMultipleDesc(self::GRID_SECTIONS.'['.$section.']' . Home::XPATH_PRODUCT_CARD.'[contains(.,"'. $info .'")]',
            'products in grid');
        codecept_debug($items);
        $itemNum = mt_rand(1, count($items));
        array_push($itemData, $section);
        array_push($itemData, $itemNum);

        return $itemData;
    }

    /**
     * Получение точек наличия товара.
     *
     * @param string $stockXpath путь к списку наличия
     * @return array $itemStockStores точки наличия товара
     * @throws \Exception
     */
    public function getProductStockStores($stockXpath)
    {
        $I = $this->tester;

        $I->waitAndClick($stockXpath, 'open stores list');
        $itemStockStores = $I->grabMultiple('//div[@data-popup="true"]//div[@class="AvailabilityInStoreStoresGroupItem"]');
        $I->seeArrayNotEmpty($itemStockStores);
        $I->waitAndClick('//div[contains(@class,"Popup__show-popup")]//button[@class="Popup__close"]',
            'close stores list');

        return $itemStockStores;
    }

    /**
     * Изменение диапазона цен.
     *
     * @param string $minPrice минимальная цена
     * @param string $maxPrice максимальная цена
     * @throws \Exception
     */
    public function changePriceRange($minPrice, $maxPrice)
    {
        $I = $this->tester;

        $I->waitForElementVisible(['class' => 'FilterRange']);
        if ($minPrice !== null) {
            $I->pressKey(['name' => 'input-min'], array('ctrl', 'a'), \WebDriverKeys::DELETE);
            $I->fillField(['name' => 'input-min'], $minPrice);
        }

        if ($maxPrice !== null) {
            $I->pressKey(['name' => 'input-max'], array('ctrl', 'a'), \WebDriverKeys::DELETE);
            $I->fillField(['name' => 'input-max'], $maxPrice);
        }

        $I->waitAndClick('//h1', 'click to heading');
        $I->waitForFilterEnd();
    }

    /**
     * Проверка SEO фильтров.
     *
     * @throws \Exception Если выбранный фильтр не соответсвует бренду в хлебных крошках.
     */
    public function checkSeoFilter()
    {
        $I = $this->tester;

        $I->lookForwardTo('SEO filters');
        $arrayBrand = $I->grabMultipleDesc(self::SEO_FILTERS, 'SEO filters');
        codecept_debug($arrayBrand);
        $I->seeArrayNotEmpty($arrayBrand);
        $number = count($arrayBrand);
        codecept_debug('number of SEO filters ' . $number);
        $listingProducts = [];
        foreach ($arrayBrand as $key => $value) {
            codecept_debug('brand from array is ' . $value . ' initial');
            $I->scrollTo(self::SEO_FILTERS . '[contains(.,"'. $value .'")]', 0, -100);
            $I->waitAndClick(self::SEO_FILTERS . '[contains(.,"'. $value .'")]', 'SEO filter', true);
            codecept_debug('SEO filter clicked is "' . $value . '"');
            $I->wait(SHORT_WAIT_TIME);
            $listingProductsAfterFilter = $I->grabMultipleDesc(self::GRID_PRODUCTS_TITLE, 'listing items');
            codecept_debug('listing products after filter');
            codecept_debug($listingProductsAfterFilter);
            $I->seeProductsListingNotEquals($listingProducts, $listingProductsAfterFilter);
            $listingProducts = $listingProductsAfterFilter;
        }
    }

    /**
     * Проверка и переключение типа отображения листинга.
     *
     * @param string $view Тип отображения list, grid
     * @throws \Exception
     */
    public function checkListingViewIs($view)
    {
        $I = $this->tester;

        $viewType = $I->grabAttributeFrom('//label[contains(@class,"ProductCardCategoryList__icon-active")]', 'data-view-type');
        codecept_debug($viewType);
        if($viewType != $view){
            $I->waitAndClick('//div[@class="ProductCardCategoryList__view-type"]/label[@data-view-type="'. $view .'"]',
                'switch to ' . $view . ' view', true);
            $I->waitForElement('//label[contains(@class,"ProductCardCategoryList__icon-active")][@data-view-type="'. $view .'"]');
            $I->wait(SHORT_WAIT_TIME);
        }
    }

    /**
     * Переход в сравнение из Листинга
     *
     * @throws \Exception
     */
    public function goToCompareProductLink()
    {
        $I = $this->tester;

        $I->waitAndClick('//div[contains(@class,"HeaderMenu__button_compare")]', 'go to Compare');
        $I->waitForElementVisible('//h2[contains(.,"Сравнение товаров")]');
    }

    /**
     * Добавление в сравнение из Листинга.
     *
     * @param int $param номер товара в листинге для добавления
     * @throws \Exception
     */
    public function addToCompareFromListing($param)
    {
        $I = $this->tester;

        $I->lookForwardTo("add item " . $param . " to compare");
        $I->waitAndClick(Home::XPATH_PRODUCT_CARD . '[' . $param . ']//label[contains(@class,"js--AddToComparison")]',
            'add to compare button');
        $I->waitForElementVisible(Home::XPATH_PRODUCT_CARD . '[' . $param . ']//span[contains(@class,"ProductCardButton__icon_active")]');
    }

    /**
     * Получить количество товаров с информацией по типу рейтинг\отзыв\обзор.
     *
     * @param string $info переход в КТ с отзывом, обзором или рейтингом
     * @return int $itemsCount количество товаров
     * @throws \Exception
     */
    public function getProductsCountWithInfo($info)
    {
        $I = $this->tester;

        $I->waitForElementVisible('//div[contains(@class,"ProductCardCategoryList__products-container")]');
        $itemsCount = $I->getNumberOfElements(Home::XPATH_PRODUCT_CARD.'[contains(.,"'. $info .'")]');

        return $itemsCount;
    }

    /**
     * Ожидание начала и завершения загрузки BasketLoader.
     *
     * @throws \Exception
     */
    public function waitBasketLoader()
    {
        $I = $this->tester;

        $I->waitForElementNotVisible('//div[@class="BasketPreloader"][@style="display: none;"]');
        $I->waitForElementNotVisible('//div[@class="BasketPreloader"][@style="display: block;"]');
    }
}
