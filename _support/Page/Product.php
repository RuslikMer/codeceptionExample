<?php
namespace Page;

class Product
{
    // include url of current page
    public static $URL = '/';
    //путь к правому блоку кнопок
    const PRODUCT_BUTTON_ADD_TO_CART = '//button[contains(@class,"ProductHeader__add-to-cart")]';
    //блок закладок в КТ
    const XPATH_PRODUCT_TABS = '//div[@class="Tabs js--Tabs"]';
    //Выбор ротатора
    const XPATH_ROTATOR_RECENTLY = '//div[contains(@class,"Container Container_has-grid")][contains(.,"История просмотров")]'; //недавно смотрели
    const XPATH_DIGITAL_SERVICE = '//ul[@class="service-list"]/li[@class="element_service__js"]';
    const XPATH_DIGITAL_SERVICE_NAME = '//span[@class="service-name"]';
    //блок с ценой на цифровую услугу
    const XPATH_DIGITAL_SERVICE_PRICE = '//span[not(contains(@class, "club-price"))]/ins[@class="num"]';
    //путь к блокам сертификатов
    const XPATH_CERTIFICATES = '//div[@class=" Documentation"]';
    //путь к списку точек наличия товара
    const XPATH_STOCKS_LIST = '//div[@class="ProductHeader__link-inner" and contains(.,"В наличии")]';
    //клубная цена
    const XPATH_CLUB_PRICE = '//div[contains(@class,"ProductHeader__club-price-title")]';
    //поле количества товара
    const XPATH_QUANTITY = '//input[@class="CountSelector__input js--CountSelector__input"]';
    //услуги установки
    const XPATH_INSTALL_SERVICE = '//div[@class=" AdditionalServices js--AdditionalServices"]//section[@class="AdditionalServices__item"]';
    const CARDIF_SERVICE = '//label[@class="Checkbox AdditionalServices__input"]';
    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    public static $installServiceName = self::XPATH_INSTALL_SERVICE . self::CARDIF_SERVICE;
    public static $installServicePrice = self::XPATH_INSTALL_SERVICE.'//span[@class="AdditionalServices__price-block_current-price"]';
    public static $installServiceCheckBox = self::XPATH_INSTALL_SERVICE.'//div[@class="AdditionalServices__checkbox"]';

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
     * Проверка страницы товара - наличие блока с экстрабонусами.
     *
     * @throws \Exception
     */
    public function checkExtraBonusCard()
    {
        $I = $this->tester;

        $I->lookForwardTo("check product page content");
        $I->checkElementOnPage('//div[contains(@class,"standart_price")]/p[@class="bonus bonus_extra_count"]', 'extra bonuses quantity');
    }

    /**
     * Переход в корзину из карточки товара по большой изменяемой кнопке Добавить в корзину -> Оформить заказ.
     *
     * @throws \Exception
     */
    public function goToCartFromProductBigButton()
    {
        $I = $this->tester;

        $I->lookForwardTo("click big button");
        $I->waitAndClick(self::PRODUCT_BUTTON_ADD_TO_CART . '[contains(.,"Оформить")]',
            "link to cart - big button");
    }

    /**
     * Переход в корзину из карточки товара по кнопке "в корзину".
     *
     * @throws \Exception
     */
    public function goToCartFromProductSmallButton()
    {
        $I = $this->tester;

        $I->lookForwardTo("click small button, in cart");
        $I->waitAndClick(self::PRODUCT_BUTTON_ADD_TO_CART . '/div[@class="remove_from_cart_container remove_container no_visited"]/a',
            "small button, in cart");
    }

    /**
     * Получение рейтинга товара с карточки товара
     *
     * @return string Рейтинг в условном формате (0,1,2,3,4,5) -> (5,4,3,2,1,0)
     */
    public function getRateFromProductPage()
    {
        $I = $this->tester;

        $starProduct = $I->getNumberFromLink('//div[@class="ProductHeader__icon-count"]/div[1]', 'rate');
        codecept_debug($starProduct);

        return $starProduct;
    }

    /**
     * Добавить в желания.
     *
     * @throws \Exception
     */
    public function addToWishList()
    {
        $I = $this->tester;

        $I->lookForwardTo('add to wish list');
        $I->waitAndClick('//button[contains(@class,"js--AddToWishList")]', 'add to wish list');
        $I->waitForElementVisible('//button[@class="ProductHeader__color-sign-item js--AddToWishList IconInRound js--IconInRound active"]');
    }

    /**
     * Перейти в желания из верхнего правого блока.
     *
     * @throws \Exception
     */
    public function goToWishListTopRight()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to wish list from top right');
        $I->waitAndClick('//div[contains(@class,"HeaderMenu__button_wishlist")]', 'button-icon wish list');
        $I->checkElementOnPage('//h2[@class="WishListLayout__title"][contains(.,"Избранное")]', 'wish list page');
    }

    /**
     * Переход в карточку товара из ротатора История просмотров в КТ.
     *
     * @throws \Exception
     */
    public function goToProductFromRecentlyRotatorPicture()
    {
        $I = $this->tester;

        $productsCount = $I->getNumberOfElements(self::XPATH_ROTATOR_RECENTLY . Home::XPATH_RICH_PRODUCT_CARD, 'products in rotator');
        codecept_debug($productsCount);
        $productNumber = mt_rand(1 , $productsCount);
        if ($productNumber >= 5){
            $productNumber = 4;
        }

        $I->waitAndClick(self::XPATH_ROTATOR_RECENTLY . Home::XPATH_RICH_PRODUCT_CARD.'[' . $productNumber . ']',
            'go to product from rotator');
    }

    /**
     * Выбор вкладки Вопросы-Ответы в карточке товара.
     *
     * @return int $answers наличие ответов
     * @throws \Exception
     */
    public function selectAnswerPage()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to Question-Answer Page');
        $I->waitAndClick(self::XPATH_PRODUCT_TABS . '//li[@data-tabname="faqs"]', 'Question-Answer Page');
        $I->checkElementOnPage(self::XPATH_PRODUCT_TABS . '//li[@data-tabname="faqs"][contains(@class,"active")]', 'check select Question-Answer');
        $I->checkElementOnPage(['class' => 'Comments__question'], 'new question box');
        $answers = $I->getNumberOfElements(['class' => 'DiscussionItfem']);

        return $answers;
    }

    /**
     * Ответ на вопрос  во вкладке Вопросы-Ответы в карточке товара.
     *
     * @param $param string текст ответа
     * @throws \Exception
     */
    public function answerForQuestion($param)
    {
        $I = $this->tester;

        $I->lookForwardTo('answer question');
        $I->waitAndClick('//button[contains(@class,"js--DiscussionItem__answer")]', 'answer question form');
        $I->waitAndFill(['class' => 'Comments__textarea'], 'answer field', $param);
        $I->waitAndClick(['name' => 'submit'], 'answer question form');
    }

    /**
     * Переход в профиль экспертов из Вопросов-Ответов в карточке товара.
     *
     * @throws \Exception
     */
    public function goToExpertProfile()
    {
        $I = $this->tester;

        $I->lookForwardTo('grab users list');
        $answers = $I->getNumberOfElements(['class' => 'DiscussionItem__head']);
        if($answers == 0){
            Throw new \Exception('Answers not found');
        }

        $arrayExperts = $I->grabMultipleDesc('//div[@class="DiscussionItem__head"]//a', 'grab experts', 'href');
        codecept_debug($arrayExperts);
        $expertRnd = array_rand($arrayExperts, 1);
        $I->amOnUrl($arrayExperts[$expertRnd]);
    }

    /**
     * Проверка карточки товара Не в наличии.
     *
     * @throws \Exception
     */
    public function checkOutOfStockProduct()
    {
        $I = $this->tester;
        $I->lookForwardTo('check out of stock product');
        $I->checkElementOnPage(['class' => 'ProductHeader__not-available']);
    }

    /**
     * Очистка списка желаний.
     *
     * @throws \Exception
     */
    public function wishListCleanUp()
    {
        $I = $this->tester;

        $I->lookForwardTo("check wishlist and wipe it if needed");
        $I->goToWishListTopRight();
        $isEmptyWishList = $I->getNumberOfElements('//div[@class=""]//div[@class="FavouritesAdvantagesItem"][contains(.,"Список очищен")]');
        if($isEmptyWishList == 0){
            $I->waitAndClick('//button[contains(@class,"button-remove-all")]', "clean up wishlist button", true);
        }

        $I->openHomePage();
    }

    /**
     * Добавление товара в корзину - кнопками в правом блоке.
     *
     * @param bool $close закрыть всплывающую корзину
     * @throws \Exception
     */
    public function addToCartFromProductPage($close)
    {
        $I = $this->tester;

        $I->lookForwardTo("**** add to cart from product page");
        $I->waitAndClick(self::PRODUCT_BUTTON_ADD_TO_CART , "buy button");
        if ($close == true){
            $I->continueShopping();
            $I->lookForwardTo("check for link");
            $I->checkElementOnPage(self::PRODUCT_BUTTON_ADD_TO_CART . '[contains(.,"Оформить")]', "link to cart");
        }
    }

    /**
     * Добавление товара в корзину - кнопками в правом блоке (для b2b).
     *
     * @param bool $close закрыть всплывающую корзину
     * @throws \Exception
     */
    public function addToCartFromProductPageB2b($close)
    {
        $I = $this->tester;

        $I->lookForwardTo("**** add to cart from product page");
        $I->waitAndClick(self::PRODUCT_BUTTON_ADD_TO_CART , "buy button");
        if ($close == true){
            $I->lookForwardTo("check for link");
            $I->checkElementOnPage(self::PRODUCT_BUTTON_ADD_TO_CART . '[contains(.,"Оформить")]', "link to cart");
        }
    }

    /**
     * Получение Id со страницы товара.
     *
     * @return mixed
     */
    public function getItemId()
    {
        $I = $this->tester;

        $I->lookForwardTo('grab id from product page');
        $itemId = $I->grabAttributeFrom('//span[@itemprop="sku"]', 'content');
        codecept_debug('item id '.$itemId);

        return $itemId;
    }

    /**
     * Выбор вкладки "Отзывы" в карточке товара.
     *
     * @throws \Exception
     */
    public function selectOpinionPage()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to opinion product page');
        $I->waitAndClick(self::XPATH_PRODUCT_TABS . '//li[@data-tabname="opinions"]', 'Opinion Page');
        $I->checkElementOnPage(self::XPATH_PRODUCT_TABS . '//li[@data-tabname="opinions"][contains(@class,"active")]', 'check select Opinion');
    }

    /**
     * Открыть форму добавление нового отзыва.
     *
     * @throws \Exception
     */
    public function selectNewOpinion()
    {
        $I = $this->tester;

        $I->lookForwardTo('add new opinion product page');
        $I->waitAndClick('//span[contains(@class,"js--OpinionsRating__button")]', 'new opinion button');
        $I->checkElementOnPage('//div[contains(@class,"ps--active-y")]//div[@class="js--OpinionsAddForm OpinionsAddForm"]',
            'opinion form');
    }

    /**
     * Добавить новый отзыв.
     *
     * @param string $param новый отзыв
     * @throws \Exception
     */
    public function addNewOpinion($param)
    {
        $I = $this->tester;

        $I->lookForwardTo('add new opinion product page');
        $I->waitAndClick('//div[@class="OpinionsAddForm"]//label[@for="rating_5"]', 'select rating');
        $I->waitAndFill(['name' => 'nickname'], 'new opinion name', 'TEST');
        $I->waitAndFill(['name' => 'body'], 'new opinion comment', $param);
        $I->waitAndClick(['id' => 'addOpinion'], 'send opinion');
    }

    /**
     * Открыть форму "Пожаловаться" на комментарий в вопрос-ответ.
     *
     * @throws \Exception
     */
    public function openAnswerCommentReportForm()
    {
        $I = $this->tester;

        $I->lookForwardTo('open comment report form');
        $I->waitAndClick('//button[contains(@class,"DiscussionItem__button_report")]', 'report button');
        $I->checkElementOnPage('//form[@class="ReportForm__form"]//h3[contains(.,"Причина жалобы")]', 'pop-up header');
    }

    /**
     * Проверка наличия.
     *
     * @throws \Exception Если не в наличии
     */
    public function checkProductInStock()
    {
        $I = $this->tester;

        $available = $I->getNumberOfElements(['class' => 'ProductHeader__not-available']);
        if($available != 0){
            Throw new \Exception('Now is '. $available );
        }
    }

    /**
     * Выбор вкладки "Услуги" в карточке товара.
     *
     * @throws \Exception
     */
    public function selectServicesPage()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to services product tab');
        $I->waitAndClick(self::XPATH_PRODUCT_TABS . '//li[@data-tabname="services"]', 'Services Page');
        $I->checkElementOnPage(self::XPATH_PRODUCT_TABS . '//li[@data-tabname="services"][contains(@class,"active")]', 'check select Services');
    }

    /**
     * Получение списка магазинов "В наличии сейчас".
     *
     * @return array Список магазинов
     * @throws \Exception
     */
    public function getStoresInStock()
    {
        $I = $this->tester;

        $I->waitAndClick('//div[@class="ProductHeader__link-inner"][contains(.,"получить сегодня")]', 'open stores list');
        $I->waitForElementVisible('//div[@class="AvailabilityInStoreStoresGroupItem"]');
        $storeList = $I->grabMultipleDesc('//div[@class="AvailabilityInStoreStoresGroupItem"]//div[@class="AvailabilityInStoreStoresGroupItem__title"]',
            'Store list - in stock');
        $I->seeArrayNotEmpty($storeList);

        return $storeList;
    }

    /**
     * Выбор пункта выдачи из списка "В наличии сейчас".
     *
     * @throws \Exception
     */
    public function selectStoreInStock()
    {
        $I = $this->tester;

        $storeList = $this->getStoresInStock();
        $store = $storeList[array_rand($storeList)];

        $I->waitAndClick("//div[@class='AvailabilityInStoreStoresGroupItem']//div[@class='AvailabilityInStoreStoresGroupItem__title'][contains(., '".$store."')]",
            'select pick point');
        $I->moveMouseOver("//div[@class='AvailabilityInStoreStoresGroupItem'][contains(., '".$store."')]//a");
        $I->waitAndClick("//div[@class='AvailabilityInStoreStoresGroupItem'][contains(., '".$store."')]//a",
            'go to pick point', true);
        $I->switchToNextTab();
        $I->waitForElementVisible('//h1');
        $storeName = $I->grabTextFrom('//h1');
        $search  = array('«', '»');
        $storeName = str_replace($search, '"', $storeName);
        $I->seeValuesAreEquals($store, $storeName);
    }

    /**
     * Получение обычной цены в карточке товара.
     *
     * @return int Обычная цена товара
     */
    public function getStandardProductPrice()
    {
        $I = $this->tester;

        $standardProductPrice = $I->getNumberFromLink(['class' => 'ProductHeader__price-default_current-price'], 'standard product price');
        $I->seePriceNotEmpty($standardProductPrice);

        return $standardProductPrice;
    }

    /**
     * Получение старой цены в карточке товара.
     *
     * @return int $oldProductPrice старая цена товара
     */
    public function getOldProductPrice()
    {
        $I = $this->tester;

        $oldProductPrice = $I->getNumberFromLink(['class' => 'ProductHeader__price-old_current-price'], 'old product price');
        $I->seePriceNotEmpty($oldProductPrice);

        return $oldProductPrice;
    }

    /**
     * Получение размера уценки в карточке товара.
     *
     * @return int $discountCount размер уценки товара
     */
    public function getProductDiscountCount()
    {
        $I = $this->tester;

        $discountCount = $I->getNumberFromLink('//div[@class="line-block club_price"]//div[@class="explain"]', 'product discount count');
        $I->seePriceNotEmpty($discountCount);

        return $discountCount;
    }

    /**
     * Получение клубной цены товара.
     *
     * @return int $clubProductPrice Клубная цена товара
     * @throws \Exception
     */
    public function getClubProductPrice()
    {
        $I = $this->tester;

        $I->waitForElementVisible(self::XPATH_CLUB_PRICE);
        $clubProductPrice = $I->getNumberFromLink(self::XPATH_CLUB_PRICE, 'club price');
        $I->seePriceNotEmpty($clubProductPrice);

        return $clubProductPrice;
    }

    /**
     * Проверка, что отображаемая клубная цена меньше обычной.
     *
     * @return int $clubProductPrice Клубная цена
     * @throws \Exception
     */
    public function checkClubPrice()
    {
        $I = $this->tester;

        $standardProductPrice = $this->getStandardProductPrice();
        $clubProductPrice = $this->getClubProductPrice();
        $I->seeClubPriceLessThanStandard($standardProductPrice, $clubProductPrice);

        return $clubProductPrice;
    }

    /**
     * Проверка цены для участника клуба. В том числе, что не отображается цена с короной.
     *
     * @param $nonAuthClubPrice
     */
    public function checkAuthClubPrice($nonAuthClubPrice)
    {
        $I = $this->tester;

        $I->dontSeeElement(self::XPATH_CLUB_PRICE);
        $standardProductPrice = $this->getStandardProductPrice();
        $I->seeClubPriceEqual($standardProductPrice, $nonAuthClubPrice);
    }

    /**
     * Выбор вкладки Обзоры в карточке товара.
     *
     * @throws \Exception
     */
    public function selectReviewsPage()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to Reviews Page');
        $I->waitAndClick(self::XPATH_PRODUCT_TABS . '//li[@data-tabname="reviews"]', 'Reviews Page');
        $I->checkElementOnPage(self::XPATH_PRODUCT_TABS . '//li[@data-tabname="reviews"][contains(@class,"active")]', 'check select Reviews');
        $I->checkElementOnPage(['id' => 'addReviewPopup'], 'new review box');
    }

    /**
     * Переход к новости из обзора, а затем к списку новостей.
     *
     * @throws \Exception
     */
    public function readNewsAsReview()
    {
        $I = $this->tester;

        $I->lookForwardTo('open News as Reviews');
        $I->waitAndClick(['class' => 'ReviewShort'], 'select news');
        $I->waitForElementVisible(['class' => 'NewsInnerCardLayout__header']);
        $I->waitAndClick('//span[@class="NewsMenu__item"]/a', 'go to news list');
    }

    /**
     * Открыть форму создания обзора.
     *
     * @throws \Exception
     */
    public function startReview()
    {
        $I = $this->tester;

        $I->waitAndClick('//span[contains(.,"Добавить обзор")]', 'add review button');
        $I->waitForElementVisible('//div[@data-popup="true"][contains(.,"Обзор на товар")]');
    }

    /**
     * Добавление комплекта товаров в корзину.
     *
     * @throws \Exception
     */
    public function addItemsSetToCart()
    {
        $I = $this->tester;

        $I->waitAndClick('//span[@class="Button__text ProductsKitPriceBlock__button__text"]', 'add kit to cart');
    }

    /**
     * Получение Id товара из случайного блока комплектов со страницы товара.
     *
     * @return array $array массив id товаров
     */
    public function getRandomItemsSetId()
    {
        $I = $this->tester;
        $I->lookForwardTo('grab id from items set from product page');
        $mainItemId = $I->getItemIdFromProductPage();
        $array = [];
        array_push($array, $mainItemId);
        $id = $I->grabAttributeFrom('//div[@class="ProductCardForKits"]', 'data-productid');
        array_push($array, $id);

        return $array;
    }

    /**
     * Переход в корзину из комплекта товаров по кнопке "в корзине".
     *
     * @throws \Exception
     */
    public function goToCartFromItemsSetButton()
    {
        $I = $this->tester;

        $I->lookForwardTo("click items set button, in cart");
        $I->waitAndClick('//div[@class="item-set__container"]/div[6]/div/a', 'click on cart button');
    }

    /**
     * Получение размера скидки на товар.
     *
     * @return int $discount процент или размер скидки
     */
    public function getItemsDiscount()
    {
        $I = $this->tester;

        $I->lookForwardTo("get percent of discount");
        $I->switchToNextTab();
        $discount = $I->getNumberFromLink('//span[@class="label promo_label"]', 'get discount');
        codecept_debug($discount .'скидка со страницы товара');

        return $discount;
    }

    /**
     * Получение размера скидки на товар.
     *
     * @return int $discountCount процент или размер скидки
     * @throws \Exception
     */
    public function checkProductDiscount()
    {
        $I = $this->tester;

        $I->lookForwardTo("check discount badge ");
        $I->waitForElementVisible('//div[@class=" ProductHeader"][contains(.,"Уценка")]');
        $standardProductPrice = $this->getStandardProductPrice();
        //$oldProductPrice = $this->getOldProductPrice();
        $discountCount = $this->getProductDiscountCount();
        $I->seeValueNotEmpty($discountCount);

        //if($standardProductPrice >= $oldProductPrice){
        //    Throw new \Exception('incorrect discount price' );
        //}

        return $discountCount;
    }

    /**
     * Переход на вкладку "документация".
     *
     * @throws \Exception
     * @return int $certificate наличие сертификата в КТ
     */
    public function goToCertificatesTab()
    {
        $I = $this->tester;

        $certificate = $I->getNumberOfElements('//li[@data-tabname="documents"]');
        if ($certificate != 0) {
            $I->waitAndClick('//li[@data-tabname="documents"]', 'select documents tab');
            $I->waitForElementVisible(self::XPATH_CERTIFICATES . '/a');
        }

        return $certificate;
    }

    /**
     * Скачивание "сертификата".
     *
     * @return string $fileName имя скачиваемго файла
     * @throws \Exception
     */
    public function downloadCertificate()
    {
        $I = $this->tester;

        $documentsCount = $I->getNumberOfElements(self::XPATH_CERTIFICATES.'/a');
        $document = mt_rand(1, $documentsCount);
        $fileName = $I->grabTextFrom(self::XPATH_CERTIFICATES.'/a['.$document.']');
        $I->waitAndClick(self::XPATH_CERTIFICATES.'/a['.$document.']', '');
        $I->waitForElementNotVisible('//div[@class="catalog-not-found-page__header"]');

        return $fileName;
    }

    /**
     * Выбор вкладки "Аксессуары".
     *
     * @throws \Exception
     */
    public function selectRelatedProducts()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to Related Products Page');
        $I->waitAndClick(self::XPATH_PRODUCT_TABS . '//li[@data-tabname="accessories"]', 'Related Products Page');
        $I->waitForElementVisible(self::XPATH_PRODUCT_TABS . '//li[@data-tabname="accessories"][contains(@class,"active")]');
        $I->waitForElementVisible(['class' => 'AccesoriesTab__items']);
    }

    /**
     * Проверка категорий во вкладке акссессуров.
     *
     * @throws \Exception
     */
    public function checkVisibilityOfCategoriesInAccessoriesTab()
    {
        $I = $this->tester;

        $categories = $I->getNumberOfElements('//ul[@class="AccesoriesTab__categories"]/li[@data-id]');
        codecept_debug($categories);
        for ($i = $categories; $i >= 1; $i--){
            $I->waitAndClick('//ul[@class="AccesoriesTab__categories"]/li[@data-id]['.$i.']', 'choose category');
            $I->waitForElementVisible('//div[@class="AccesoriesTab__items"]//div[contains(@class,"product_data__pageevents-js")]');
        }
    }

    /**
     * Проверка вкладок.
     *
     * @throws \Exception
     */
    public function checkTabsVisibility()
    {
        $I = $this->tester;

        $tabs = $I->grabTextFrom(self::XPATH_PRODUCT_TABS);
        codecept_debug($tabs);
        if (stristr($tabs,'Услуги') == true){
            $I->selectServicesProductPage();
        }
        if (stristr($tabs,'Обзоры') == true){
            $I->selectReviewsPage();
        }
        if (stristr($tabs,'Отзывы') == true){
            $I->selectOpinionProductPage();
        }
        if (stristr($tabs,'Вопрос') == true){
            $I->selectAnswerPage();
        }
        if (stristr($tabs,'Сопутствующие') == true){
            $I->selectRelatedProducts();
        }
        if (stristr($tabs,'Сертификаты') == true){
            $I->goToCertificatesTab();
        }
    }

    /**
     * Добавление в сравнение.
     *
     * @throws \Exception
     */
    public function addToCompare()
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(@class,"js--AddToComparison")]', 'add to compare');
        $I->waitForElementVisible('//button[contains(@class,"js--AddToComparison IconInRound js--IconInRound active")]');
    }

    /**
     * Удаление из сравнения.
     *
     * @throws \Exception
     */
    public function deleteFromCompare()
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(@class,"js--AddToComparison")]', 'remove from compare');
        $I->waitForElementNotVisible('//button[contains(@class,"js--AddToComparison IconInRound js--IconInRound active")]');
    }

    /**
     * Переход в список сравнения.
     *
     * @throws \Exception
     */
    public function goToCompare()
    {
        $I = $this->tester;

        $I->waitAndClick('//div[contains(@class,"HeaderMenu__button_compare")]', 'go to compare');
        $I->switchToNextTab();
        $I->waitForElementVisible('//div[@class="ComparePage__header"]');
    }

    /**
     * Получить список услуг "защита покупки".
     *
     * @throws \Exception
     */
    public function getInsuranceServices()
    {
        $I = $this->tester;

        $I->waitForElementVisible('//div[@class="UpsaleBasket__info"]//span[@class="Checkbox__text AdditionalServices__input__label"]');
        $names = $I->grabMultiple('//div[@class="UpsaleBasket__info"]//span[@class="Checkbox__text AdditionalServices__input__label"]');
        $I->seeArrayNotEmpty($names);
        $services = array();
        foreach ($names as $name){
            $services[] = array('name' => $name);
        }

        return $services;
    }

    /**
     * Добавление услуг страховки.
     *
     * @param int $serviceNum номер страховки по порядку 0-Комбо, 1-Продление, 2-Защита
     * @return array $service
     * @throws \Exception
     */
    public function addInsuranceServices($serviceNum)
    {
        $I = $this->tester;

        $services = $this->getInsuranceServices();
        $selectedService = $services[$serviceNum]['name'];
        codecept_debug('Selected service');
        codecept_debug($selectedService);
        $xpathService = '//div[@class="UpsaleBasket__info"]//section['.($serviceNum+1).']';
        $xpathServiceDate = $xpathService.'//div[@class="AdditionalServices__select-date"]/label';
        $dates = $I->getNumberOfElements($xpathServiceDate);
        if ($dates > 0){
            $rndDate = mt_rand(1, $dates);
            $servicePrice = $I->grabAttributeFrom($xpathServiceDate.'['.$rndDate.']', 'data-price');
            $serviceId = $I->grabAttributeFrom($xpathServiceDate.'['.$rndDate.']', 'data-id');
            $I->waitAndClick($xpathServiceDate.'['.$rndDate.']', 'select service');
        }else{
            $servicePrice = $I->getNumberFromLink($xpathService.'//span[@class="AdditionalServices__price-block_current-price"]',
                'get available service Price');
            $serviceId = $I->grabAttributeFrom($xpathService.'/div', 'data-id');
            $I->waitAndClick($xpathService.'//div[@class="checkbox-input"]', 'select service');
        }

        $service = array('name' => $selectedService, 'price' => $servicePrice, 'id' => $serviceId);
        codecept_debug($service);

        return $service;
    }

    /**
     * Отправка формы "сообщить о низкой цене".
     *
     * @param int $value цена товара меньшей стоимости
     * @param string $link ссылка на товар меньшей стоимости
     * @param bool $auth авторизованный пользователь
     * @throws \Exception
     */
    public function reportLowPrice($value, $link, $auth)
    {
        $I = $this->tester;

        $I->waitAndClick(['class' => 'best-price-guarantee__title'], 'best price guarantee');
        if ($auth == false){
            $I->waitAndClick('//a[contains(.,"Войти")]', 'open auth form');
            $I->doLoginFromPage();
        } else {
            $I->waitForElementVisible('//div[@class="best-price-guarantee__window best-price-guarantee__window_form"]', 10);
            $I->fillField('//input[@id="best_price_guarantee_bestProductPrice"]', $value);
            $I->fillField(['id' => 'best_price_guarantee_productUrl'], $link);
            $I->waitAndClick('//div[@class="best-price-guarantee__window-title best-price-guarantee__window-title_left"]', 'best price guarantee window title', true);
            if ($value > 4999) {
                $I->waitForElementNotVisible('//button[@disabled][contains(.,"Отправить")]');
                $I->click(['id' => 'questionnaire_submit']);
                $I->waitForElementVisible(['class' => 'best-price-guarantee__window-title']);
            } else {
                $I->waitForElementVisible('//span[@class="best-price-guarantee__form-warning js--best-price-guarantee__form-warning"]');
            }
        }
    }

    /**
     * Переход и проверка корректной ссылки на промо "Гарантия лучшей цены".
     *
     * @throws \Exception
     */
    public function checkGuaranteeBestPricePromo()
    {
        $I = $this->tester;

        $I->waitForElementVisible(['class' => 'best-price-guarantee__title']);
        $I->moveMouseOver(['class' => 'best-price-guarantee__title']);
        $url = $I->grabAttributeFrom('//a[contains(@class,"best-price-guarantee__link")][contains(.,"Подробнее")]', 'href');
        $I->amOnUrl($url);
        $I->waitForElementVisible('//div[@class="conteiner banner"]');
        $promoUrl = $I->getFullUrl();
        $I->seeValuesAreEquals($url, $promoUrl);
    }

    /**
     * Проверка что "Гарантия лучшей цены" не отображается.
     *
     * @throws \Exception
     */
    public function checkGuaranteeBestPriceNotVisible()
    {
        $I = $this->tester;

        $I->waitForElementVisible(['class' => 'product-sidebar ']);
        $I->waitForElementNotVisible(['class' => 'best-price-guarantee__title']);
    }

    /**
     * Изменение количества товара.
     *
     * @param int $value количество товара
     * @return int $quantity количество товара
     * @throws \Exception
     */
    public function changeQuantity($value)
    {
        $I = $this->tester;

        $I->waitForElementVisible(self::XPATH_QUANTITY);
        $I->clearField(self::XPATH_QUANTITY);
        $I->appendField(self::XPATH_QUANTITY, $value);
        $I->wait(SHORT_WAIT_TIME);
        $quantity = $I->getNumberFromLink(self::XPATH_QUANTITY, 'new product quantity');

        return $quantity;
    }

    /**
     * Получение количества бонусов.
     *
     * @return int $bonuses количество товара
     * @throws \Exception
     */
    public function getBonuses()
    {
        $I = $this->tester;

        $I->waitForElementVisible(['class' => 'ProductHeader__bonus-block']);
        $bonuses = $I->getNumberFromLink(['class' => 'ProductHeader__bonus-block'], 'get bonuses');
        $I->seeValueNotEmpty($bonuses);

        return $bonuses;
    }

    /**
     * Получение суммы кредита в месяц.
     *
     * @return int $creditSum количество товара
     * @throws \Exception
     */
    public function getCreditSum()
    {
        $I = $this->tester;

        $I->waitForElementVisible('//div[@class="ProductHeader__link-item"][contains(.,"кредит")]');
        $creditSum = $I->grabTextFrom('//ins[@class="num credit-price-number_js"]');
        $creditSum = strstr($creditSum, '₽' , true);
        $I->seeValueNotEmpty($creditSum);

        return $creditSum;
    }

    /**
     * Проверка изменения цен при увеличении количества товара.
     *
     * @param int $value количество товара
     * @throws \Exception
     */
    public function checkChangeItemQuantity($value)
    {
        $I = $this->tester;

        $I->lookForwardTo("change item quantity to " . $value . " and check prices/bonuses in page");
        $oldPrice = 0;
        $clubPrice = 0;
        $price = $this->getStandardProductPrice();
        $creditSum = $this->getCreditSum();
        if ($I->getNumberOfElements('//span[@class="price old-price"]') > 0) {
            $oldPrice = $this->getOldProductPrice();
        }
        if ($I->getNumberOfElements(self::XPATH_CLUB_PRICE) > 0) {
            $clubPrice = $this->getClubProductPrice();
        }

        $this->changeQuantity($value);
        $I->wait(SHORT_WAIT_TIME);
        $I->seeValuesAreEquals($this->getStandardProductPrice(), $price*$value);
        $I->seeValuesAreEquals($this->getCreditSum(), $creditSum*$value);
        if ($oldPrice > 0) {
            $I->seeValuesAreEquals($this->getOldProductPrice(), $oldPrice * $value);
        }
        if ($clubPrice > 0) {
            $I->seeValuesAreEquals($this->getClubProductPrice(), $clubPrice);
        }
    }

    /**
     * Добавить коплектующий в сборку по кнопке "добавить в сборку".
     *
     * @param string $id айди комплектующего
     * @throws \Exception
     */
    public function addToConfiguration($id)
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(@class,"js--add-to-config")]', 'add to configuration');
        $I->waitForElementVisible('//div[@class="configuration-feature__package"]//div[@data-product-id="'.$id.'"]');
    }

    /**
     * Добавить обзор.
     *
     * @throws \Exception
     */
    public function addReview()
    {
        $I = $this->tester;

        $I->waitAndFill(['name' => 'name'], 'add name', 'Тест');
        $I->waitAndFill('//div[@class="tui-editor te-ww-mode"]', 'add description', REVIEW);
        $I->waitAndClick('//span[contains(.,"Опубликовать")]', 'add review');
        $I->checkElementOnPage(['id' => 'readreview']);
    }

    /**
     * Добавление в корзину случайной услуги установки.
     *
     * @return mixed Наименование, цена, id услуги
     * @throws \Exception
     */
    public function addToCartInstallService()
    {
        $I = $this->tester;

        $service = $this->getInstallService();
        codecept_debug($service);
        $selectedService = array_rand($service);
        codecept_debug($selectedService . ' array random');
        codecept_debug($service[$selectedService]);
        $clickService = $selectedService + 1;
        codecept_debug($clickService . ' click service');
        $I->waitAndClick(self::XPATH_INSTALL_SERVICE . '[' . $clickService . ']' . '//label[@class="Checkbox AdditionalServices__input"]',
            'add install service');
        $I->wait(SHORT_WAIT_TIME);
        $servicePriceCartBlock = $I->getNumberFromLink(['class' => 'ProductHeader__service-price'], 'service price in cart block');
        codecept_debug($service[$selectedService]['price'] . ' price');
        $I->seeServicePriceEquals($servicePriceCartBlock, $service[$selectedService]['price']);


        return $service[$selectedService];
    }

    /**
     * Получение наименования и стоимости доступных услуг установки.
     *
     * @return array $serviceData Наименование, цена, id услуги
     * @throws \Exception
     */
    public function getInstallService()
    {
        $I = $this->tester;

        $I->waitForElementVisible(self::$installServicePrice);
        $prices = $I->grabMultiple(['xpath' => self::$installServicePrice]);
        $I->seeArrayNotEmpty($prices);
        $names = $I->grabMultiple(['xpath' => self::$installServiceName]);
        $I->seeArrayNotEmpty($names);
        $ids = $I->grabMultiple(['xpath' => self::$installServiceCheckBox], 'data-id');
        $I->seeArrayNotEmpty($ids);
        $serviceData = array();
        foreach ($names as $key => $value) {
            $serviceData[] = array('name' => $value, 'price' => preg_replace("/[^,.0-9]/", '', $prices[$key]), 'id' => $ids[$key]);
        }

        return $serviceData;
    }
}
