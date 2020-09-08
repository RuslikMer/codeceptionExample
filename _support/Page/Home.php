<?php
namespace Page;

class Home
{
    // include url of current page
    public static $URL = '/';
    const PRODUCT_PAGE_NAME = 'ProductCardVertical__name';
    const PRODUCT_PAGE_IMG = 'ProductCardVertical__image-wrap';
    const PRODUCT_TOP = 'top-products'; //Популярное в Топ 100
    const PRODUCT_SPECIAL = 'special-offers'; //Суперцена
    const PRODUCT_NEW = 'new-products-tab'; //Новинки
    const PRODUCT_NOW = 'products-buying-now'; //Сейчас покупают
    //блок с баннерами
    const XPATH_BANNERS_BLOCK = '//div[@class="Slider__wrap"]';
    //закладка Суперцена на главной странице, блок с товарами
    const XPATH_SPECIAL = '//div[@class="clearfix product-card-container product-card-container_row2 tab-main special-offers block_data__gtm-js block_data__pageevents-js between-card-banners_gtm-js between-card-banners_pageevents-js"]';
    //закладка Сейчас смотрят на главной странице, блок с товарами
    const XPATH_NEW = '//div[@class="clearfix product-card-container product-card-container_row2 tab-main new-products-tab block_data__gtm-js block_data__pageevents-js between-card-banners_gtm-js between-card-banners_pageevents-js"]';
    //закладка Сейчас покупают на главной странице, блок с товарами
    const XPATH_NOW = '//div[@class="clearfix product-card-container product-card-container_row2 tab-main products-buying-now block_data__gtm-js block_data__pageevents-js between-card-banners_gtm-js between-card-banners_pageevents-js"]';
    //закладка Популярное в топ100 на главной странице, блок с товарами
    const XPATH_TOP = '//div[starts-with(@class, "clearfix product-card-container product-card-container_row2  ")]';
    //блок с описанием товара в плитках
    const XPATH_PRODUCT_CARD = '//div[contains(@class,"product_data__gtm-js")]';
    //richrelevance
    const XPATH_RICH_PRODUCT_CARD = '//div[contains(@class,"SubgridScrollable__item")]';
    const XPATH_PRODUCTS_CATEGORY = '//div[contains(@class,"between-card-banners_gtm-js")]'; // блоки категорий рекомендуемых товаров
    //ссылка выбора города
    const XPATH_CHANGE_CITY_LINK = '//button[@class="js--CitiesSearch-trigger MainHeader__open-text TextWithIcon"]';
    //заголовок новостей
    const XPATH_NEWS = '//a[contains(.,"новости")]';
    //список на странице новостей
    const XPATH_NEWS_LIST = ['class' => 'NewsPreviewItem'];
    //ротатор "Вы смотрели" на главной
    const XPATH_ROTATOR_RECENTLY_CLASS = '//div[@class="clearfix product-card-container product-card-container_row1 block_data__gtm-js block_data__pageevents-js between-card-banners_gtm-js between-card-banners_pageevents-js"]';
    const XPATH_ROTATOR_RECENTLY = self::XPATH_ROTATOR_RECENTLY_CLASS . '/div';
    const XPATH_FOOTER_COPYRIGHT = '//div[@class="Footer__copyright"]';
    const XPATH_FOOTER = '//div[contains(@class,"Footer")]'; //путь к футеру
    const WISH_ITEMS = '//a[contains(@href,"wishlist")]//div[contains(@class,"HeaderMenu__count")]';
    const PROPERTY_PROTECTION = 'Защита имущества';
    const ASSEMBLING = 'Сборка';
    const PURCHASE_PROTECTION = 'Защита покупки';
    const SUPPLIES = 'Подборка';
    const DELIVERY = 'Доставка';
    const SUBSCRIPTIONS = 'Электронные ключи и подписки';
    const DIGITAL = 'Цифровые';
    const INSTALL = 'Установка';
    const ABOUT = 'Ситилинк';
    const CORPORATE = 'Корпоративным клиентам';
    const GET_ORDER = 'Оформление заказа';
    const CREDIT = 'редит';
    const CLUB = 'Клуб Ситилинк';
    const WARRANTY = 'Гарант';
    const PRIVACY_POLICY = 'персональных данных';
    const FORUM = 'Форум';
    const USED = 'Барахолка';
    const ADDRESS = 'Адреса магазинов';
    const REVIEWS = 'Обзоры';
    const CONFIG = 'Конфигуратор';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    public static $profilePopupButton = Cart::USER_MENU_LINK . '/span';

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
     * Открытие главной страницы сайта и проверка наличия основных элементов:
     *
     * - Логотип
     * - Ссылка на выбор города
     * - Ссылка "Сервисы и услуги"
     * @throws \Exception
     */
    public function open() {
        $I = $this->tester;

        $I->lookForwardTo("opening home page");
        $I->amOnPage("/");
        $I->setCookie('testing','1');
        $I->resetCookie('ab_test');
        $I->setCookie('ab_test', 'new_designv5%3A3');
        $I->amOnPage("/");

        $I->lookForwardTo("checking for useful elements on home page");

        $I->checkMandatoryElements();
    }

    /**
     * Очищаем корзину и выставляем Москву, если требуется.
     *
     * @throws \Exception
     */
    public function doCleanUp()
    {
        $I = $this->tester;

        $I->cartCleanUp();
        $this->wishListCleanUp();
        $I->changeCity('Москва');
    }

    /**
     * Проверка обязательных элементов на страницах сайта.
     *
     * @throws \Exception
     */
    public function checkMandatoryElements() {
        $I = $this->tester;

        $I->seeElement('//div[contains(@class,"MainHeader__logo")]');
        $I->seeElement(self::XPATH_FOOTER_COPYRIGHT);
    }

    /**
     * очищаем корзину, если требуется.
     *
     * @throws \Exception
     */
    public function cartCleanUp() {
        $I = $this->tester;

        if ($this->getCartItems() > 0) {
            $I->goToCartFromTopLink();
            $I->deleteItemFromCart(0);
            $I->openHomePage();
        }
    }

    /**
     * Проверка главной страницы сайта - наличие основных компонентов:
     *
     * - Блок Суперцена
     * - Блок Новинки
     * - Блок Сейчас покупают
     * - Блок Популярное в Top 100
     * @throws \Exception
     */
    public function checkHomePage() {
        $I = $this->tester;

        $I->waitAndClick('//li[@data-block="' . self::PRODUCT_SPECIAL . '"]', 'special-offers block');
        $I->checkElementOnPage(self::XPATH_SPECIAL . '//div[@class="product-card__name"]', 'special offer items');
        $I->waitAndClick('//li[@data-block="' . self::PRODUCT_NEW . '"]', 'new product block');
        $I->checkElementOnPage(self::XPATH_NEW . '//div[@class="product-card__name"]', 'new product items');
        $I->waitAndClick('//li[@data-block="' . self::PRODUCT_NOW . '"]', 'now buying block');
        $I->checkElementOnPage(self::XPATH_NOW . '//div[@class="product-card__name"]', 'now buying items');
        $I->waitAndClick('//li[@data-block="' . self::PRODUCT_TOP . '"]', 'top products block');
        $I->checkElementOnPage(self::XPATH_TOP . '//div[@class="product-card__name"]', 'top products items');
    }

    /**
     * Добавление в корзину товаров с главной страницы (плитки).
     *
     * @param int $categoryNumber Номер категории
     * @param int $productNumber Номер товара в категории
     * @param $link string class блока на главной
     * @return string Id товара
     * @throws \Exception
     */
    public function addToCart($link, $categoryNumber, $productNumber) {
        $I = $this->tester;

        if (is_null($categoryNumber) || is_null($productNumber)) {
            $itemPosition = $this->chooseNumbersOfCategoryAndProduct();
            codecept_debug($itemPosition);
            $categoryNumber = $itemPosition['category'];
            $productNumber = $itemPosition['product'];
        }

        $I->lookForwardTo("add to cart product " . $productNumber . " from main page's tiles");

        $I->lookForwardTo("we need to check that cart amount was increased - get initial");
        $initialAmount = $I->getCurrentCartAmountInitial();

        $I->lookForwardTo("get current product price");
        $currentProductPrice = $I->getNumberFromLink('//div[@class="' . $link . '"]' . self::XPATH_PRODUCT_CARD . '[' . $productNumber . ']//div[@class="product-card__price-container"]//span[@class="price"]/ins[@class="num"]',
            'get current Product Price');

        $I->lookForwardTo("adding item to cart");
        $I->waitAndClick('//div[@class="' . $link . '"]' . self::XPATH_PRODUCT_CARD . '[' . $productNumber . ']//div[@class="product-card__buttons-container"]//button[@class="add_to_cart pretty_button pretty_button__small type4-1 add_to_cart_text_for_user"]',
            'add to cart button for product ' . $productNumber);
        $I->wait(SHORT_WAIT_TIME);
        $I->continueShopping();
        $I->lookForwardTo("check that cart amount was increased - get after");
        $afterAmount = $I->getCurrentCartAmountInitial();

        $I->lookForwardTo("check that cart amount was increased exactly by product price");
        if ($initialAmount + $currentProductPrice != $afterAmount) {
            Throw new \Exception('Cart amount was not increased by product price!');
        }
    }

    /**
     * Получение рейтинга продукта с главной страницы.
     *
     * @param int $number Номер продукта на странице по порядку
     * @return string Рейтинг в условном формате (0,1,2,3,4,5) -> (5,4,3,2,1,0)
     */
    public function getRateFromHomePage($number)
    {
        $I = $this->tester;

        $I->lookForwardTo('grab main array from product ' . $number);
        if ($I->getNumberOfElements('//div[@class="recommends-home-box__item"]', 'rich relevance blocks') > 0) {
            $main = $I->grabMultiple(['xpath' => self::XPATH_TOP .'//div[@class="recommends-home-box__item"][' . $number . ']' . self::XPATH_PRODUCT_CARD . '//div[@class="ratings ratings_js"]/span'],'class');
        } else {
            $main = $I->grabMultiple(['xpath' => self::XPATH_TOP . self::XPATH_PRODUCT_CARD . '[' . $number . ']//div[@class="ratings ratings_js"]/span'],'class');
        }
        $main = array_slice($main, 0, 5);

        codecept_debug($main);
        $starMain = array_search('star_js selected', $main);
        if ($starMain === false) {
            $starMain = array_search('star_js selected half', $main);
            if ($starMain === false) {
                $starMain = 5;
                codecept_debug('null main - '.$starMain);
            }else{
                $starMain = $starMain + 0.5;
                codecept_debug('half main - '.$starMain);
            }
        }else{
            codecept_debug('FULL STAR MAIN - '.$starMain);
        }

        return $starMain;
    }

    /**
     * Проверка всех главных баннеров.
     *
     * @throws \Exception Если не выбран переключатель или если баннер не выбран по переключателю
     */
    public function checkBanner()
    {
        $I = $this->tester;

        $arrayBanners = $I->grabMultiple('//div[contains(@class,"banner_item_gtm-js")]/a');
        foreach ($arrayBanners as $bannerNumber => $value){
            $bannerNumber = $bannerNumber + 1;
            if ($bannerNumber != 1) {
                $I->waitAndClick(['class' => 'Slider__next'], 'select banner from bottom switcher');
            }

            $selectedBanner = $I->getNumberOfElements('//div[contains(@class,"activeSlide")][@data-gtm-position='. $bannerNumber .']');
            codecept_debug($selectedBanner);
            if($selectedBanner == 0){
                Throw new \Exception('banner is not selected');
            }

            $promoUrl = $I->grabAttributeFrom('//div[contains(@class,"banner_item_gtm-js")]['. $bannerNumber .']/a', 'href');
            $I->waitAndClick(self::XPATH_BANNERS_BLOCK, 'the banner itself');
            $I->switchToNextTab();
            $I->seeThatUrlsAreEquals($promoUrl, $I->getFullUrl(), 'promo url', 'current page url');
            $I->closeTab();
            $I->switchToPreviousTab();
            $I->openHomePage();
        }
    }

    /**
     * Смена города.
     *
     * @param string $param Наименование города
     * @throws \Exception
     */
    public function changeCity($param)
    {
        $I = $this->tester;

        $city = $I->upFirst($param);
        $I->lookForwardTo('trying to change city to ' . $city);
        $currentCity = $I->grabTextFrom(self::XPATH_CHANGE_CITY_LINK);
        $currentCityTranslit = $I->translit($currentCity);
        codecept_debug('current city is ' . $currentCityTranslit);
        if ($currentCity != $city) {
            if (($I->getNumberOfElements('//div[contains(@class,"Popup__show-popup")]')) == 0){
                $I->waitAndClick(self::XPATH_CHANGE_CITY_LINK, 'switch city');
            }

            $arrayCity = $this->getCities();
            $rndCity = array_rand($arrayCity, 1);
            $rndCityValue = $arrayCity[$rndCity];
            if(empty($city)){
                $city = $I->upFirst($rndCityValue);
            }

            $I->waitAndFill(['name'=>'search_text'], 'search field ' , $city);
            $I->waitAndClick('//span[@class="CitiesSearch__highlight"][.="'.$city.'"]',
                'selected city');
            $I->wait(SHORT_WAIT_TIME);
            $changedCity = $I->grabTextFrom(self::XPATH_CHANGE_CITY_LINK);
            $changedCityTranslit = $I->translit($changedCity);
            codecept_debug('changed city is ' . $changedCityTranslit);
            $I->seeNotEqualsCity($currentCity, $changedCity);
        } else {
            $I->lookForwardTo('city ' . $city . ' is already selected');
        }

        $this->checkCityPhone();
    }

    /**
     * Проверка инпута поиска города на emoji.
     *
     * @param string $param Emoji
     * @throws \Exception
     */
    public function checkCitySearchByEmoji($param)
    {
        $I = $this->tester;

        $I->waitAndClick(self::XPATH_CHANGE_CITY_LINK, 'switch city');
        $I->waitAndFill(['name'=>'search_text'], 'search field ' , $param);
        $I->checkElementOnPage(['class' => 'CitiesSearch__list']);
    }

    /**
     * Переход в личный кабинет.
     *
     * @throws \Exception
     */
    public function goToProfile()
    {
        $I = $this->tester;

        $I->waitAndClick(Cart::USER_MENU_LINK, 'button profile');
        $I->waitForElementVisible(Cart::USER_MENU_LINK.'//ul[@class="UserMenu__menu-list"]');
        $I->waitAndClick(Cart::USER_MENU_LINK . '//a[contains(.,"Мой профиль")]', 'my profile link');
        $I->waitForElementVisible('//li[@class="selected"][.="Мой профиль"]');
    }

    /**
     * Переход на страницу товара, товар может быть указан числом по порядку на странице или выбран случайным образом.
     *
     * @param string $imgNameLink  Дополнение Xpath перехода через наименование или картинку
     * @param null $number Номер товара по порядку
     * @throws \Exception
     */
    public function goToProductFromTopPage($imgNameLink , $number = null)
    {
        $I = $this->tester;

        if (is_null($number)) {
            $numberMax = $I->getNumberOfElements('//div[@class="clearfix block_data__gtm-js block_data__pageevents-js"]' . self::XPATH_PRODUCT_CARD . '/div[@class="product-card__name"]/a',
                "top 100 page's product items");
            $number = mt_rand(1, $numberMax);
        }
        $I->lookForwardTo("go to product " . $number . " from top 100 page");
        $I->scrollTo(['xpath' => '//div[@class="clearfix block_data__gtm-js block_data__pageevents-js"]' . self::XPATH_PRODUCT_CARD . '[' . $number . ']/div[@class="'. $imgNameLink .'"]/a']);
        $I->waitAndClick('//div[@class="clearfix block_data__gtm-js block_data__pageevents-js"]' . self::XPATH_PRODUCT_CARD . '[' . $number . ']/div[@class="'. $imgNameLink .'"]/a',
            'go to product ' . $number . 'from top 100 page');
    }

    /**
     * Переход на страницу Все товары в топ 100.
     *
     * @throws \Exception
     */
    public function goToTopHundredPage()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to Top 100 page');
        $I->waitAndClick("//div[@class='top-hundred top-hundred_js']/a", "Top100 link");
        $I->getNumberOfElements('//div[@class="clearfix block_data__gtm-js block_data__pageevents-js"]' . self::XPATH_PRODUCT_CARD . '/div[@class="product-card__name"]/a',
            "top 100 page's product items");
    }

    /**
     * Переход на страницу товара, товар может быть указан числом по порядку на странице или выбран случайным образом.
     *
     * @param string $imgNameLink  Дополнение Xpath перехода через наименование или картинку
     * @param string $productContainer Дополнение Xpath для разных типов товаров на главной
     * @param null $number Номер товара по порядку
     * @throws \Exception
     */
    public function goToProductFromSpecialPage($imgNameLink , $productContainer , $number = null)
    {
        $I = $this->tester;

        $I->lookForwardTo('go to special page');
        $I->waitAndClick('//li[@data-block="' . $productContainer . '"]', $productContainer, 'go to ' . $productContainer . ' page');
        if (is_null($number)) {
            $number = mt_rand(1, 8);
        }

        if ($I->getNumberOfElements('//div[@class="recommends-home-box__item"]', 'rich relevance blocks') > 0) {
            $number = 1;
        }

        switch ($productContainer) {
            case self::PRODUCT_TOP:
                $I->waitAndClick(self::XPATH_TOP . self::XPATH_PRODUCT_CARD . '[' . $number . ']//div[@class="' . $imgNameLink . '"]/a',
                    'go to product ' . $number . ' from top 100 page');
                break;
            case self::PRODUCT_SPECIAL:
                $I->waitAndClick(self::XPATH_SPECIAL . self::XPATH_PRODUCT_CARD . '[' . $number . ']/div[@class="' . $imgNameLink . '"]/a',
                    'go to product ' . $number . ' from special offers page');
                break;
            case self::PRODUCT_NEW:
                $I->waitAndClick(self::XPATH_NEW . self::XPATH_PRODUCT_CARD . '[' . $number . ']/div[@class="' . $imgNameLink . '"]/a',
                    'go to product ' . $number . ' from new products page');
                break;
            case self::PRODUCT_NOW:
                $I->waitAndClick(self::XPATH_NOW . self::XPATH_PRODUCT_CARD . '[' . $number . ']/div[@class="' . $imgNameLink . '"]/a',
                    'go to product ' . $number . ' from now buying page');
                break;
        }
    }

    /**
     * Переход в карточку товара из ротатора "Недавно просмотренные",
     *
     * @param string $imgNameLink Дополнение Xpath перехода через наименование или картинку
     * @param int $number Номер товара по порядку
     * @throws \Exception
     */
    public function goToProductFromMainRotator($imgNameLink , $number)
    {
        $I = $this->tester;

        if (is_null($number)) {
            $number = mt_rand(1, 4);
        }
        $I->lookForwardTo("go to product " . $number . " from rotator");
        $I->waitAndClick('//div[@data-pageevents-location="home_page_b5_citi"]'.self::XPATH_RICH_PRODUCT_CARD.'[' . $number . ']//div[@class="ProductCardVertical__picture-container"]',
            'go to product ' . $number . ' from main page rotator', true);
    }

    /**
     * Проверка загрузки картинки капчи.
     *
     * @param string $form попап или страница
     * @throws \Exception
     */
    public function checkCaptcha($form)
    {
        $I = $this->tester;

        $I->lookForwardTo('going to check captcha');
        $I->wait(SHORT_WAIT_TIME);
        $urlFromStyle = $I->grabAttributeFrom($form.'//div[@class="Captcha__image"]', 'style');
        if ( preg_match('#\("(.*?)"\)#', $urlFromStyle, $urlFromStyle) == 0 ) {
            Throw new \Exception('captcha url is empty');
        }

        $firstImage = $I->screenAndCropImage($form.'//div[@class="Captcha__image"]', 'firstScreen');
        codecept_debug("captcha url is " . $urlFromStyle[1]);
        $I->amOnUrl($urlFromStyle[1]);
        $I->checkElementOnPage('//img[@src="' . $urlFromStyle[1] . '"]', 'image on the page');
        $secondImage = $I->screenAndCropImage('//img[contains(@style,"-webkit-user-select")]', 'secondScreen');
        $I->compareCaptcha($firstImage, $secondImage);
    }

    /**
     * Переход на страницу акции с главной.
     */
    public function goToSingleActionFromHomePage()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to single action from home page');
        $actionNumber = mt_rand(1,3);
        $I->lookForwardTo('trying to use action ' . $actionNumber);
        $url = $I->grabAttributeFrom('//div[contains(@class,"js--Slider-trigger")]/div[' . $actionNumber . ']/a', 'href');
        $I->amOnUrl($url);
    }

    /**
     * Переход на страницу акций.
     *
     * @throws \Exception
     */
    public function goToActionsFromHomePage()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to actions from home page');
        $I->waitAndClick( '//div[@class="MainMenu__link"]//a[contains(text(),"Акции")]', 'go to actions page');
    }

    /**
     * Получение ай ди товара с главной страницы.
     *
     * @param int $categoryNumber Номер категории по порядку
     * @param int $productNumber Номер товара в категории по порядку
     * @return string Ай ди товара
     * @throws \Exception
     */
    public function getItemId($categoryNumber, $productNumber)
    {
        $I = $this->tester;

        if (is_null($categoryNumber) || is_null($productNumber)) {
            $itemPosition = $this->chooseNumbersOfCategoryAndProduct();
            codecept_debug($itemPosition);
            $categoryNumber = $itemPosition['category'];
            $productNumber = $itemPosition['product'];
        }

        $productCardXpath = self::XPATH_PRODUCTS_CATEGORY.'["'.$categoryNumber.'"]'. self::XPATH_RICH_PRODUCT_CARD . '['. $productNumber .']/div';

        $I->lookForwardTo('grab id from main page - item number '.$productNumber);
        $itemId = $I->grabAttributeFrom(['xpath' => $productCardXpath], 'data-product-id');
        $I->seeProductIdNotEmpty($itemId);
        codecept_debug('item id ' . $itemId);

        return $itemId;
    }

    /**
     * Получение списка ай ди товаров.
     *
     * @param int $length количество значений массива
     * @return array $itemsId список айди товаров
     * @throws \Exception
     */
    public function getItemsIdFromHomePage($length)
    {
        $I = $this->tester;

        $productCardXpath = '//div[contains(@class,"product_data__gtm-js") and @data-product-id]';
        $I->waitForElementVisible($productCardXpath);
        $itemsId = $I->grabMultiple($productCardXpath, 'data-product-id');
        $I->seeArrayNotEmpty($itemsId);
        if ($length != null){
            $itemsId = array_slice($itemsId, 0, $length);
        }

        return $itemsId;
    }

    /**
     * Получение номера случайного товара с главной страницы.
     */
    public function getRandomItemNumber()
    {
        $I = $this->tester;

        $index = [];
        $elements = $I->grabMultiple(['class' => 'recommends-home-box__item']);
        $sortElements = array_diff($elements, array('', NULL, false));
        codecept_debug($sortElements);

        foreach ($sortElements as $key => $element)
        {
            array_push($index, $key);
        }

        $number = mt_rand(1, mt_rand(1, count($index)));

        return $number;
    }

    /**
     * Переход на карточку товара с главной страницы.
     *
     * @param int $categoryNumber Номер рекомендуемой категории товаров
     * @param int $productNumber Номер товара в категории по порядку
     * @throws \Exception
     */
    public function goToProductFromMainPage($categoryNumber, $productNumber)
    {
        $I = $this->tester;

        if (is_null($categoryNumber) || is_null($productNumber)) {
            $itemPosition = $this->chooseNumbersOfCategoryAndProduct();
            codecept_debug($itemPosition);
            $categoryNumber = $itemPosition['category'];
            $productNumber = $itemPosition['product'];
        }

        $I->waitAndClick(self::XPATH_PRODUCTS_CATEGORY . '['.$categoryNumber.']' . self::XPATH_RICH_PRODUCT_CARD . '['.$productNumber.']'.Listing::PRODUCT_TITLE,
            'go to product ' . $productNumber . ' page', true);
    }

    /**
     * Выбор номера категории и номера товара в категории.
     *
     * @return array $itemPosition номер категории и номер товара
     * @throws \Exception
     */
    public function chooseNumbersOfCategoryAndProduct()
    {
        $I = $this->tester;

        $itemPosition = array();
        $countOfRecommendedItemsCategories = $I->getNumberOfElements(self::XPATH_PRODUCTS_CATEGORY, "count of categories");
        if ($countOfRecommendedItemsCategories > 3) {
            $countOfRecommendedItemsCategories = 3;
        }

        $categoryNumber = mt_rand(1, $countOfRecommendedItemsCategories);
        array_push($itemPosition, $itemPosition['category'] = $categoryNumber);

        $countOfProductsInCategory = $I->getNumberOfElements(self::XPATH_PRODUCTS_CATEGORY . '['.$categoryNumber.']' . self::XPATH_PRODUCT_CARD,
            "main page's product's category items");
        if ($countOfProductsInCategory > 4) {
            $countOfProductsInCategory = 4;
        }

        $productNumber = mt_rand(1, $countOfProductsInCategory);
        array_push($itemPosition, $itemPosition['product'] = $productNumber);

        return $itemPosition;
    }

    /**
     * Проверка наличия блока Рич релеванс.
     *
     * @return int Количество элементов рич релеванс
     */
    public function checkRichBlock()
    {
        $I = $this->tester;

        $rich = $I->getNumberOfElements(self::XPATH_TOP . '/div[@class="recommends-home-box"]', "main page's rich product items");
        return $rich;
    }

    /**
     * Получение наименования случайного серого города.
     *
     * @return mixed Наименование города
     * @throws \Exception
     */
    public function getGrayCity()
    {
        $I = $this->tester;
        $I->lookForwardTo('getting gray city list');
        $this->sortCity();
        $cityArray = $I->grabMultiple('//li[contains(@class,"CitiesSearch__item") and not(contains(@class,"CitiesSearch__city-main"))]/a', 'data-search');
        $city = array_rand($cityArray);

        return $cityArray[$city];
    }

    /**
     * Получение наименований желтых городов.
     *
     * @return array Названия городов
     * @throws \Exception
     */
    public function getYellowCityArray()
    {
        $I = $this->tester;

        $I->waitAndClick(self::XPATH_CHANGE_CITY_LINK, 'switch city');
        $I->waitForElementVisible('//li[contains(@class,"CitiesSearch__item")]');
        $cityArray = $I->grabMultiple('//li[contains(@class,"CitiesSearch__city-main")]/a', 'data-search');

        return $cityArray;
    }

    /**
     * Выбор серого города из списка.
     *
     * @throws \Exception
     */
    public function changeCityToGrayRandom()
    {
        $I = $this->tester;
        $I->lookForwardTo('trying to change city to random gray city');
        $currentCity = $I->grabTextFrom(self::XPATH_CHANGE_CITY_LINK);
        $currentCityTranslit = $I->translit($currentCity);
        codecept_debug('current city is ' . $currentCityTranslit);
        $param = $this->getGrayCity();
        $I->waitAndFill(['name'=>'search_text'], 'search field ' , $param);
        $I->waitAndClick('//a[contains(@class,"CitiesSearch__highlight_show")][@data-search="' . mb_strtolower($param) . '"]', 'selected city');
        $changedGrayCity = $I->grabTextFrom(self::XPATH_CHANGE_CITY_LINK);
        $changedGrayCityTranslit = $I->translit($changedGrayCity);
        codecept_debug('changed gray city is ' . $changedGrayCityTranslit);
        $I->seeNotEqualsCity($currentCity, $changedGrayCity);
        $this->checkCityPhone();
    }

    /**
     * Проверка номера телефона города на главной странице.
     *
     * @throws \Exception
     */
    public function checkCityPhone()
    {
        $I = $this->tester;

        $I->lookForwardTo('check city phone');
        $I->waitForElementVisible(['class' => 'MainHeader__phone']);
        $phone = $I->grabTextFrom(['class' => 'MainHeader__phone'], 'city phone');
        $I->seeCityPhone(str_replace(' ', '', $phone));
    }

    /**
     * Проверка отображения списка городов в алфавитном порядке.
     *
     * @param null $arrayCity Список городов
     * @throws \Exception
     */
    public function sortCity($arrayCity = null)
    {
        $I = $this->tester;

        $I->lookForwardTo('check if list of cities is in alphabetical order');
        $I->waitAndClick(self::XPATH_CHANGE_CITY_LINK, 'switch city');
        if($arrayCity == null) {
            $arrayCity = $this->getCities();
        }

        $arrayCity = preg_replace("/\d/", "", $arrayCity);
        $arrayCity = preg_replace("/\-/", "", $arrayCity);
        $notSort = $arrayCity;
        $sortResult = sort($arrayCity, SORT_REGULAR);
        $I->seeSortRegularTrue($sortResult);
        $diff = array_diff_assoc($notSort, $arrayCity);
        codecept_debug('diff array after sort');
        codecept_debug($diff);
        $I->seeSortCityList($notSort, $arrayCity);
    }

    /**
     * Получение списка городов со страницы выбора городов.
     *
     * @return array $arrayCity список городов
     * @throws \Exception
     */
    public function getCities()
    {
        $I = $this->tester;

        $I->waitForElementVisible('//div[@class="CitiesSearch__cities-container js--CitiesSearch__cities-container"]//a');
        $arrayCity = $I->grabMultipleDesc('//div[@class="CitiesSearch__cities-container js--CitiesSearch__cities-container"]//a',
            'cities listing', 'data-search');
        $I->seeArrayNotEmpty($arrayCity);

        return $arrayCity;
    }

    /**
     * Переход на страницу новостей.
     *
     * @throws \Exception
     */
    public function goToNewsPage()
    {
        $I = $this->tester;

        $I->waitAndClick(self::XPATH_NEWS, 'news header');
        $I->seeCurrentUrlEquals(self::route('news/'));
    }

    /**
     * Получение списка новостей со страницы новостей.
     *
     * @return array Список новостей
     */
    public function getNews()
    {
        $I = $this->tester;

        $news = $I->grabMultipleDesc(self::XPATH_NEWS_LIST, 'news list');
        $I->seeArrayNotEmpty($news);

        return $news;
    }

    /**
     * Проверка ссылок в подвале.
     *
     * @param int $tab Столбцы категорий подвала
     * @throws \Exception
     */
    public function checkFooter($tab)
    {
        $I = $this->tester;

        $category = $I->grabMultipleDesc('//div[@class="FooterMenu__list-block List"]['. $tab .']//li/a[not(contains(@class,"Link_disabled"))]',
            'footers listing');
        unset($category[array_search('Вакансии', $category)]);
        codecept_debug($category);
        foreach ($category as $key => $value){
            $I->waitAndClick('//div[@class="FooterMenu__list-block List"]['. $tab .']//li/a[contains(.,"'. $value .'")]',
                'footer category');
            $url = $I->grabFromCurrentUrl();
            codecept_debug($url);
            $this->checkMandatoryElements();
        }
    }

    /**
     * Проверка всех ссылок в подвале.
     *
     * @throws \Exception
     */
    public function checkAllFooterLinks()
    {
        $I = $this->tester;
        $I->wantTo('check all footer links');
        for($tab = 1; $tab <= 3; $tab++){
            $this->checkFooter($tab);
        }
    }

    /**
     * Переход на страницу регистрации.
     *
     * @throws \Exception
     */
    public function openRegistrationForm()
    {
        $I = $this->tester;

        $I->openLoginRegistrationWindow();
        $I->waitAndClick('//div[@class="Popup__html ps"]//span[contains(@class,"AuthGroup__tab-sign-up")]',
            'registration link');
    }

    /**
     * Добавление товара в корзину из ротатора Вы недавно смотрели на Главной.
     *
     * @throws \Exception
     */
    public function addToCartFromRecentlyMain()
    {
        $I = $this->tester;

        $I->lookForwardTo('add to cart from main rotator');
        $I->moveMouseOver(['xpath' => self::XPATH_ROTATOR_RECENTLY]);
        $number = $I->getNumberOfElements(self::XPATH_ROTATOR_RECENTLY , 'rotator pattern');
        if ($number > 4) {
            $number = mt_rand(1, 4);
        } else {
            $number = mt_rand(1, $number);
        }
        if ($number == 3) {
            $number = 2;
        }
        $I->waitAndClick(self::XPATH_ROTATOR_RECENTLY . '[' . $number . ']//button[@class="add_to_cart pretty_button pretty_button__small type4-1 add_to_cart_text_for_user"]',
            'add to cart');
        $I->continueShopping();
        $I->checkElementOnPage(self::XPATH_ROTATOR_RECENTLY . '[' . $number . ']//div[@class="remove_from_cart_container remove_container no_visited"]',
            'button after click');
    }

    /**
     * Получение ссылок на ресурсы со статики.
     *
     * @return array $resources массиы с датой и временем ссылок
     */
    public function getStaticResourceLinks()
    {
        $I = $this->tester;

        $resources = [];
        $href = $I->grabMultiple('//*[@href]', 'href');
        $I->wait(SHORT_WAIT_TIME);
        $src = $I->grabMultiple('//*[@src]', 'src');
        foreach ($href as $link){
            if ((stristr($link, 'static.citilink') && stristr($link, 'css')) || (stristr($link, 'static.citilink') && stristr($link, 'png'))){
                $str = strpos($link,'?');
                $timestamp = substr($link, $str+1);
                array_push($resources, $timestamp);
            }
        }

        foreach ($src as $link){
            if (stristr($link, 'static.citilink') && stristr($link, 'png')){
                $str = strpos($link,'?');
                $timestamp = substr($link, $str+1);
                array_push($resources, $timestamp);
            }
        }

        return $resources;
    }

    /**
     * Получение количества адресов магазинов и пунктов выдачи.
     *
     * @return array $addresses массив с количеством магазинов и пунктов выдачи
     * @throws \Exception
     */
    public function getShopAndPickPointCount()
    {
        $I = $this->tester;

        $allAddresses = $I->getNumberOfElements(['class' => 'StoreGroupItem']);
        $I->selectTypeStore(About::TYPE_SHOPS);
        $shopsCount = $I->getNumberOfElements(['class' => 'StoreGroupItem']);
        $pickPointsCount = $allAddresses - $shopsCount;
        $addresses = [];
        array_push($addresses, $shopsCount);
        array_push($addresses, $pickPointsCount);

        return $addresses;
    }

    /**
     * Переход на страницу сервисные центры.
     *
     * @throws \Exception
     */
    public function goToServiceCenterPage()
    {
        $I = $this->tester;

        $I->waitAndClick(self::XPATH_FOOTER . '//a[contains(.,"Сервисные центры")]', 'go to Service center page');
        $I->waitForElementVisible('//span[contains(.,"Сервисные центры")]');
    }

    /**
     * Переход на страницу сервисные центры.
     *
     * @throws \Exception
     */
    public function goToStoreAddressesPage()
    {
        $I = $this->tester;

        $I->waitAndClick(self::XPATH_FOOTER . '//a[contains(.,"Адреса магазинов")]', 'go to Store Addresses page');
        $I->waitForElementVisible('//span[contains(.,"Адреса магазинов")]');
    }

    /**
     * Выбор города на странице сервисные центры.
     *
     * @return string $city выбранный город
     * @throws \Exception
     */
    public function selectCityOnServiceCenterPage()
    {
        $I = $this->tester;

        $I->waitAndClick(['id' => 'citylist'], 'city list');
        $cityCount = $I->getNumberOfElements('//select[@id="citylist"]/option');
        $num = mt_rand(1,$cityCount);
        $city = $I->grabTextFrom('//select[@id="citylist"]/option['.$num.']');
        $I->waitAndClick('//select[@id="citylist"]/option['.$num.']', 'select city');

        return $city;
    }

    /**
     * Выбор категории на странице сервисные центры.
     *
     * @return string $category выбранную категорию
     * @throws \Exception
     */
    public function selectCategoryOnServiceCenterPage()
    {
        $I = $this->tester;

        $I->waitAndClick('//label[contains(.,"Категория")]', 'select category list');
        $I->waitForElementVisible('//div[@class="js--SelectProductForRepair__catalog"]//li[starts-with(@class,"Autocomplete__item item-click")]');
        $categoryCount = $I->getNumberOfElements('//div[@class="js--SelectProductForRepair__catalog"]//li[starts-with(@class,"Autocomplete__item item-click")]');
        $num = mt_rand(1,$categoryCount);
        $category = $I->grabTextFrom('//div[@class="js--SelectProductForRepair__catalog"]//li[starts-with(@class,"Autocomplete__item item-click")]['.$num.']');
        $I->waitAndFill('//label[contains(.,"Категория")]/input', 'fill brand input', $category);
        $I->waitAndClick('//div[@class="js--SelectProductForRepair__catalog"]//div[@class="Autocomplete__content"]',
            'select category');
        $I->waitAndClick('//button[contains(.,"Показать центры")]', 'show service centers');

        return $category;
    }

    /**
     * Выбор бренда на странице сервисные центры.
     *
     * @return string $brand выбранный бренд
     * @throws \Exception
     */
    public function selectBrandOnServiceCenterPage()
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(.,"В каталоге")]', 'open form');
        $I->waitAndClick('//label[contains(.,"Бренд")]', 'select brand list');
        $brandCount = $I->getNumberOfElements('//div[@class="js--SelectProductForRepair__brand"]//li[starts-with(@class,"Autocomplete__item item-click")]');
        $num = mt_rand(1,$brandCount);
        $brand = $I->grabTextFrom('//div[@class="js--SelectProductForRepair__brand"]//li[starts-with(@class,"Autocomplete__item item-click")]['.$num.']');
        $I->waitAndFill('//label[contains(.,"Бренд")]/input', 'fill brand input', $brand);
        $I->waitAndClick('//div[@class="js--SelectProductForRepair__brand"]//div[@class="Autocomplete__content"]',
            'select brand');

        return $brand;
    }

    /**
     * Получение количества сервисных центров.
     *
     * @return string $result количество сервисных центров
     * @throws \Exception
     */
    public function checkResultOnServiceCenterPage()
    {
        $I = $this->tester;

        $I->waitForElementVisible('//div[contains(@class,"ServiceCenterList")]');
        $result = $I->grabTextFrom('//div[contains(@class,"ServiceCenterList")]');
        codecept_debug($result);
        if (stristr($result, 'не найдено.')){
            $result = 0;
        }else{
            $I->waitForElementVisible('//li[@class="ServiceCenterList__item"]');
            $result = $I->getNumberOfElements('//li[@class="ServiceCenterList__item"]');
            codecept_debug($result);
        }

        return $result;
    }

    /**
     * Поиск сервисного центра по ссылке или айди товара.
     *
     * @param string $itemIdOrLink айди товара
     * @throws \Exception
     */
    public function searchServiceCenterAddressByItemId($itemIdOrLink)
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(.,"По ссылке")]', 'open form');
        $I->waitAndFill(['name' => 'link'], 'fill item id or link', $itemIdOrLink);
        $I->waitAndClick('//button[contains(.,"Показать центры ")]', 'show service centers');
    }

    /**
     * Получение списка городов со страницы выбора городов.
     *
     * @throws \Exception
     */
    public function checkDuplicateCities()
    {
        $I = $this->tester;

        $I->waitAndClick(self::XPATH_CHANGE_CITY_LINK, 'switch city');
        $arrayCities = $this->getCities();
        $I->seeArrayNotContainsDuplicate($arrayCities);
    }

    /**
     * Открыть список городов.
     *
     * @throws \Exception
     */
    public function openCitiesList()
    {
        $I = $this->tester;

        $I->waitAndClick(self::XPATH_CHANGE_CITY_LINK, 'switch city');
        $I->waitForElementVisible('//li[contains(@class,"CitiesSearch__item")]');
    }

    /**
     * Проверка баннера с рекомендуемым товаром.
     *
     * @throws \Exception
     */
    public function checkRecommendBanner()
    {
        $I = $this->tester;

        $size = $I->getElementSize("div.recommends-home-box__item.recommends-home-box__item_banner-like-product > div > a > img");
        $height = $size[0];
        $width  = $size[1];
        if($height < 200 && $width < 100){
            Throw new \Exception('banner is not displayed');
        }
    }

    /**
     * Переход на страницу из футера.
     *
     * @param string $page страница для перехода
     * @throws \Exception
     */
    public function openPageFromFooter($page)
    {
        $I = $this->tester;

        $I->waitAndClick(self::XPATH_FOOTER.'//a[contains(.,"'.$page.'")]', 'open '.$page.' page');
        $I->waitForElementVisible('//div[@class="main_content_inner" and contains(.,"'.$page.'")]');
    }

    /**
     * Переход на страницу брендов.
     *
     * @throws \Exception
     */
    public function goToBrands()
    {
        $I = $this->tester;

        $I->waitAndClick('//a[contains(.,"бренды")]', 'go to all brands');
        $I->waitForElementVisible(['class' => 'BrandBook__header']);
    }

    /**
     * Получить название текущего города.
     *
     * @return string $city текущий город
     * @throws \Exception
     */
    public function getCurrentCity()
    {
        $I = $this->tester;

        $city = $I->grabTextFrom(['class' => 'MainHeader__city']);
        codecept_debug($city);

        return $city;
    }

    /**
     * Добавление в корзину товара по суперцене.
     *
     * @throws \Exception
     */
    public function addSpecialPriceItem()
    {
        $I = $this->tester;

        $I->waitAndClick(['xpath' => '//li[@data-block="special-offers"]'], "Special Offers (supercena) link");
        $I->addToCartFromTileXpath('clearfix product-card-container product-card-container_row2 tab-main special-offers block_data__gtm-js block_data__pageevents-js between-card-banners_gtm-js between-card-banners_pageevents-js',4);
        $I->waitAndClick(['xpath' => '//li[@data-block="new-products-tab"]'], "New products link");
        $I->addToCartFromTileXpath('clearfix product-card-container product-card-container_row2 tab-main new-products-tab block_data__gtm-js block_data__pageevents-js between-card-banners_gtm-js between-card-banners_pageevents-js',3);
        $I->waitAndClick(['xpath' => '//li[@data-block="products-buying-now"]'], "Now buying link");
        $I->addToCartFromTileXpath('clearfix product-card-container product-card-container_row2 tab-main products-buying-now block_data__gtm-js block_data__pageevents-js between-card-banners_gtm-js between-card-banners_pageevents-js',3);
    }

    /**
     * Получить кол-во товаров в корзине.
     *
     * @return string Кол-во товаров в корзине
     */
    public function getCartItems()
    {
        $I = $this->tester;

        $cartItems = $I->getNumberFromLink(Listing::CART_ITEMS, 'cart items from home page');
        codecept_debug('cart items ' . $cartItems);

        return $cartItems;
    }

    /**
     * Получить кол-во товаров в желаниях.
     *
     * @return string Кол-во товаров в желаниях
     */
    public function getWishItems()
    {
        $I = $this->tester;

        $wishItems = $I->getNumberFromLink(self::WISH_ITEMS, 'wish items from home page');
        codecept_debug('wish items ' . $wishItems);

        return $wishItems;
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
        if($this->getWishItems() > 0){
            $I->lookForwardTo("wipe wishlist");
            $I->goToWishListTopRight();
            $I->waitAndClick('//button[contains(@class,"button-remove-all")]', "clean up wishlist button", true);
        } else {
            $I->lookForwardTo("check message - empty wishlist");
            $I->goToWishListTopRight();
            $I->waitForElementVisible('//div[@class="FavouritesAdvantagesItem"][contains(.,"Список очищен")]');
        }

        $I->openHomePage();
    }

    /**
     * Переход на главную по мини лого.
     *
     * @throws \Exception
     */
    public function goToHomePageBySmallLogo()
    {
        $I = $this->tester;

        $I->scrollTo('//div[contains(@class,"MainHeader__inner_bottom")]');
        $I->waitAndClick('//div[contains(@class,"MainHeader__logo-small")]//a[@class="Logo__link"]',
            'go to home page');
        $this->checkMandatoryElements();
    }
}
