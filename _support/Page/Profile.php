<?php
namespace Page;

class Profile
{
    // include url of current page
    public static $URL = '/profile/';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    const XPATH_DELIVERY_ADDRESS = '//div[@class="delivery_address"]'; //путь к адрессу доставки
    const OWN_WISHLIST = '//div[@class="ProductCardFavourites__name"]'; //свой список желания
    const OTHER_WISHLIST = ['class' => 'product_name']; //чужой список желания
    const XPATH_REVIEW = '//div[@class="review with_image"]/article//a'; //путь к заголовкам обзоров

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

    //таблица с товарами в списке желаний
    const XPATH_PRODUCT_CART_WISHILIST = '//div[@class="ProductListFavourites__grid"]/div';
    //заказ в списке заказов
    const XPATH_ORDER_ITEM = 'div[@class="order_wrapper expandable_control_header  block_data__gtm-js block_data__pageevents-js"]';
    const XPATH_PROFILE = '//ul[contains(@class,"private_main_navigation with_bright_counters")]';
    //кнопка сохранения настроек оповещений
    const NOTIFICATION_SETTINGS_BUTTON = '//form[@class="notification_settings"]/button';
    //кнопка сохранения персональных данных
    const PERSONAL_DATA_BUTTON = '//form[@class="js--personal-data-form inline-form private_data__js"]';

    /**
     * Прокликивает по всем кнопкам в навигационной панели Личного кабинета,
     * кроме B2B и проверяет, что выбрана именно эта кнопка. Проверка вкладки Профиль.
     *
     * @throws \Exception
     */
    public function checkProfile()
    {
        $I = $this->tester;

        $I->checkElementOnPage(self::XPATH_PROFILE . '/li[1][@class="selected"]', 'profile selected');
        $this->checkProfileButton();
        $arrayCollection = $I->grabMultipleDesc(self::XPATH_PROFILE . '/li', 'navigation panel');
        $arrayCollection = array_diff($arrayCollection, ["Мой профиль"]);
        $arrayCollection = array_slice($arrayCollection, 0, 6);
        codecept_debug('array grabbed from page');
        codecept_debug($arrayCollection);
        foreach ($arrayCollection as $value) {
            $I->waitAndClick(self::XPATH_PROFILE . '/li[contains(.,"'.$value.'")]', 'navigation panel button');
            $I->checkElementOnPage(self::XPATH_PROFILE . '/li[contains(.,"'.$value.'")][@class="selected"]', "$value selected");
        }
    }

    /**
     * Клик на ссылку B2b кабинет - в личном кабинете обычного пользователя.
     *
     * @throws \Exception
     */
    public function checkB2BLink()
    {
        $I = $this->tester;

        $I->waitAndClick(self::XPATH_PROFILE . '/li/a[text()="B2b кабинет"]', 'navigation panel B2b button');
        $I->checkElementOnPage('//a[text()="Получить доступ к организации"]', "b2b page is ready");
    }

    /**
     * Проверка вкладки Активность.
     *
     * @throws \Exception
     */
    public function checkProfileAction()
    {
        $I = $this->tester;

        $I->checkElementOnPage(self::XPATH_PROFILE . '/li[1][@class="selected"]', 'profile selected');
        $this->checkActionButton();
    }

    /**
     * Проверка вкладки Гарантийный раздел.
     *
     * @throws \Exception
     */
    public function checkProfileWarranty()
    {
        $I = $this->tester;

        $I->checkElementOnPage(self::XPATH_PROFILE . '/li[1][@class="selected"]', 'profile selected');
        $this->checkWarrantyButton();
    }

    /**
     * Переход на вкладку Гарантийный раздел.
     *
     * @throws \Exception
     */
    public function selectProfileWarranty()
    {
        $I = $this->tester;

        $this->checkWarrantyButton();
    }

    /**
     * Ввод номера документа в поле во вкладке Гарантийный раздел.
     *
     * @param string $param текст ответа
     * @throws \Exception
     */
    public function fillDocumentNumber($param)
    {
        $I = $this->tester;

        $I->waitAndFill(['id' => 'warranty_number'], 'fiil document number', $param);
        $I->waitAndClick('//div[@class="warranty-form_submit"]/button', 'send form');
    }

    /**
     * Проверяет состояние кнопки Сохранить в блоке Оповещения, до и после клика по чекбоксу в этом блоке.
     *
     * @throws \Exception Если не верно отображается доступность кнопки Сохранить
     */
    public function checkProfileButton()
    {
        $I = $this->tester;
        $button = $I->grabAttributeFrom(self::NOTIFICATION_SETTINGS_BUTTON, 'disabled');
        codecept_debug($button);
        if ($button != true) {
            Throw new \Exception('Enable SAVE button!');
        }
        $I->waitAndClick('//ul[@class="checkboxes_list notifications"]/li[4]', 'checkbox dont recive notification');
        $button = $I->grabAttributeFrom(self::NOTIFICATION_SETTINGS_BUTTON, 'disabled');
        codecept_debug($button);
        if ($button == true) {
            Throw new \Exception('Disable SAVE button!');
        }
    }

    /**
     * Проверка страницы гарантии Личного кабинета.
     *
     * @throws \Exception
     */
    public function checkWarrantyButton()
    {
        $I = $this->tester;

        $I->waitAndClick(self::XPATH_PROFILE . '/li[7]', 'warranty button');
        $I->checkElementOnPage(self::XPATH_PROFILE . '/li[7][@class="selected"]', 'warranty button selected');
    }

    /**
     * Проверка страницы Активность Личного кабинета.
     *
     * @throws \Exception
     */
    public function checkActionButton()
    {
        $I = $this->tester;

        $this->goToProfileAction();
        $arrayCollection = $I->grabMultipleDesc('//div[@class="subnavigation important_block alt2_links no_visited columns"]/ul', 'nav panel in action');
        codecept_debug($arrayCollection);
        if (count($arrayCollection) > 13) {
            unset($arrayCollection[13]);//не кликаем на Список желаний
        }
        unset($arrayCollection[10]);//не кликаем на барахолку
        foreach ($arrayCollection as $key => $value) {
            $key = $key + 1;
            $I->waitAndClick('//div[@class="subnavigation important_block alt2_links no_visited columns"]/ul[' . $key . ']', 'navigation action panel button');
            $I->checkElementOnPage('//div[@class="subnavigation important_block alt2_links no_visited columns"]/ul[' . $key . ']/li[@class="selected"]',
                $value."selected");
        }
    }

    /**
     * Активация или отключение видимости списка желаний.
     *
     * @param string $turn active или disable
     * @throws \Exception
     */
    public function activateOrDisableWishListVisibility($turn)
    {
        $I = $this->tester;

        $visibility = $I->getNumberOfElements('//div[@class="SharingBlock__social js--SharingBlock SharingBlock__hidden"]',
            'disabled block');
        codecept_debug($visibility);

        if ($turn == 'active'){
            if ($visibility != 0){
                $I->waitAndClick('//div[@class="SharingBlock__checkbox"]//label', 'disable visibility');
            }
        }elseif ($turn == 'disable'){
            if ($visibility != 1){
                $I->waitAndClick('//div[@class="SharingBlock__checkbox"]//label', 'active visibility');
            }
        }
    }

    /**
     * Получение ссылки на список желаний.
     *
     * @return string ссылку на список желаний
     * @throws \Exception
     */
    public function getWishListLink()
    {
        $I = $this->tester;

        $I->waitForElementVisible('//div[@class="SharingBlock__link"]/a');
        $wishListLink = $I->grabAttributeFrom('//div[@class="SharingBlock__link"]/a', 'href');

        return $wishListLink;
    }

    /**
     * Проверка наличия дублирования услуги доставки в оформленном заказе. Вызов из личного кабинета.
     *
     * @throws \Exception
     */
    public function checkDuplicateDelivery()
    {
        $I = $this->tester;

        $I->waitAndClick(self::XPATH_PROFILE . '/li[2]', 'Orders button');
        $arrayName = $I->grabMultipleDesc('//tbody/tr[@class="product_data__gtm-js order_data"]', "имя товара\услуги");
        codecept_debug("Dirty array: ");
        codecept_debug($arrayName);
        $arrayFilter = array_filter($arrayName);
        codecept_debug("Clean array: ");
        codecept_debug($arrayFilter);
        $numberFilter = count($arrayFilter);
        codecept_debug("Count in Filter array: " . $numberFilter . "");
        $arrayUnique = array_unique($arrayFilter);
        codecept_debug("Unique array: ");
        codecept_debug($arrayUnique);
        $numberUnique = count($arrayUnique);
        codecept_debug("Count in Unique array: " . $numberUnique . "");
        if ($numberFilter != $numberUnique) {
            Throw new \Exception('Delivery duplication');
        }
    }

    /**
     * Добавление в корзину всех товаров из Желаний, проверка суммы корзины
     *
     * @return int $currentProductPrice цена товаров
     * @throws \Exception Если сумма корзины изменилась не на сумму товаров добавленных в корзину
     */
    public function addToCartFromWishListAll()
    {
        $I = $this->tester;

        $I->lookForwardTo('add to cart from wish list');
        $arrayPrice = $I->grabMultiple(['xpath' => self::XPATH_PRODUCT_CART_WISHILIST . '//span[@class="ProductCardFavourites__price-current_current-price"]']);
        foreach ($arrayPrice as $key => &$value) {
            $value = preg_replace("/\s/", "", $value);
        }

        codecept_debug($arrayPrice);
        $currentProductPrice = array_sum($arrayPrice);
        codecept_debug('overall price for wishlist items is ' . $currentProductPrice);
        $I->waitAndClick('//span[contains(.,"Купить все")]', 'move wish to cart', true);
        $I->waitForElementNotVisible(self::XPATH_PRODUCT_CART_WISHILIST);

        return $currentProductPrice;
    }

    /**
     * Добавление в корзину одного товара из желаний, проверка суммы корзины
     *
     * @return int $currentProductPrice цена товара
     * @throws \Exception Если сумма корзины изменилась не на сумму товаров добавленных в корзину
     */
    public function addToCartFromWishList()
    {
        $I = $this->tester;

        $I->lookForwardTo('add to cart from wish list');
        $number = $I->getNumberOfElements(self::OWN_WISHLIST, 'item in wish list');
        $item = mt_rand(1, $number);
        $currentProductPrice = $I->getNumberFromLink(self::XPATH_PRODUCT_CART_WISHILIST . '[' . $item . ']//span[@class="ProductCardFavourites__price-current_current-price"]',
            'get current Product Price');
        $I->lookForwardTo("**** add to cart product " . $item . " from wish page");
        $I->waitAndClick(self::XPATH_PRODUCT_CART_WISHILIST . '[' . $item . ']//div[@class="ProductCardFavourites__basket-button"]',
            'move wish to cart', true);

        return $currentProductPrice;
    }

    /**
     * Переход в карточку товара из Заказов в профиле.
     *
     * @throws \Exception
     */
    public function goToProductFromOrders()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to product from orders');
        $numberExp = $I->getNumberOfElements('//div[@class="main_content_inner"]/' . self::XPATH_ORDER_ITEM, 'expandable order');
        $numberExpRnd = mt_rand(1, $numberExp);
        codecept_debug("random number for order '$numberExpRnd'");
        $orderNumber = $I->grabAttributeFrom(['xpath' => '//' . self::XPATH_ORDER_ITEM . '[' . $numberExpRnd . ']'], 'id');
        codecept_debug("order number '$orderNumber'");
        $I->scrollTo(['xpath' => '//' . self::XPATH_ORDER_ITEM . '[' . $numberExpRnd . ']//td[@class="order_brif_info"]']);
        $I->lookForwardTo("expand ***'$numberExpRnd'*** item");
        $I->waitAndClick('//' . self::XPATH_ORDER_ITEM . '[' . $numberExpRnd . ']//td[@class="order_brif_info"]/span', "expandable order number: ***$numberExpRnd***");
        $numberProduct = $I->getNumberOfElements('//div[@id="' . $orderNumber . '"]//table[@class="order order_content"]/tbody/tr[@class="product_data__gtm-js product_data__pageevents-js order_data"]/td[@class="product_name"]/a',
            'products in order');
        $numberProductRnd = mt_rand(1, $numberProduct);
        codecept_debug("product number in order '$numberProductRnd'");
        $I->waitAndClick('//div[@id="' . $orderNumber . '"]//tr[@class="product_data__gtm-js product_data__pageevents-js order_data"][' . $numberProductRnd . ']/td[@class="product_name"]/a',
            "product link number: ***$numberProductRnd*** , ***$orderNumber***");
    }

    /**
     * Переход в список заказов в профиле.
     *
     * @throws \Exception
     */
    public function goToOrders()
    {
        $I = $this->tester;

        $I->waitAndClick(self::XPATH_PROFILE . '/li[2]', 'orders button');
        $I->checkElementOnPage(self::XPATH_PROFILE . '/li[2][@class="selected"]', 'orders button selected');
    }

    /**
     * Переход на вкладку Вопросы-Ответы в профиле другого пользователя.
     *
     * @throws \Exception
     */
    public function goToAnswersOther()
    {
        $I = $this->tester;

        $I->waitAndClick('//li[.="Вопросы и ответы"]', 'answers button');
        $I->waitForElementVisible('//div[@class="review with_image"]');
    }

    /**
     * Проверка блока пагинации.
     *
     * @param string $reviewsTitle путь к заголовку обзора
     * @return int $pagination ниличие блока пагинации
     * @throws \Exception Проверка выбранной страницы
     */
    public function checkPagination($reviewsTitle)
    {
        $I = $this->tester;

        $I->lookForwardTo('check that the first page is selected');
        $pagination = $I->getNumberOfElements('//section[@class=" PaginationWidget"]');
        if ($pagination != 0) {
            $selected = $I->getNumberOfElements(['xpath' => '//section[@data-current-page-number="1"]']);
            codecept_debug("The first page is " . $selected . "");
            if ($selected != 1) {
                Throw new \Exception('The first page is not selected');
            }

            $arrayFirstPage = $I->grabMultiple($reviewsTitle);
            codecept_debug($arrayFirstPage);
            $I->lookForwardTo('check that the second page is selected');
            $next = $I->grabTextFrom('//a[contains(@class,"PaginationWidget__page_next")]');
            $I->waitAndClick('//a[contains(@class,"PaginationWidget__page_next")]', "second page");
            $I->waitForElementVisible('//span[contains(@class,"PaginationWidget__page_current")][contains(.,"'.$next.'")]');
            $I->waitForElementVisible('//a[contains(@class,"PaginationWidget__page_previous")][contains(.,"1")]');
            $arraySecondPage = $I->grabMultiple($reviewsTitle);
            codecept_debug($arraySecondPage);
            if ($arrayFirstPage == $arraySecondPage) {
                Throw new \Exception('List of products was not changed');
            }

            $more = $I->getNumberOfElements('//a[contains(@class,"PaginationWidget__page_more")]');
            if ($more != 0) {
                $I->lookForwardTo('check selected three dots page');
                $I->waitAndClick('//a[contains(@class,"PaginationWidget__page_more")]', "three dots page");
                $I->waitForElementVisible('//a[contains(@class,"PaginationWidget__page_more")][not(contains(.,"'.$next.'"))]');
                $I->waitForElementVisible('//a[contains(@class,"PaginationWidget__page_less")]');
                $arrayDotsPage = $I->grabMultiple($reviewsTitle);
                codecept_debug($arrayDotsPage);
                if ($arrayDotsPage == $arraySecondPage) {
                    Throw new \Exception('List of products was not changed');
                }

                $I->lookForwardTo('check that the left dots page is selected');
                $I->waitAndClick('//a[contains(@class,"PaginationWidget__page_less")]', "The left dots");
                $I->waitForElementNotVisible('//a[contains(@class,"PaginationWidget__page_less")]');
                $arrayLeftDotsPage = $I->grabMultiple($reviewsTitle);
                codecept_debug($arrayLeftDotsPage);
                if ($arrayDotsPage == $arrayLeftDotsPage) {
                    Throw new \Exception('List of products was not changed');
                }

                $arraySecondPage = $arrayLeftDotsPage;
            }

            $I->lookForwardTo('check that the last page is selected');
            $lastPage = $I->grabTextFrom('//a[contains(@class,"PaginationWidget__page_last")]');
            $I->waitAndClick('//a[contains(@class,"PaginationWidget__page_last")]', "The last page");
            $I->waitForElementVisible('//span[contains(@class,"PaginationWidget__page_current")][contains(.,"'.$lastPage.'")]');
            $arrayLastPage = $I->grabMultiple($reviewsTitle);
            codecept_debug($arrayLastPage);
            if ($arrayLastPage == $arraySecondPage) {
                Throw new \Exception('List of products was not changed');
            }

            $I->lookForwardTo('check that the first page is selected');
            $I->waitAndClick('//a[contains(@class,"PaginationWidget__page_first")]', "The first page");
            $I->waitForElementVisible('//span[contains(@class,"PaginationWidget__page_current")][contains(.,"1")]');
            $arrayFirstPage = $I->grabMultiple($reviewsTitle);
            codecept_debug($arrayFirstPage);
            if ($arrayLastPage == $arrayFirstPage) {
                Throw new \Exception('List of products was not changed');
            }
        }

        return $pagination;
    }

    /**
     * Переход на карточку товара из Вопросов-Ответов.
     *
     * @throws \Exception
     */
    public function goToProductFromProductListingAnswer()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to product from answer');
        $reviewsCount = $I->getNumberOfElements(self::XPATH_REVIEW, 'products in listing');
        $numberRnd = mt_rand(1, $reviewsCount);
        $I->waitAndClick('//div[@class="review with_image"][' . $numberRnd . ']/article//a',
            "product number *** $numberRnd *** ");
    }

    /**
     * Добавление нового адреса доставки в ЛК.
     *
     * @throws \Exception
     */
    public function addDeliveryAddress()
    {
        $I = $this->tester;;
        //скроллим к кнопке Добавить адрес
        $addressCount = $I->getNumberOfElements(self::XPATH_DELIVERY_ADDRESS);

        $I->scrollTo(['xpath' => '//span[text()="Добавить адрес"]']);
        //нажимаем Добавить адрес - раскрывается форма добавления нового адреса
        $I->waitAndClick('//span[text()="Добавить адрес"]', "add address link");
        //заполняем поля адреса
        $I->waitAndFill(['id' => 'streetNew'], "street name", BUYER_STREET);
        $I->waitAndFill(['id' => 'houseNew'], "house number", BUYER_HOUSE);
        $I->waitAndFill(['id' => 'contactNameNew'], "street name", BUYER_NAME . " " . BUYER_FAMILY_NAME);
        $I->waitAndFill(['id' => 'phoneNew'], "phone number", BUYER_PHONE);
        $I->waitAndFill(['id' => 'additionalPhoneNew'], "second phone number", BUYER_PHONE);
        //нажимаем кнопку Сохранить, форма должна закрыться
        $I->waitAndClick(['class' => 'profile__title'], "delivery address title");
        $I->wait(SHORT_WAIT_TIME);
        $I->waitAndClick('//div[@class="profile-address-item__form-col profile-address-item__form-col_content"]//button', "save button");
        //ждем закрытия формы
        //проверяем, что ссылка Добавить адрес не выбрана
        $I->checkElementOnPage('//span[@class="pseudo add_new_address add_new_item expandable_control for_new_address"]', "add address link collapsed");
        $newAddressCount = $I->getNumberOfElements(self::XPATH_DELIVERY_ADDRESS);
        if ($addressCount >= $newAddressCount){
            Throw new \Exception('Address was not added');
        }
    }

    /**
     * Проверка корректного сохранения адресса.
     *
     * @throws \Exception
     */
    public function checkSaveAddress()
    {
        $I = $this->tester;

        $default = 'Москва, '.BUYER_STREET.' , дом '.BUYER_HOUSE.' , тел.: '.BUYER_PHONE.' , '.BUYER_PHONE;
        $newAddressNum = $I->getNumberOfElements(self::XPATH_DELIVERY_ADDRESS, 'delivery addresses');
        $newAddress = $I->grabTextFrom(self::XPATH_DELIVERY_ADDRESS.'['.$newAddressNum.']/span');
        $I->waitAndClick(self::XPATH_DELIVERY_ADDRESS.'['.$newAddressNum.']/span', 'open new address information');
        $bool =  mb_check_encoding($newAddress, 'UTF-8');
        $I->seeValueIsTrue($bool);
        $I->seeThatValuesAreEquals($default,$newAddress);
    }

    /**
     * Изменение адреса доставки в ЛК.
     *
     * @throws \Exception
     */
    public function editDeliveryAddress()
    {
        $I = $this->tester;;
        //скроллим к последнему адресу
        $I->scrollTo(['xpath' => self::XPATH_DELIVERY_ADDRESS.'[last()]/span[@class="pseudo expandable_control"][last()]']);
        //нажимаем на последнем адресе
        $I->waitAndClick(self::XPATH_DELIVERY_ADDRESS.'[last()]/span[@class="pseudo expandable_control"]', "last address link");
        //нажимаем кнопку Редактировать
        $I->waitAndClick(self::XPATH_DELIVERY_ADDRESS.'[last()]//div[@class="form_item edit_block"]//button', "edit button");
        $I->wait(SHORT_WAIT_TIME);
        //заполняем поля адреса
        $I->scrollTo(self::XPATH_DELIVERY_ADDRESS.'[last()]//input[@id="street"]');
        $I->waitAndFill(self::XPATH_DELIVERY_ADDRESS.'[last()]//input[@id="street"]', "street name", BUYER_STREET . " " . BUYER_STREET);
        $I->waitAndFill(self::XPATH_DELIVERY_ADDRESS.'[last()]//input[@id="house"]', "house number", BUYER_HOUSE . BUYER_HOUSE);
        $I->waitAndFill(self::XPATH_DELIVERY_ADDRESS.'[last()]//input[@id="contactName"]', "contact name", BUYER_FAMILY_NAME . " " . BUYER_NAME);
        $I->waitAndFill(self::XPATH_DELIVERY_ADDRESS.'[last()]//input[@id="phone"]', "phone number", BUYER_PHONE);
        //нажимаем кнопку Сохранить, форма должна закрыться
        $I->wait(SHORT_WAIT_TIME);
        $I->waitAndClick('//form[@class="profile-address-item__form pretty_form addresses_list__item editing_mode"]//div[@class="profile-address-item__form-col_1-2"]//button[@class="pretty_button type6 save-button-delivery_js"]',
            "save button");
        //нажимаем на заголовок адреса
        $I->waitAndClick(self::XPATH_DELIVERY_ADDRESS.'[last()]/span[@class="pseudo expandable_control selected"]', "last address link");
        //проверяем, что ссылка Добавить адрес не выбрана
        $I->waitForElement('//span[@class="pseudo add_new_address add_new_item expandable_control for_new_address"]');
    }

    /**
     * Удаление сохраненных адресов доставки в ЛК.
     *
     * @throws \Exception
     */
    public function deleteDeliveryAddresses()
    {
        $I = $this->tester;

        $I->goToProfile();
        while ($I->getNumberOfElements(self::XPATH_DELIVERY_ADDRESS.'/span[@class="pseudo expandable_control"]') > 0) {
            //скроллим к первому адресу
            $I->scrollTo(['xpath' => self::XPATH_DELIVERY_ADDRESS.'/span[@class="pseudo expandable_control"]']);
            //нажимаем на последнем адресе
            $I->waitAndClick(self::XPATH_DELIVERY_ADDRESS.'/span[@class="pseudo expandable_control"]', "last address link");
            //нажимаем кнопку Редактировать
            $I->waitAndClick(self::XPATH_DELIVERY_ADDRESS.'//div[@class="form_item edit_block"]//button', "edit button");
            $I->wait(SHORT_WAIT_TIME);
            //нажимаем ссылку Удалить адрес
            $I->waitAndClick('//form[@class="profile-address-item__form pretty_form addresses_list__item editing_mode"]//div[@class="remove_address_control pseudo"]',
                "delete address link");
            //проверяем, что ссылка Добавить адрес не выбрана
            while ($I->getNumberOfElements('//span[@class="pseudo expandable_control selected"]') > 0) {
                $I->wait(SHORT_WAIT_TIME);
            }
        }
    }


    /**
     * Удаление последнего адреса доставки в ЛК.
     *
     * @throws \Exception
     */
    public function deleteDeliveryAddress()
    {
        $I = $this->tester;
        //скроллим к последнему адресу
        $I->scrollTo(['xpath' => self::XPATH_DELIVERY_ADDRESS.'[last()]/span[@class="pseudo expandable_control"][last()]']);
        //нажимаем на последнем адресе
        $I->waitAndClick(self::XPATH_DELIVERY_ADDRESS.'[last()]/span[@class="pseudo expandable_control"]', "last address link");
        //нажимаем кнопку Редактировать
        $I->waitAndClick(self::XPATH_DELIVERY_ADDRESS.'[last()]//div[@class="form_item edit_block"]//button', "edit button");
        $I->wait(SHORT_WAIT_TIME);
        //нажимаем ссылку Удалить адрес
        $I->waitAndClick('//form[@class="profile-address-item__form pretty_form addresses_list__item editing_mode"]//div[@class="remove_address_control pseudo"]',
            "delete address link");
        //проверяем, что ссылка Добавить адрес не выбрана
        $I->waitForElement('//span[@class="pseudo add_new_address add_new_item expandable_control for_new_address"]');
    }

    /**
     * Получение даты рождения из личного кабинета.
     *
     * @return array Дата рождения День, Месяц, Год
     */
    public function getBirthDate()
    {
        $I = $this->tester;

        $I->lookForwardTo('get birth date');
        $day = $I->grabValueFrom(['id' => 'userDay']);
        $month = $I->grabValueFrom(['id' => 'userMonth']);
        $year = $I->grabValueFrom(['id' => 'userYear']);
        $date = array($day, $month, $year);

        return $date;
    }

    /**
     * Установка даты рождения в личном кабинете.
     *
     * @param string $date Дата рождения День, Месяц, Год
     * @throws \Exception
     */
    public function setBirthDate($date)
    {
        $I = $this->tester;

        $I->lookForwardTo('set birth date');
        $I->waitAndFill(['id' => 'userDay'], 'day', $date[0]);
        $I->waitAndFill(['id' => 'userMonth'], 'month', $date[1]);
        $I->waitAndFill(['id' => 'userYear'], 'year', $date[2]);
    }

    /**
     * Активация блока редактирования личных данных.
     *
     * @throws \Exception
     */
    public function editPersonalData()
    {
        $I = $this->tester;

        $I->lookForwardTo('edit birth date');
        $I->waitAndClick('//button[contains(@class,"js--personal-data-form__edit-btn")]',
            'edit personal data button', true);
        $I->wait(SHORT_WAIT_TIME);
        $I->checkElementOnPage(self::PERSONAL_DATA_BUTTON, 'edit personal data button block');
    }

    /**
     * Сохранение изменений личных данных в личном кабинете.
     *
     * @throws \Exception
     */
    public function savePersonalData()
    {
        $I = $this->tester;

        $I->lookForwardTo('save birth date');
        $I->checkElementOnPage('//form[@class="js--personal-data-form inline-form private_data__js"]', 'edit personal data button block');
        $I->waitAndClick('//button[contains(@class,"js--personal-data-form__save-btn")]',
            'edit personal data button', true);
        $I->wait(SHORT_WAIT_TIME);
        $I->checkElementOnPage(self::PERSONAL_DATA_BUTTON, 'edit personal data button block');
    }

    /**
     * Переход в карточку товара из последнего заказа.
     *
     * @throws \Exception
     */
    public function goToProductFromLastOrder()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to product from last order');
        $I->waitAndClick('//a[@class="link_gtm-js link_pageevents-js"]', "first product in last order");
    }

    /**
     * Смена пароля.
     *
     * @param string $oldPass Старый пароль
     * @param string $newPass Новый пароль
     * @throws \Exception
     */
    public function changePassword($oldPass, $newPass)
    {
        $I = $this->tester;

        $I->waitAndFill(['id' => 'old_password'], 'old password', $oldPass);
        $I->waitAndFill(['id' => 'new_password'], 'new password', $newPass);
        $I->waitAndFill(['id' => 'repeat_new_password'], 'new password', $newPass);
        $I->waitAndClick('//form[@class="pretty_form without_background change_password"]//button[@type="submit"]', 'save new password');
        $I->wait(SHORT_WAIT_TIME);
    }

    /**
     * Переход на страницу профиля по id.
     *
     * @param string $id IL01234567
     * @throws \Exception
     */
    public function goToProfileById($id)
    {
        $I = $this->tester;

        $I->wantTo("open profile $id");
        $I->amOnPage("/profile/$id/");
        $I->checkElementOnPage('//h1[contains(.,"Профиль пользователя")]', 'profile title');
    }

    /**
     * Отправка сообщения пользователю.
     *
     * @param string $message Текст сообщения
     * @throws \Exception
     */
    public function sendPrivateMessage($message)
    {
        $I = $this->tester;

        $I->waitAndClick('//a[contains(.,"Написать сообщение")]', 'add new message');
        $I->checkElementOnPage('//form[@class="comment_form"]/textarea[@name="message"]', 'message form');
        $I->waitAndFill('//form[@class="comment_form"]/textarea[@name="message"]', 'message text', $message);
        $I->waitAndClick('//form[@class="comment_form"]/button[@type="submit"][contains(.,"Отправить")]', 'send message');
    }

    /**
     * Получение userId из url страницы Активности.
     *
     * @return mixed userId
     */
    public function getUserId()
    {
        $I = $this->tester;

        return $I->grabFromCurrentUrl('/((IL)(\d+))/');
    }

    /**
     * Переход на старницу Активность в профиле.
     *
     * @throws \Exception
     */
    public function goToProfileAction()
    {
        $I = $this->tester;

        $I->waitAndClick(self::XPATH_PROFILE . '/li[4]', 'action button');
        $I->checkElementOnPage(self::XPATH_PROFILE . '/li[4][@class="selected"]', 'action button selected');
    }

    /**
     * Изменение контактного телефона, с помощью текущего номера телефона.
     * Авторизация по номеру телефона, требуется указывать почту профиля.
     *
     * @param string $newPhone
     * @param string $phone Текущий номер телефона в профиле
     * @param bool $resend повторная отправка кода
     * @param string $email Текущая почта в профиле
     * @throws \Exception
     */
    public function editContactPhoneNumber($newPhone, $phone, $email, $resend)
    {
        $I = $this->tester;

        $this->inputNewPhone($newPhone);
        $this->editByDefaultPhone($phone, $email, $resend);
    }

    /**
     * Ввод и подтверждение нового номера телефона.
     *
     * @param string $newPhone
     * @throws \Exception
     */
    public function inputNewPhone($newPhone)
    {
        $I = $this->tester;

        $I->waitAndClick('//div/a[@class="js--profile__edit-phone-btn"]', 'edit phone number button', true);
        $I->wait(SHORT_WAIT_TIME);
        $I->waitAndFill(['id' => 'wizard-new-email-or-phone'], 'new phone', $newPhone, true);
        $I->clearSmsByPhone($newPhone);
        $I->waitAndClick(['class' => 'wizard-popup__title'], 'edit phone number main label', true);
        $I->wait(SHORT_WAIT_TIME);
        $I->waitAndClick('//div[@class="wizard-inputting__line"]/button[contains(.,"Подтвердить номер телефона")]', 'confirm new phone', true);
        $I->wait(SHORT_WAIT_TIME);
        $code = $I->getSmsCode($newPhone);
        codecept_debug('in test ' . $code);
        $I->waitAndFill('//div[@class="wizard-group-input"]//input', 'sms code', $code, true);
        $I->waitAndClick(['class' => 'wizard-popup__title'], 'edit phone number main label', true);
        $I->wait(SHORT_WAIT_TIME);
        $I->waitAndClick('//div[@class="wizard-group-input"]/button', 'confirm button', true);
    }

    /**
     * Выбор подтверждения изменений через текущий номер телефона.
     *
     * @param string $phone Текущий номер телефона в профиле
     * @param bool $resend повторная отправка кода
     * @param string $email Текущая почта в профиле
     * @throws \Exception
     */
    public function editByDefaultPhone($phone, $email, $resend)
    {
        $I = $this->tester;

        $I->waitForText('По текущему номеру телефона');
        $I->waitAndClick('//ul[@class="wizard-list"]/li/button[contains(., "По текущему номеру телефона")]', 'by default phone number', true);
        $I->waitAndFill('//div[@class="wizard-group-input"]//input[@id="wizard-sms-field"]', 'email', $email, true);
        $I->waitAndClick(['class' => 'wizard-popup__title'], 'edit phone number main label', true);
        $I->wait(SHORT_WAIT_TIME);
        $I->clearSmsByPhone($phone);
        $I->waitAndClick('//div[@class="wizard-confirming__btn-wrapper"]/button', 'confirm email', true);
        $I->wait(SHORT_WAIT_TIME);
        if ($resend == true){
            $I->clearSmsByPhone($phone);
            $I->resendSmsCodeForProfile();
        }

        $code = $I->getSmsCode($phone);
        codecept_debug('in test ' . $code);
        $I->waitAndFill('//div[@class="wizard-group-input"]/span/input', 'sms code', $code, true);
        $I->waitAndClick('//div[@class="wizard-group-input"]/button', 'confirm button', true);
    }

    /**
     * Создание черновика обзора.
     *
     * @param string $header Текст заголовка
     * @param string $inner Текст тела обзора
     * @throws \Exception
     */
    public function createDraftReview($header, $inner)
    {
        $I = $this->tester;

        $I->appendField('//input[@name="name"]', $header);
        $I->fillByInnerHtml('//div[@id="editor-body"]//div[@class="tui-editor-contents tui-editor-contents-placeholder"]/div', $inner);
        $I->waitAndClick('//span[contains(.,"Сохранить")]', 'save draft review');
    }

    /**
     * Переход к списку обзоров.
     *
     * @throws \Exception
     */
    public function goToListingReview()
    {
        $I = $this->tester;

        $I->waitAndClick('//span[@class="navigation_item"]/a[contains(.,"Список обзоров")]', 'listing reviews link');
        $I->waitForText('Неопубликованные обзоры');
    }

    /**
     * Переход к конкретному черновику обзора.
     *
     * @param string $header Текст заголовка
     * @param string $inner Текст тела обзора
     * @throws \Exception
     */
    public function goToEditDraftReview($header, $inner)
    {
        $I = $this->tester;

        $I->waitAndClick('//div[@class="review with_image"]//h5//a[contains(.,"' . $header . '")]', 'header reviews link');
        $I->waitForText($inner);
    }

    /**
     * Удаление конкретного черновика обзора.
     *
     * @param string $header Текст заголовка
     * @throws \Exception
     */
    public function deleteDraftReview($header)
    {
        $I = $this->tester;

        $I->waitAndClick('//tr/td/a[contains(.,"' . $header . '")]/../../td[@class="remove_container"]', 'delete button');
        $I->acceptPopup();
        $I->dontSee($header);
    }

    /**
     * Поиск по списку заказов.
     *
     * @param string $searchString Поисковый запрос
     * @throws \Exception
     */
    public function searchInOrdersList($searchString)
    {
        $I = $this->tester;

        $I->waitAndFill(['name' => 'search'], 'search input', $searchString);
        $I->waitAndClick('//div[@class="search-wraper"]/button', 'search enter button');
        $I->waitAndClick('//td[@class="order_brif_info"]/span', 'expand order');
        $I->wait(SHORT_WAIT_TIME);
        $I->see($searchString, '//div[contains(@class, "selected")]');
    }

    /**
     * Проверка отображения листа доверенности.
     *
     * @throws \Exception
     */
    public function checkB2bProcuration()
    {
        $I = $this->tester;

        if (($I->getNumberOfElements('//div[@class="orders-list-control orders-list-control_js"]/span[contains(.,"развернуть")]')) > 0){
            $I->waitAndClick('//div[@class="orders-list-control orders-list-control_js"]/span', 'open orders list');
        }elseif(($I->getNumberOfElements('//span[contains(@class,"pseudo expandable_control")]')) > 0){
            $I->waitAndClick('//span[contains(@class,"pseudo expandable_control")]', 'open order');
        }

        $I->waitAndClick('//a[@class="pretty_button"]', 'open procuration');
        $I->checkElementOnPage('//div[@class="power-of-attorney power-of-attorney__js"]');
        $I->moveBack();
        $I->cancelOrderB2B();
    }

    /**
     * Проверка списка желаний на видимость для других пользователей.
     *
     * @param array $wishListItems товары из списка желаний
     * @param string $wishListLink ссылка на список желаний
     * @param string $visibility установленная видимость
     * @param string $turnOff закрытие окна
     */
    public function checkWishListForVisibility($wishListLink, $visibility, $wishListItems = null, $turnOff = null)
    {
        $I = $this->tester;

        $unknownUser = $I->haveFriend('nick');
        $unknownUser->does(function(\AcceptanceTester $I) use (&$wishListItems, &$wishListLink, &$visibility) {
            $I->amOnUrl($wishListLink);

            if ($visibility == 'active'){
                if (empty($wishListItems)) {
                    $I->checkElementOnPage(['class' => 'not_found_block']);
                }else {
                    $unknownUserWishList = $I->getItemsFromWishList(self::OTHER_WISHLIST);
                    $I->checkThatArraysAreEqual($wishListItems, $unknownUserWishList);
                }
            }elseif ($visibility == 'disable'){
                $I->checkElementOnPage(['class' => 'activity_list']);
            }
        });

        codecept_debug($turnOff);
        if ($turnOff == true){
            $unknownUser->leave();
        }
    }

    /**
     * Получение всех товаров из списка желаний.
     *
     * @param string $wishListXpath ссылка на список товаров
     * @return array список товаров в желаниях
     */
    public function getItemsFromWishList($wishListXpath)
    {
        $I = $this->tester;

        return $wishList = $I->grabMultiple($wishListXpath);
    }

    /**
     * Переход на вкладку обзоры.
     *
     * @throws \Exception
     */
    public function goToReviewsTab()
    {
        $I = $this->tester;

        $I->waitAndClick('//div[contains(@class,"subnavigation")]//a[.="Обзоры"]', 'reviews link');
        $I->waitForElementVisible('//li[@class="selected"][.="Обзоры"]');
    }

    /**
     * Проверка наличия обзоров.
     *
     * @return array $reviewLinks
     * @throws \Exception
     */
    public function checkActiveReviews()
    {
        $I = $this->tester;

        $reviewLinks = [];
        $reviewsCount = $I->getNumberOfElements('//div[@class="review with_image"]', 'reviews');
        if ($reviewsCount != 0){
            for($i = 1; $i <= $reviewsCount; $i++)
            array_push($reviewLinks, $I->grabAttributeFrom('//div[@class="review with_image"]['.$i.']//h4/a', 'href'));
        }

        return $reviewLinks;
    }

    /**
     * Получение идентификатора обзора со страницы обзора.
     *
     * @return mixed userId
     */
    public function getReviewId()
    {
        $I = $this->tester;

        return $I->grabFromCurrentUrl('/((rev)(\d+))/');
    }


    /**
     * Получить идентификатор обзора из ссылки.
     *
     * @param string $link
     * @return string $revId
     */
    public function getReviewIdFromListing($link)
    {
        $matches = [];
        preg_match("/((rev)(\d+))/", $link, $matches);
        $revId = $matches[0];
        codecept_debug($revId);

        return $revId;
    }
}
