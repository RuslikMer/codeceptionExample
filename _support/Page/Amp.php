<?php
namespace Page;

class Amp
{
    // include url of current page
    public static $URL = '/amp/catalog/';
    // начало блока с элементами AMP
    const XPATH_AMP_BLOCK = '//div[@class="page-block-amp"]';
    // отдельный товар в листинге AMP
    const XPATH_PRODUCT_ITEM = '//div[@class="subcategory-product-item"]';
    // подкатегория
    const XPATH_SUBCATEGORY = '//div[contains(@class,"category-list__item")]';

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
     * Переход в карточку товара из листинга.
     *
     * @return null|string|string[] Id товара
     * @throws \Exception
     */
    public function goToAmpProductPage()
    {
        $I = $this->tester;

        $numberOfProductsInListing = $I->getNumberOfElements(self::XPATH_AMP_BLOCK . Amp::XPATH_PRODUCT_ITEM,"products in listing");
        $number = mt_rand(1, $numberOfProductsInListing);
        $I->waitAndClick(self::XPATH_AMP_BLOCK . self::XPATH_PRODUCT_ITEM . '[' . $number .']/div[@class="subcategory-product-item__body"]//a', 'product in listing', true);
        $itemId = preg_replace("/[^0-9]/", "", $I->grabFromCurrentUrl());
        codecept_debug('Product id: ' . $itemId);

        return $itemId;
    }

    /**
     * Проверка обязательных элементов на страницах сайта.
     *
     * @throws \Exception
     */
    public function checkMandatoryElements()
    {
        $I = $this->tester;

        $I->checkElementOnPage(['class' => 'logo'], 'citilink logo');
    }

    /**
     * Переход к листингу товаров из каталога.
     *
     * @param null $param Категория
     * @param null $subCat Подкатегория
     * @param null $lowCat Вторая подкатегория
     * @return mixed
     * @throws \Exception
     */
    public function goToAmpListing($param = null, $subCat = null, $lowCat = null)
    {
        $I = $this->tester;

        $I->lookForwardTo('**** click to amp category: first parameter is ' . $param . ', second is ' . $subCat . ' and third is ' . $lowCat);

        //если категория не задана, заходим в случайную категорию
        if (is_null($param)) {
            $I->lookForwardTo("use random category");
            $param = mt_rand(1, 11);
        }

        $I->waitAndClick(self::XPATH_AMP_BLOCK . '/div[@class="category-list"]/div[@class="category-list__item"][' . $param . ']/a',
            'amp category ' . $param . ' from amp catalog', true);

        do {
            if (is_null($subCat)) {
                $I->lookForwardTo("use random subcategory");
                $rndSub = $I->getNumberOfElementsClear(self::XPATH_AMP_BLOCK . self::XPATH_SUBCATEGORY, "subcategory");
                $subCat = mt_rand(1, $rndSub);
            }
            $I->waitAndClick(self::XPATH_AMP_BLOCK . self::XPATH_SUBCATEGORY . '[' . $subCat . ']',
                "subcategory " . $subCat, true);
            $I->wait(SHORT_WAIT_TIME);
            $numberOfProductsInListing = $I->getNumberOfElements(self::XPATH_AMP_BLOCK . self::XPATH_PRODUCT_ITEM,
                "products in listing");

            if ($numberOfProductsInListing == 0) {
                if (!is_null($lowCat)) {
                    do {
                        if (is_null($lowCat)) {
                            $I->lookForwardTo("use random subcategory");
                            $rndSub = $I->getNumberOfElementsClear(self::XPATH_AMP_BLOCK . self::XPATH_SUBCATEGORY,
                                "subcategory");
                            $lowCat = mt_rand(1, $rndSub);
                        }

                        $I->waitAndClick(self::XPATH_AMP_BLOCK . self::XPATH_SUBCATEGORY . '[' . $lowCat . ']/div[@class="category-list__item"]',
                            "subcategory " . $lowCat, true);
                        $lowCat = null;
                        $numberOfProductsInListing = $I->getNumberOfElements(self::XPATH_AMP_BLOCK . self::XPATH_PRODUCT_ITEM,
                            "products in listing");
                    } while ($numberOfProductsInListing == 0);
                }
                $subCat = null;
                //проверяем на наличие листинга товаров, иначе проваливаемся еще ниже
            }
        } while ($numberOfProductsInListing == 0);

        //убеждаемся в наличии кнопок листинга - добавление товаров в сравнение
        $I->checkElementOnPage(self::XPATH_AMP_BLOCK . self::XPATH_PRODUCT_ITEM, "check products in listing");
        $selectedCategoryName = $I->grabTextFrom(['xpath' => '//h1[@class="content-page-title"]']);
        $selectedCategoryNameView = $I->translit($selectedCategoryName);
        codecept_debug('Selected category is - '.$selectedCategoryNameView);

        return $selectedCategoryName;
    }

    /**
     * Добавление товара в корзину из карточки товара.
     *
     * @throws \Exception
     */
    public  function addToCartFromAmpProductPage()
    {
        $I = $this->tester;

        $I->lookForwardTo('add to cart from amp product page');
        $I->scrollTo(['xpath' => '//button[@class="button button_full button_buy"]'], 0, -150);
        $I->waitAndClick('//button[@class="button button_full button_buy"]', 'add to cart', true);
        $I->seeCurrentUrlEquals('/order/');
    }

    /**
     * Переход в AMP каталог.
     *
     * @throws \Exception
     */
    public function goToAmpCatalog()
    {
        $I = $this->tester;

        $I->amOnPage('/amp/catalog/');
        $I->checkElementOnPage(['xpath' => '//h1[@class="content-page-title"][contains(.,"Каталог товаров")]']);
    }

    /**
     * Проверка навигации хлебных крошек.
     *
     * @throws \Exception если хлебные крошки не соответсвуют странице
     */
    public function checkAmpNavigation()
    {
        $I = $this->tester;

        $breadCrumbs = $I->getNumberOfElements('//*[@class="navigation-item navigation-item_parent"]');
        if ($breadCrumbs == true) {
            $selectedCategoryName = $I->grabTextFrom('//*[@class="navigation-item navigation-item_parent"]');
            codecept_debug('category name from first page ' . $selectedCategoryName);
            $I->click('//*[@class="navigation-item navigation-item_parent"]');
            $I->checkAmpMandatoryElements();
            $categoryName = $I->grabTextFrom(['class' => 'content-page-title']);
            codecept_debug('category name from second page ' . $categoryName);

            if (stristr($selectedCategoryName, $categoryName) !== false){
                Throw new \Exception('Bread crumbs does not work correctly - category titles are different');
            }
        }
    }

    /**
     * Проверка пагинации в листинге.
     *
     * @throws \Exception
     */
    public function checkListingPagination()
    {
        $I = $this->tester;

        $breadCrumbs = $I->getNumberOfElements(['class' => 'page_listing']);

        if ($breadCrumbs == true) {
            $pagesNumbers = $I->getNumberOfElements(['class' => 'next']);
            $num = mt_rand(1, $pagesNumbers);
            $I->waitAndClick('//div[@class="page_listing"]//li['.($num+1).']', 'select page number in pagination');
            $selectedPage = $I->grabTextFrom('//div[@class="page_listing"]//li[@class="selected"]');
            if (($num+1) !== (int)$selectedPage){
                Throw new \Exception('Pagination does not work correctly');
            }
        }else{
            $I->moveBack();
            $rndSub = $I->getNumberOfElementsClear(self::XPATH_AMP_BLOCK . self::XPATH_SUBCATEGORY);
            $lowCat = mt_rand(1, $rndSub);
            $I->waitAndClick(self::XPATH_AMP_BLOCK . self::XPATH_SUBCATEGORY . '[' . $lowCat . ']/div[@class="category-list__item"]', 'select category');
            $I->checkAmpListingPagination();
        }
    }

    /**
     * Выбор случайного тега в листинге.
     *
     * @return string $tagName название выбранного тега
     */
    public  function selectRandomTag()
    {
        $I = $this->tester;

        $I->lookForwardTo('select tag');
        $numberOfSliders = $I->getNumberOfElements('//ul[@class="tags-slider__slider-track"]');
        $slideNum = mt_rand(1, $numberOfSliders);
        $numbersOfTags = $I->getNumberOfElements('//ul[@class="tags-slider__slider-track"]['.$slideNum.']/span');
        $tagNum = mt_rand(1, $numbersOfTags);
        $tagName = $I->grabTextFrom('//ul[@class="tags-slider__slider-track"]['.$slideNum.']/span['.$tagNum.']');
        $I->click('//ul[@class="tags-slider__slider-track"]['.$slideNum.']/span['.$tagNum.']');

        return $tagName;
    }

    /**
     * Получение всех товаров из списка на странице и проверка на соответствие.
     *
     * @param string $subCategory название подкатегории
     * @throws \Exception если имеется товар не из данной подкатегории
     */
    public function checkItemsCategoryFromAmpListing($subCategory)
    {
        $I = $this->tester;

        $numbersOfProduct = $I->getNumberOfElements('//div[@class="subcategory-product-item__product-name"]');
        $subCategories = [];

        for ($i = 1; $i <= $numbersOfProduct; $i++) {
            $ProductsAttribute = $I->grabTextFrom(self::XPATH_PRODUCT_ITEM . '['.$i.']//div[@class="subcategory-product-item__product-name"]');
            array_push($subCategories, $ProductsAttribute);
        }

        foreach ($subCategories as $product){
            if (!stripos($product, $subCategory)){
                Throw new \Exception('List of products has another categories');
            }
        }
    }
}
