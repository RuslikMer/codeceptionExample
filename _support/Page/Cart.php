<?php
namespace Page;

class Cart
{
    // include url of current page
    public static $URL = '/order/';
    const PRODUCT_PAGE_NAME = 'ProductCardForBasket__name';
    const PRODUCT_PAGE_IMG = 'ProductCardForBasket__image-wrap';
    //путь к блоку услуг страховки у товара
    const DIGITAL = 'digitalService';
    const INSURANCE = 'cardifService';
    const INSTALL = 'installService';

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

    //блок доставки КБТ
    const X_KBT_CLASS = '//article[@class="delivery_options"]/div[@class="lifting-options-form"]';
    //Чекбокс - подъем на лифте
    const X_KBT_ELEVATOR = self::X_KBT_CLASS . '//input[@id="delivery_liftingOptions_isCargoElevator"]';
    //Чекбокс подъем на этаж
    const X_KBT_UPTOSTAGE = self::X_KBT_CLASS . '//p/input';
    //Клик по чекбоксу - подъем на этаж
    const X_KBT_UPTOSTAGE_CLICK = self::X_KBT_CLASS . '//label[@for="delivery_liftingOptions_enable"]';
    const X_KBT_STAGE_INPUT = '//input[@id="delivery_liftingOptions_floor"]'; // Инпут номер этажа
    //список пунктов самовывоза
    const XPATH_STORES_LIST = '//table[@class="stores"]/tbody/tr[@class="store-item"]/td[@class="address"]';
    //Выбор ротатора
    const ROTATOR_USEFUL = 'Может пригодиться в корзине';
    const ROTATOR_WITH_IT = 'С товаром покупают в корзине';
    //виджет с содержимым корзины
    const CART_COUNT = '//div[contains(@class,"HeaderMenu__button_basket")]';
    //меню пользователя
    const USER_MENU_LINK = '//div[@class="HeaderMenu js--HeaderMenuMobile"]//div[contains(@class,"DropDown js--DropDown")]';
    //класс для содержимого корзины
    const CART_ITEMS_CLASS = '"block_data__gtm-js block_data__pageevents-js"';
    //путь к товару в корзине
    const XPATH_TABLE_ITEMS = '//div[@class="ProductListForBasket__item"]';
    //список товаров в корзине - тег таблицы
    const XPATH_TBODY_ITEMS = '//tbody[@class=' . self::CART_ITEMS_CLASS . ']';
    //класс содержимого правого черного блока
    const XPATH_CART_DETAILS = '//div[@class="cart_details js--cart-details cart-details"]';
    //класс содержимого правого черного блока с промокодом
    const XPATH_CART_DETAILS_PROMO = '//div[@class="cart_details"]';
    //сумма К ОПЛАТЕ из черного блока в Корзине
    const XPATH_SUMMA_K_OPLATE = self::XPATH_CART_DETAILS . '/table/tbody/tr/td[@class="price js--order-amount__amount"]/span[@class="price cart_cost"]/ins[@class="num js--order-amount__amount-num"]';
    //кнопка отмены заказа
    const XPATH_CANCEL_ORDER_BUTTON = '//button[@class="cancel_order_control pretty_button type2"]';
    //кнопка раскрытия списка заказов
    const XPATH_EXPAND_ORDERS_LIST = '//span[@class="pseudo expandable_control orders-list-control__all-expander orders-list-control__all-expander_js"]';
    const XPATH_INSURANCE_LIST = '//ul[contains(@class, "services_list")]/li';
    const XPATH_DIGITAL_LIST = '//ul[@class="services_list service-list"]//li';
    const XPATH_PROMO_CODE = '//div[@class="js--market-payments__promocode-form payment-promocode"]';
    const XPATH_DIGITAL_SERVICE = '//ul[@class="js--services-group__items-list services-group__items-list"]/li[@class="js--services-item js--digital-services-item digital-services-item"]';
    const XPATH_DIGITAL_SERVICE_NAME = '//span[@class="digital-services-item__title"]';
    const XPATH_DIGITAL_SERVICE_PRICE = '//span[@class="digital-services-item__price digital-services-item__price_main"]/ins[@class="digital-services-item__price-value"]';
    //кнопка отмены заказа B2B
    const XPATH_CANCEL_ORDER_BUTTON_B2B = '//a[contains(.,"Отменить заказ")]';
    //ссылка выбора другой точки самовывоза
    const XPATH_CHOOSE_OTHER_PICKPOINT = '//span[text()="Выбрать другое место самовывоза"]';
    const XPATH_STOCKS_LIST = '//a[contains(.,"В наличии")]'; //путь к списку точек наличия товара
    //ссылка на Список заказов из выпадающего меню авторизованного пользователя
    const XPATH_MYORDERS = self::USER_MENU_LINK . '//a[contains(.,"Заказы")]';

    /**
     * Удаление товара из корзины.
     *
     * @param int $itemNumber Номер товара в списке по порядку
     * @throws  \Exception
     */
    public function deleteItem($itemNumber)
    {
        $I = $this->tester;

        $I->lookForwardTo("**** remove item " . $itemNumber . " from cart");
        $I->checkElementOnPage(['class' => 'ProductListForBasket__item']);
        $I->waitAndClick(self::XPATH_TABLE_ITEMS . '['. $itemNumber .']//div[@class="ProductCardForBasket__icons"]/*[2]', 'delete item', true);
        $I->wait(SHORT_WAIT_TIME);
    }

    /**
     * Удаление всех товаров в корзине.
     *
     * @throws \Exception
     */
    public function deleteAll()
    {
        $I = $this->tester;

        $I->waitAndClick('//div[@class="OrderFinalPrice__empty-action"]/button', '**** remove all items from cart', true);
        $I->waitForElementVisible('//div[@class="Basket__basket-empty"]');
    }

    /**
     * Выбор курьерской доставки.
     *
     * @return int $notAvailableDelivery невозможность доставки
     * @throws \Exception
     */
    public function chooseCourierDelivery()
    {
        $I = $this->tester;

        $I->lookForwardTo("**** choose courier delivery");
        $I->waitAndClick('//ul[@class="switcher type1 radio_style text tabs tabs2 delivery_type_switcher"]/li[2]', 'delivery tab');
        $notAvailableDelivery = $I->getNumberOfElements('//div[@class="delivery_type delivery_type_0"][contains(.,"невозможна")]');

        return $notAvailableDelivery;
    }

    /**
     * Выбор доставки день в день.
     *
     * @throws \Exception
     */
    public function chooseSameDayDelivery()
    {
        $I = $this->tester;

        $I->lookForwardTo("**** choose same day delivery");
        $I->chooseCourierDelivery();
        if ( $I->getNumberOfElements('//input[@id="delivery_deliveryType_1" and @value="4"]') > 0 ) {
            if ($I->getNumberOfElements('//input[@id="delivery_deliveryType_1" and value="4" and @disabled="disabled"]') == 0) {
                $I->waitAndClick('//label[@for="delivery_deliveryType_1"]', 'same day delivery tab');
            } else {
                Throw new \Exception('Same day delivery tab disabled');
            }
        } else {
            Throw new \Exception('Same day delivery tab not found');
        }
    }

    /**
     * Получение текущей суммы товаров в Корзине (указывается на каждой странице сверху справа)
     *
     * @return int Сумма корзины
     */
    public function getTopRightAmountInitial()
    {
        $I = $this->tester;

        $I->lookForwardTo("get current cart amount");
        $grabbedCartAmount = $I->getNumberFromLink(['class'=>'OrderFinalPrice__price-block'], 'get current cart Amount');
        if (strlen($grabbedCartAmount) == 0) {
            $grabbedCartAmount = 0;
        }

        codecept_debug('current cart amount (from top right) is ' . $grabbedCartAmount);
        return $grabbedCartAmount;
    }

    /**
     * Получение суммы скидки в корзине.
     *
     * @return int $discount сумма скидки в корзине
     */
    public function getDiscount()
    {
        $I = $this->tester;

        $I->lookForwardTo("get current cart discount");
        $discount = $I->grabTextFrom('//tr[@class="js--cart-details__discounts-stock"]//ins[@class="num"]');

        return $discount;
    }

    /**
     * Получение текущей суммы товаров в Корзине (указывается на каждой странице сверху справа) после изменения.
     *
     * @return int Сумма корзины
     */
    public function getTopRightAmountAfterChange()
    {
        $I = $this->tester;

        $I->lookForwardTo("get current cart amount after change");
        $I->scrollTo('//*[@id="header_basket_cost"]');
        $I->waitForJS("return $.active == 1;", ELEMENT_WAIT_TIME);
        $I->waitForJS("return $.active == 0;", ELEMENT_WAIT_TIME);
        $grabbedCartAmount = $I->getNumberFromLink('//span[@class="basket-storage__cost"]/ins[@id="header_basket_cost"]', 'get current cart Amount');
        if (strlen($grabbedCartAmount) == 0) {
            $grabbedCartAmount = 0;
        }

        codecept_debug('current cart amount (from top right) is ' . $grabbedCartAmount);

        return $grabbedCartAmount;
    }

    /**
     * Переход в Корзину по ссылке сверху справа (виджет корзины).
     *
     * @throws \Exception
     */
    public function goToCartFromTopLink()
    {
        $I = $this->tester;

        $I->lookForwardTo("go to Cart from Right Top link");
        $productCountRightTop = $I->getNumberFromLink(self::CART_COUNT, 'top right product counter');
        $I->waitAndClick(self::CART_COUNT, 'cart link on the right top');
        $I->lookForwardTo("check that product counters are OK");
        $productCountFromCart = $I->getNumberFromLink(['class' => 'OrderFinalPrice__order-count'],
            'cart page product counter');
        if ($productCountRightTop > $productCountFromCart) {
            Throw new \Exception('Product counters was not equal between right top and cart content!');
        }
    }

    /**
     * Переход на страницу чекаута.
     *
     * @return string $cartSum текущая стоимость корзины
     * @throws \Exception
     */
    public function goFromCartToCheckout()
    {
        $I = $this->tester;

        $I->lookForwardTo("**** go from Cart to Check Out page");
        $productsInCart = $I->getProductsIdFromCart();
        codecept_debug('product ID list from cart');
        codecept_debug($productsInCart);
        $cartSum = $this->getCartAmountBlackBox();
        codecept_debug($cartSum . ' CART SUM');
        $I->waitAndClick('//button[contains(@class,"OrderFinalPrice__order-button")]', "button Оформить заказ");
        $I->waitForElementVisible('//div[@class="AsideOrder__total AsideOrderInfoItem"]');
        $totalPrice = $I->getNumberFromLink('//div[@class="AsideOrder__total AsideOrderInfoItem"]', 'total price');
        $I->seeValuesAreEquals($cartSum, $totalPrice);

        return $cartSum;
    }

    /**
     * Заполнение данных при самовывозе.
     *
     * @param $name string имя пользователя
     * @param $familyName string фамилия пользователя
     * @param $phone string телефон пользователя
     * @throws \Exception
     * @return array $contacts
     */
    public function fillContactsForSelfDelivery($name, $familyName, $phone)
    {
        $I = $this->tester;

        $I->lookForwardTo("**** fill contact details for self delivery");

        $I->fillValid('//input[@id="selfDelivery_userContact_firstName"]', $name);
        $I->fillValid('//input[@id="selfDelivery_userContact_lastName"]', $familyName);
        $I->fillValid('//input[@id="selfDelivery_userContact_phone"]', $phone);

        $contacts = array(
            "name" => $name,
            "familyName" => $familyName,
        );

        return $contacts;
    }

    /**
     * Переход на подтверждение заказа с отменой СМС и комментарием.
     *
     * @throws \Exception
     */
    public function goFromPaymentToConfirmation()
    {
        $I = $this->tester;

        $I->lookForwardTo("go from Payment to Order Confirmation page");
        $I->waitAndClick('//button[@class="pretty_button type4"]', "button Далее");

        $I->lookForwardTo("disable SMS for order details");
        $I->waitAndClick('//label[@for="sms_me"]', 'disable SMS ME', true);

        $I->fillField(['xpath' => '//textarea[@id="comment"]'], 'TEST ORDER - Тестовый заказ');
    }

    /**
     * Переход на подтверждение заказа.
     *
     * @throws \Exception
     */
    public function goFromPaymentToConfirmationWithSMS()
    {
        $I = $this->tester;

        $I->lookForwardTo("go from Payment to Order Confirmation page");
        $I->waitAndClick('//button[@class="pretty_button type4"]', "button Далее");
    }

    /**
     * Подтверждение заказа для авторизованного пользователя и отмена созданного заказа.
     *
     * @param int $paymentType Тип оплаты подтверждаемого заказа
     * @throws \Exception
     */
    public function orderConfirm($paymentType)
    {
        $this->orderConfirmOnly($paymentType);
        $this->orderCancel();
    }

    /**
     * Отмена созданного заказа.
     *
     * @throws \Exception
     */
    public function cancelOrderB2B()
    {
        $I = $this->tester;

        $I->waitAndClick(self::USER_MENU_LINK, "user menu");
        $I->waitAndClick(self::XPATH_MYORDERS, "my orders link");
        if ($I->getNumberOfElements(self::XPATH_EXPAND_ORDERS_LIST, 'expand order list') > 0) {
            $I->waitAndClick(self::XPATH_EXPAND_ORDERS_LIST, "expand order list");
            do {
                $I->waitAndClick(self::USER_MENU_LINK, "user menu");
                $I->waitAndClick(self::XPATH_MYORDERS, "my orders link");
                $I->waitAndClick(self::XPATH_EXPAND_ORDERS_LIST, "expand order list");
            } while ($I->getNumberOfElements(self::XPATH_CANCEL_ORDER_BUTTON_B2B, 'cancel button') == 0);
            do {
                $I->waitAndClick(self::XPATH_CANCEL_ORDER_BUTTON_B2B, "cancel one order");
                $I->acceptPopup();
                $I->waitAndClick(self::USER_MENU_LINK, "user menu");
                $I->waitAndClick(self::XPATH_MYORDERS, "my orders link");
                $I->waitAndClick(self::XPATH_EXPAND_ORDERS_LIST, "expand order list");
            } while ($I->getNumberOfElements(self::XPATH_CANCEL_ORDER_BUTTON_B2B, 'cancel button') > 0);
        } else {
            do {
                $I->waitAndClick(self::USER_MENU_LINK, "user menu");
                $I->waitAndClick(self::XPATH_MYORDERS, "my orders link");
                $cancelOrderButtonsCount = $I->getNumberOfElements(self::XPATH_CANCEL_ORDER_BUTTON_B2B, 'cancel button');
            } while (($cancelOrderButtonsCount == 0));
            $I->waitAndClick(self::XPATH_CANCEL_ORDER_BUTTON_B2B, "cancel last order");
            $I->acceptPopup();
        }
    }

    /**
     * Получение текущей суммы корзины.
     *
     * @return int $cartSum сумма корзины
     * @throws \Exception
     */
    public function getCartAmountBlackBox()
    {
        $I = $this->tester;

        $I->lookForwardTo("get cart amount from black box");
        $arrayProductsPrices = $I->grabMultiple(['class' => 'ProductCardForBasket__price-current_current-price']);
        $totalProductsPrice = 0;
        foreach ($arrayProductsPrices as $price){
            codecept_debug($price);
            $price = preg_replace("/[^0-9]/", "", $price);
            $totalProductsPrice += $price;
        }

        $services = $I->getNumberOfElements('//div[@class="ProductListForBasket__content"]//section[@data-checked="true"]//span[@class="AdditionalServices__price-block_current-price"]');
        $totalServicesPrice = 0;
        if ($services != 0) {
            $itemsId = $I->grabMultiple('//div[@class="ProductListForBasket__content-title"]/b/../../../div[@data-productid]', 'data-productid');
            foreach ($itemsId as $itemId) {
                $openBlock = $I->getNumberOfElements('//div[@data-productid="' . $itemId . '"]//div[contains(@class,"ProductListForBasket__inner")][@data-active="false"]');
                if ($openBlock != 0) {
                    $I->waitAndClick('//div[@data-productid="' . $itemId . '"]//div[@class="ProductListForBasket__content-title"]',
                        'open service list');
                    $I->waitForElementVisible('//div[@data-productid="' . $itemId . '"]//div[contains(@class,"ProductListForBasket__inner")][@data-active="true"]');
                }
            }

            $arrayServicesPrices = $I->grabMultiple('//div[@class="ProductListForBasket__content"]//section[@data-checked="true"]//span[@class="AdditionalServices__price-block_current-price"]');
            codecept_debug($arrayServicesPrices);

            foreach ($arrayServicesPrices as $price) {
                codecept_debug($price);
                $price = preg_replace("/[^0-9]/", "", $price);
                $totalServicesPrice += $price;
            }
        }

        $configAvailability = $I->getNumberOfElements('//tr[contains(.,"в составе")]', 'see config available');
        $configPrice = 0;
        if ($configAvailability != 0) {
            $configPrice = $I->getNumberFromLink('//tr[contains(.,"в составе")]//td[@class="price"]//span',
                'get current cart config price');
        }

        $cartSum = intval($I->getNumberFromLink('//span[contains(@class,"OrderFinalPrice__price-current__price")]',
            'get current cart Amount'));
        $actionDiscountAvailability = $I->getNumberOfElements('//aside[@class="order_page_block"][contains(.,"акции")]//td[@class="price"]',
            'see current cart discount');
        $actionDiscount = 0;
        if ($actionDiscountAvailability != 0) {
            $actionDiscount = $I->getNumberFromLink('//aside[@class="order_page_block"][contains(.,"акции")]//td[@class="price"]',
                'get current cart discount');
        }

        $serviceDiscountAvailability = $I->getNumberOfElements('//aside[@class="order_page_block"][contains(.,"услугу")]//td[@class="price"]',
            'see current cart discount');
        $serviceDiscount = 0;
        if ($serviceDiscountAvailability != 0) {
            $serviceDiscount = $I->getNumberFromLink('//aside[@class="order_page_block"][contains(.,"услугу")]//td[@class="price"]',
                'get current cart discount');
        }

        $I->seeValuesAreEquals($totalProductsPrice + $totalServicesPrice - $configPrice, $cartSum + $actionDiscount - $serviceDiscount);

        return $cartSum;
    }

    /**
     * Получение текущей суммы корзины без учета доставки.
     *
     * @return int
     */
    public function getCartAmountWithOutDelivery()
    {
        $I = $this->tester;

        $I->lookForwardTo("get cart amount from black box without delivery");
        return intval($I->getNumberFromLink('//span[@class="price"]/ins[@class="num js--order-amount-without-discount__amount-num"]', 'get current cart Amount'));
    }

    /**
     * Проверка блока подъема на этаж КБТ при курьерской доставке. По-умолчанию, без параметров,
     * проверяется дефолтное значение(Чекбоксы не выбраны, инпут=1 disable). Можно проверить выбор ручного подъема и на лифте.
     *
     * @param int $level Номер этажа
     * @param string $lift Признак наличия лифта, любое не пустое означает проверку выбора лифта
     * @throws \Exception
     */
    public function checkEnableLift($level = null, $lift = null)
    {
        $I = $this->tester;
        if (($level != null) AND ( $lift == null)) {    //проверка подъема без лифта на этаж
            $I->lookForwardTo('check enable up to stage NOT use elevator');
            $I->seeCheckboxIsChecked(['xpath' => self::X_KBT_UPTOSTAGE], "checkbox lift up to stage");
            $I->scrollTo(['xpath' => self::X_KBT_STAGE_INPUT]);
            $dis = $I->grabAttributeFrom(['xpath' => self::X_KBT_STAGE_INPUT], "disabled");
            codecept_debug($dis);
            if ($dis == true) {
                Throw new \Exception('Floor number field is disabled');
            }
            $I->lookForwardTo('check number stage');
            $numStage = $I->grabValueFrom(['xpath' => self::X_KBT_STAGE_INPUT]);
            codecept_debug($numStage);
            if ($numStage != $level) {
                Throw new \Exception("INPUT STAGE = $level, GRAB STAGE = $numStage");
            }
            $I->lookForwardTo('check enable elevator');
            $elevator = $I->grabAttributeFrom(['xpath' => self::X_KBT_ELEVATOR], "disabled");
            codecept_debug($elevator);
            if (($elevator == true) AND ($level != 1)) {
                Throw new \Exception('ELEVATOR CHECKBOX DISABLE');
            }
            $I->dontSeeCheckboxIsChecked(['xpath' => self::X_KBT_ELEVATOR], "lift checkbox");
        } elseif (($level != null) AND ( $lift != null)) {  //проверка подъема на лифте
            $I->lookForwardTo('check floor number');
            $numStage = $I->grabValueFrom(['xpath' => self::X_KBT_STAGE_INPUT]);
            codecept_debug($numStage);
            if ($numStage != $level) {
                Throw new \Exception("INPUT STAGE = $level, GRAB STAGE = $numStage");
            }
            $I->lookForwardTo('check enable elevator');
            $elevator = $I->grabAttributeFrom(['xpath' => self::X_KBT_ELEVATOR], "disabled");
            if (($elevator == true) AND ($level != 1)) {
                Throw new \Exception('ELEVATOR CHECKBOX DISABLE');
            }
            $I->seeCheckboxIsChecked(['xpath' => self::X_KBT_ELEVATOR]);
        } else {    //проверка дефолтного занчения, без выбора
            $I->lookForwardTo('check DEFAULT enable up to stage');
            $I->dontSeeCheckboxIsChecked(['xpath' => self::X_KBT_UPTOSTAGE]);
            $dis = $I->grabAttributeFrom(['xpath' => self::X_KBT_STAGE_INPUT], "disabled");
            codecept_debug($dis);
            if ($dis != true) {
                Throw new \Exception('Floor number field is disabled');
            }
            $I->lookForwardTo('check default stage');
            $numStage = $I->grabValueFrom(['xpath' => self::X_KBT_STAGE_INPUT]);
            codecept_debug($numStage);
            if ($numStage != 1) {
                Throw new \Exception("CHECK DEFAULT STAGE = 1, GRAB DEFAULT STAGE = $numStage");
            }
            $I->lookForwardTo('check enable elevator');
            $elevator = $I->grabAttributeFrom(['xpath' => self::X_KBT_ELEVATOR], "disabled");
            if ($elevator != true) {
                Throw new \Exception('STAGE INPUT ENABLE');
            }
            $I->dontSeeCheckboxIsChecked(['xpath' => self::X_KBT_ELEVATOR]);
        }
    }

    /**
     * Выбор чекбокса "Подъем на этаж" и ввод значения в поле "Этаж" - Ручной подъем.
     *
     * @param int $param Номер этажа
     * @throws \Exception
     */
    public function liftUptoStage($param)
    {
        $I = $this->tester;

        $I->scrollTo(['xpath' => '//div[@id="delivery_deliveryType"]']);
        $I->lookForwardTo('set checkbox - pickup to floor');
        $I->waitAndClick(self::X_KBT_UPTOSTAGE_CLICK, 'click checkbox - pickup to floor');
        $I->lookForwardTo('set '. $param .' floor');
        $I->fillValid(self::X_KBT_STAGE_INPUT, $param);
    }

    /**
     * Выбор чекбокса "Подъем на этаж", ввод значения в поле "Этаж", выбор чекбокса "Подъем на лифте".
     *
     * @param int $param Номер этажа
     * @throws \Exception
     */
    public function liftUptoStageElevator($param)
    {
        $I = $this->tester;

        $I->lookForwardTo('set checkbox up to stage');
        $I->waitAndClick(self::X_KBT_UPTOSTAGE_CLICK, 'click checkbox up to stage');
        $I->lookForwardTo('set N stage');
        $I->fillValid(self::X_KBT_STAGE_INPUT, $param);
        $I->lookForwardTo('set checkbox Use elevator');
        $I->wait(SHORT_WAIT_TIME);
        $I->waitAndClick('//article[@class="delivery_options"]//label[@for="delivery_liftingOptions_isCargoElevator"]', 'select checkbox use elevator');
    }

    /**
     * Проверка услуги(количесвто услуг) подъема на этаж при подтверждении заказа.
     *
     * @param int $param Номер этажа
     * @throws \Exception Выбранный номер не совпадает с количеством услуг при подтверждении
     */
    public function checkLiftUpToStage($param)
    {
        $I = $this->tester;

        $I->lookForwardTo('check lift service - should be ' . $param);
        $I->checkElementOnPage('//table[@class="order_details"]' . self::XPATH_TBODY_ITEMS . '/tr/th');
        $I->lookForwardTo('get N stage in Service');
        $initialNumberStage = $I->getNumberFromLink(self::XPATH_TBODY_ITEMS . '/tr[th/text()="Услуга подъёма"]/following::tr/td[@class="amount"]', 'floor number');
        if ($initialNumberStage != $param) {
            Throw new \Exception('floor number is not the same');
        }
    }

    /**
     * Проверка услуги(одной) подъема на лифте
     *
     * @throws \Exception Добавлена НЕ одна услуга, а несколько
     */
    public function checkLiftUpToStageElevator()
    {
        $I = $this->tester;

        $I->lookForwardTo('check lift service');
        $I->checkElementOnPage('//table[@class="order_details"]' . self::XPATH_TBODY_ITEMS . '/tr/th');
        $I->lookForwardTo('compare');
        $initialNumberStage = $I->getNumberFromLink(self::XPATH_TBODY_ITEMS . '/tr/td[contains(text(),"Подъем")]/../td[@class="amount"]',
            'number of elevation services');
        if ($initialNumberStage != 1) {
            Throw new \Exception('lift up to stage not same');
        }
    }

    /**
     * Выбор типа оплаты
     *
     * @param int $param Номер элемента по порядку, используются константы - PAY_CASH = 1;
        PAY_CARD = 2;
        PAY_CREDIT = 3;
        PAY_YANDEX = 4;
        PAY_TERMINAL = 5;
        PAY_WEB = 6;
        PAY_ORG = 7;
     * @throws \Exception
     */
    public function paymentVia($param)
    {
        $I = $this->tester;

        $I->waitAndClick('//ul[@class="order-payment__checkboxes-list"]/li[' . $param . ']/label', "set kind of pay", 1);
        $I->seeCheckboxIsChecked(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . $param . ']/input']);
    }

    /**
     * Проверка всех ограничений типов оплаты для НЕавторизованного пользователя
     *
     * @throws \Exception В случае не ожидаемой доступности типов оплат
     */
    public function checkAllPayLimitNonAuth()
    {
        $I = $this->tester;

        $I->lookForwardTo('check enable radio');

        $disCash = $I->grabAttributeFrom(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . PAY_CASH . ']/input'], 'disabled');
        codecept_debug($disCash);

        $disCard = $I->grabAttributeFrom(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . PAY_CARD . ']/input'], 'disabled');
        codecept_debug($disCard);

        $disCredit = $I->grabAttributeFrom(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . PAY_CREDIT . ']/input'], 'disabled');
        codecept_debug($disCredit);

        $disYandex = $I->grabAttributeFrom(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . PAY_YANDEX . ']/input'], 'disabled');
        codecept_debug($disYandex);

        $disTerminal = $I->grabAttributeFrom(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . PAY_TERMINAL . ']/input'], 'disabled');
        codecept_debug($disTerminal);

        $disWeb = $I->grabAttributeFrom(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . PAY_WEBMONEY . ']/input'], 'disabled');
        codecept_debug($disWeb);

        $disOrg = $I->grabAttributeFrom(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . PAY_ORG . ']/input'], 'disabled');
        codecept_debug($disOrg);

        $I->lookForwardTo('check available payment options');
        if ($disCard == true) {
            Throw new \Exception('wrong status for card payment');
        }
        if ($disCash == true) {
            Throw new \Exception('wrong status for cash payment');
        }
        if ($disCredit != true) {
            Throw new \Exception('wrong status for credit payment');
        }
        if ($disYandex != true) {
            Throw new \Exception('wrong status for yandex payment');
        }
        if ($disTerminal != true) {
            Throw new \Exception('wrong status for terminal payment');
        }
        if ($disWeb != true) {
            Throw new \Exception('wrong status for webmoney payment');
        }
        if ($disOrg != true) {
            Throw new \Exception('wrong status for wire transfer payment');
        }
    }

    /**
     * Выбор типа оплаты
     *
     * @param int $paymentToChoose Номер метода оплаты, используются константы - PAY_CASH = 1;
        PAY_CARD = 2;
        PAY_CREDIT = 3;
        PAY_YANDEX = 4;
        PAY_TERMINAL = 5;
        PAY_WEB = 6;
        PAY_ORG = 7;
     * @throws \Exception
     */
    public function choosePayment($paymentToChoose)
    {
        $I = $this->tester;

        $I->lookForwardTo('choose payment of type ' . $paymentToChoose);
        $I->waitAndClick('//ul[@class="order-payment__checkboxes-list"]/li[' . $paymentToChoose . ']/label', 'payment selector');
    }

    /**
     * Проверка всех ограничений типов оплаты для Авторизованного пользователя
     *
     * @param bool $installService наличие услуги сборки true/false
     * @throws \Exception В случае не ожидаемой доступности типов оплат
     */
    public function checkAllPayLimitAuth($installService = null)
    {
        $I = $this->tester;

        $I->lookForwardTo('check payment methods depending of order amount - for authorized user');

        //получаем стоимость заказа вместе с доставкой - сумма к оплате
        $cart = $I->getNumberFromLink(self::XPATH_SUMMA_K_OPLATE, 'SUMMA K OPLATE');

        $disCash = $I->grabAttributeFrom(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . PAY_CASH . ']/input'], 'disabled');
        codecept_debug('pay by cash attibute is disabled? - ' . $disCash);
        if ($disCash == true) {
            Throw new \Exception('wrong status for pay-by-cash, should be always available');
        }

        $disCard = $I->grabAttributeFrom(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . PAY_CARD . ']/input'], 'disabled');
        codecept_debug('card payment status is disabled? - ' . $disCard);
        if (($disCard == true) AND ($cart <= LIMIT_PAY_CARD)) {
            Throw new \Exception('wrong status for pay-by-card');
        } elseif (($disCard != true) AND ($cart > LIMIT_PAY_CARD)){
            Throw new \Exception('wrong status for pay-by-card');
        }

        $disCredit = $I->grabAttributeFrom(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . PAY_CREDIT . ']/input'], 'disabled');
        codecept_debug('credit payment status is disabled? - ' . $disCredit);
        if (($disCredit == true) AND ($cart >= LIMIT_PAY_CREDIT) AND ($installService == false)) {
            Throw new \Exception('wrong status for credit payment');
        } elseif (($disCredit != true) AND ($cart < LIMIT_PAY_CREDIT) AND ($installService == false)){
            Throw new \Exception('wrong status for credit payment');
        }

        $disYandex = $I->grabAttributeFrom(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . PAY_YANDEX . ']/input'], 'disabled');
        codecept_debug('Yandex payment status is disabled? - ' . $disYandex);
        if (($disYandex == true) AND ($cart <= LIMIT_PAY_YANDEX)) {
            Throw new \Exception('wrong status for pay-by-yandex');
        } elseif (($disYandex != true) AND ($cart > LIMIT_PAY_YANDEX)){
            Throw new \Exception('wrong status for pay-by-yandex');
        }

        $disTerminal = $I->grabAttributeFrom(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . PAY_TERMINAL . ']/input'], 'disabled');
        codecept_debug($disTerminal);
        if (($disTerminal == true) AND ($cart < LIMIT_TERMINAL)) {
            Throw new \Exception('wrong status for pay-by-terminal');
        } elseif (($disTerminal != true) AND ($cart > LIMIT_TERMINAL)){
            Throw new \Exception('wrong status for pay-by-terminal');
        }

        $disWeb = $I->grabAttributeFrom(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . PAY_WEBMONEY . ']/input'], 'disabled');
        codecept_debug($disWeb);
        if (($disWeb == true) AND ($cart < LIMIT_WEBMONEY)) {
            Throw new \Exception('wrong status for pay-by-webmoney');
        } elseif (($disWeb != true) AND ($cart > LIMIT_WEBMONEY)){
            Throw new \Exception('wrong status for pay-by-webmoney');
        }

        $disOrg = $I->grabAttributeFrom(['xpath' => '//ul[@class="order-payment__checkboxes-list"]/li[' . PAY_ORG . ']/input'], 'disabled');
        codecept_debug('pay-by-wire-transfer attibute is disabled? - ' . $disOrg);
        if ($disOrg == true) {
            Throw new \Exception('wrong status for pay-by-wire-transfer, should be always available');
        }
    }

    /**
     * Выбор точки самовывоза.
     *
     * @param bool $changePickPoint
     * @return string $selfPoint выбранный пункт самовывоза
     * @throws \Exception
     */
    public function useSelfDelivery($changePickPoint)
    {
        $I = $this->tester;

        $I->wait(SHORT_WAIT_TIME);
        $I->chooseSelfDelivery();
        if ($changePickPoint) {
            $I->selectPickupPoint();
        }
        return $selfPoint = $I->getNamePickUpPoint();
    }

    /**
     * Изменение количества штук конкретного товара в корзине.
     *
     * @param int $itemNumber Номер элемента по порядку
     * @param int $value Количество
     * @throws \Exception
     */
    public function setItemValue($itemNumber, $value)
    {
        $I = $this->tester;

        $I->lookForwardTo("input item value");
        $I->waitAndFill(self::XPATH_TABLE_ITEMS . '['. $itemNumber . ']//input[@class="CountSelector__input js--CountSelector__input"]', 'QTY' ,$value);
        $I->unFocus();
        $I->wait(SHORT_WAIT_TIME);
        $grabValue =  $I->grabAttributeFrom(['xpath' => self::XPATH_TABLE_ITEMS . '['. $itemNumber . ']//input[@class="CountSelector__input js--CountSelector__input"]'], 'value');
        codecept_debug("Value after input at item number " . $grabValue);
        if($grabValue != $value){
            $I->lookForwardTo('check notification show');
            $I->waitAndFill(self::XPATH_TABLE_ITEMS . '['. $itemNumber . ']//input[@class="CountSelector__input js--CountSelector__input"]', 'QTY' ,$value);
        }
    }

    /**
     * Выбор типа доставки - Самовывоз.
     *
     * @throws \Exception
     */
    public function chooseSelfDelivery()
    {
       $I = $this->tester;

       $I->lookForwardTo("choose self delivery");
       $I->waitAndClick('//ul[@class="switcher type1 radio_style text tabs tabs2 delivery_type_switcher"]/li[1]', 'self delivery tab');
    }

    /**
     * Переход на шаг "Способ и адрес доставки".
     *
     * @throws \Exception
     */
    public function returnToDeliveryStage()
    {
        $I = $this->tester;

        $I->lookForwardTo("go to Delivery Page");
        $I->waitAndClick('//ul[@class="order_steps"]/li/a[text()="Способ и адрес доставки"]', 'go to Delivery Page');
    }

    /**
     * Только подтверждение заказа.
     *
     * @param string $paymentType тип оплаты
     * @throws \Exception
     */
    public function orderConfirmOnly($paymentType)
    {
        $I = $this->tester;

        $I->lookForwardTo("confirm order of type " . $paymentType);
        $onlinePaymentMethods = array(PAY_CARD, PAY_YANDEX, PAY_WEBMONEY);

        if (in_array($paymentType, $onlinePaymentMethods)) {
            $I->scrollTo(['xpath' => '//p[@class="next_step"]/button[@id="confirm_order_button"]']);
            $confirmButton = $I->grabAttributeFrom(['xpath' => '//p[@class="next_step"]/button[@id="confirm_order_button"]'],'disabled');
            codecept_debug('Confirm button enable - ' . $confirmButton);
            if(is_null($confirmButton)){
                Throw new \Exception('Confirm button is enabled - ' . $confirmButton);
            }

            $I->waitAndClick('//label[@for="confirm_delivery_data"]', "confirm delivery user data", true);
            $I->checkElementOnPage('//div[@class="js--order-check order-check"]', "send check selector");
        }

        $I->checkElementOnPage(self::USER_MENU_LINK, "user menu");
        $confirmButton = $I->grabAttributeFrom(['xpath' => '//button[@id="confirm_order_button"]'],'disabled');
        codecept_debug('Confirm button disable - '.$confirmButton);
        if($confirmButton == true){
            Throw new \Exception('Confirm button is disabled');
        }

        $I->waitAndClick('//button[@id="confirm_order_button"]', "button Оформить заказ");
        $I->checkElementOnPage('//div[@class="main_content_inner"]/h1', 'Поздравляем! Вы успешно оформили заказ.');
        codecept_debug('Order number is ' . $I->grabTextFrom(['xpath' => '//span[@class="order_number"]']));
        $I->wait(SHORT_WAIT_TIME);
    }

    /**
     * Отмена последнего заказа.
     *
     * @throws \Exception
    */
    public function orderCancel()
    {
        $I = $this->tester;

        $I->waitAndClick(self::USER_MENU_LINK, "user menu");
        $I->waitAndClick(self::XPATH_MYORDERS, "my orders link");

        if ( $I->getNumberOfElements( self::XPATH_EXPAND_ORDERS_LIST, 'expand order list') > 0) {
            $I->waitAndClick(self::XPATH_EXPAND_ORDERS_LIST, "expand order list");
            do {
                $I->waitAndClick(self::USER_MENU_LINK, "user menu");
                $I->waitAndClick(self::XPATH_MYORDERS, "my orders link");
                $I->waitAndClick(self::XPATH_EXPAND_ORDERS_LIST, "expand order list");
            } while ( $I->getNumberOfElements(self::XPATH_CANCEL_ORDER_BUTTON, 'cancel button') == 0 );
            do {
                $I->waitAndClick(self::XPATH_CANCEL_ORDER_BUTTON, "cancel one order");
                $I->acceptPopup();
                $I->waitAndClick(self::USER_MENU_LINK, "user menu");
                $I->waitAndClick(self::XPATH_MYORDERS, "my orders link");
                if ( $I->getNumberOfElements( self::XPATH_EXPAND_ORDERS_LIST, 'expand order list') > 0) {
                    $I->waitAndClick(self::XPATH_EXPAND_ORDERS_LIST, "expand order list");
                }
            } while ( $I->getNumberOfElements(self::XPATH_CANCEL_ORDER_BUTTON, 'cancel button') > 0 );
        } else {
            do {
                $I->waitAndClick(self::USER_MENU_LINK, "user menu");
                $I->waitAndClick(self::XPATH_MYORDERS, "my orders link");
                $cancelOrderButtonsCount = $I->getNumberOfElements(self::XPATH_CANCEL_ORDER_BUTTON, 'cancel button');
            } while ( ( $cancelOrderButtonsCount == 0) );
            $I->waitAndClick(self::XPATH_CANCEL_ORDER_BUTTON, "cancel last order");
            $I->acceptPopup();
        }
    }

    /**
     * Проверка наличия дублирования услуги доставки в оформленном заказе. Вызов из корзины.
     *
     * @throws \Exception
     */
    public function checkDuplicateDelivery()
    {
        $I = $this->tester;

        $arrayName = $I->grabMultipleDesc('//tbody/tr', "имя товара\услуги");
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
        if ($numberFilter != $numberUnique){
            Throw new \Exception('Delivery duplication');
        }
    }

    /**
     * Добавление товара в корзину из ротаторов корзины - с этим товаром также покупают
     *
     * @throws \Exception
     */
    public function addToCartFromCartWithIt()
    {
        $I = $this->tester;

        $I->lookForwardTo('add to cart from cart - with it rotator');
        $number = $I->getNumberOfElements('//div[@class="Basket__related-products"]//div[@class="ProductsSlider__slide"]', 'rotator pattern');
        $I->wait(SHORT_WAIT_TIME);
        $number = mt_rand(1, $number);
        $I->waitAndClick('//div[@class="Basket__related-products"]//div[@class="ProductsSlider__slide"][' . $number . ']//*[contains(@class,"ProductCardForSlider__button-add")]',
            'add to cart ' . $number . ' item');
        $I->continueShopping();
        $I->checkElementOnPage('//div[@class="Basket__related-products"]//div[@class="ProductsSlider__slide"][' . $number . ']//*[contains(@class,"ProductCardForSlider__active")]',
            'button after click');
    }



    /**
     * Возвращает имя выбранной точки самовывоза со страницы доставки из списка всех пунктов.
     *
     * @return string Имя точки самовывоза
     * @throws \Exception
     */
    public function getNamePickUpPointFromList()
    {
        $I = $this->tester;

        $I->lookForwardTo('grab name of pickup point');
        $I->goToPickupPointList();
        $pointName = $I->grabTextFrom('//span[@class="pseudo selected"]');
        $debugName = $I->translit($pointName);
        codecept_debug($debugName);
        $I->waitAndClick('//span[@class="popup_close pseudo"]', 'close popup');

        return $pointName;
    }

    /**
     * Переход в карточку товара из списка товаров сборки заказа.
     *
     * @param string $imgNameLink ссылка на картинку товара
     * @throws \Exception
     */
    public function goToProductFromInstallOrderList($imgNameLink)
    {
        $I = $this->tester;

        $I->lookForwardTo('grab products list');
        $array = $I->grabMultipleDesc('//div[contains(@class,"js--ProductListForBasket")]/div', 'products id', 'data-productid');
        $arrayProducts = array_filter($array);
        codecept_debug($arrayProducts);
        $productRnd = array_rand($arrayProducts, 1);
        codecept_debug($productRnd);
        $productLabel = $I->grabTextFrom('//div[contains(@class,"js--ProductListForBasket")]/div[@data-productid="'. $arrayProducts[$productRnd] .'"]');
        $I->waitAndClick('//div[contains(@class,"js--ProductListForBasket")]/div[@data-productid="'. $arrayProducts[$productRnd] .'"]//a[contains(@class,"'.$imgNameLink.'")]',
            "product: '.$productLabel");
    }

    /**
     * Переход в карточку товара из списка товаров подтверждения заказа.
     *
     * @throws \Exception
     */
    public function goToProductFromConfirmOrderList()
    {
        $I = $this->tester;

        $I->lookForwardTo('number of products list');
        $number = $I->getNumberOfElements('//tr[@class="product_data__gtm-js product_data__pageevents-js"]/td/a', 'products list');
        $numberRnd = mt_rand(1, $number);
        codecept_debug($numberRnd);
        $productLabel = $I->grabTextFrom(['xpath' => '//tr[@class="product_data__gtm-js product_data__pageevents-js"]['. $numberRnd .']/td/a']);
        $I->waitAndClick('//tr[@class="product_data__gtm-js product_data__pageevents-js"]['. $numberRnd .']/td/a', "product: '.$productLabel");
    }

    /**
     * Переход в карточку товара в корзину из ротаторов корзины - с этим товаром также покупают по названию.
     *
     * @throws \Exception
     */
    public function goToProductFromCartWithIt()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to product from cart - with it rotator');
        $productsCount = $I->getNumberOfElements('//div[@class="Basket__related-products"]//div[@class="ProductsSlider__slide"]', 'rotator pattern');
        $I->wait(SHORT_WAIT_TIME);
        $number = mt_rand(1, $productsCount);
        $I->waitAndClick('//div[@class="Basket__related-products"]//div[@class="ProductsSlider__slide"]['. $number .']//a', 'add to cart ' . $number . ' item');
    }

    /**
     * Переход в карточку товара из ротаторов корзины - с этим товаром также покупают по картинке.
     *
     * @throws \Exception
     */
    public function goToProductFromCartPictureWithIt()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to product from cart - with it rotator');
        $number = $I->getNumberOfElements('//div[@data-gtm-location="' . self::ROTATOR_WITH_IT . '"]//div[@class="product-card__name"]/a', 'rotator pattern');
        $I->wait(SHORT_WAIT_TIME);
        $number = mt_rand(1, $number);
        $I->waitAndClick('//div[@data-gtm-location="' . self::ROTATOR_WITH_IT . '"]//div[' . $number . ']//div[@class="product-card__img-container"]/a/img', 'add to cart ' . $number . ' item');
    }

    /**
     * Переход в карточку товара из ротатора "Вам может пригодиться" по наименованию.
     *
     * @throws \Exception
     */
    public function goToProductFromRotatorUseful()
    {
        $I = $this->tester;
        $I->lookForwardTo('go to product from useful rotator');

        if ( $I->getNumberOfElements('//div[@class="recommendation_block__no_print"]//div[@class="slick_carousel__js slick-initialized slick-slider"]', "rotator arrows") == 0 ){
            $array = $I->grabMultipleDesc('//div[@class="recommendation_block__no_print"]//div[@class="slick-track"]/div', 'product in rotator');
            $array = array_filter($array);
            array_pop($array);
            codecept_debug($array);
            $number = array_rand($array) + 1;
            codecept_debug($number);
            $I->waitAndClick('//div[@class="recommendation_block__no_print"]//div[@class="slick-track"]//div[' . $number . ']//div[@class="product-card__name"]/a', 'go to product from rotator');
        } else {
            $I->lookForwardTo('add to cart from cart - useful rotator');
            $number = $I->getNumberOfElements('//div[@class="recommendation_block__no_print"]//div[@aria-hidden="false"]//div[@class="product-card__name"]/a', 'rotator pattern');
            $I->wait(SHORT_WAIT_TIME);
            $number = mt_rand(1, $number);
            $I->waitAndClick('//div[@class="recommendation_block__no_print"]//div[@aria-hidden="false"][' . $number . ']//div[@class="product-card__name"]/a', 'go to product ' . $number . ' item');
        }
    }

    /**
     * Переход в карточку товара из ротатора "Вам может пригодиться" по картинке.
     *
     * @throws \Exception
     */
    public function goToProductFromPictureRotatorUseful()
    {
        $I = $this->tester;
        $I->lookForwardTo('go to product from useful rotator');

        if ( $I->getNumberOfElements('//div[@class="recommendation_block__no_print"]//div[@class="slick_carousel__js slick-initialized slick-slider"]', "rotator arrows") == 0 ){
            $array = $I->grabMultipleDesc('//div[@class="recommendation_block__no_print"]//div[@class="slick-track"]/div', 'product in rotator');
            $array = array_filter($array);
            array_pop($array);
            codecept_debug($array);
            $number = array_rand($array) + 1;
            codecept_debug($number);
            $I->waitAndClick('//div[@class="recommendation_block__no_print"]//div[@class="slick-track"]//div[' . $number . ']//div[@class="product-card__img-container"]/a/img', 'go to product from rotator');
        } else {
            $I->lookForwardTo('add to cart from cart - useful rotator');
            $number = $I->getNumberOfElements('//div[@class="recommendation_block__no_print"]//div[@aria-hidden="false"]//div[@class="product-card__name"]/a', 'rotator pattern');
            $I->wait(SHORT_WAIT_TIME);
            $number = mt_rand(1, $number);
            $I->waitAndClick('//div[@class="recommendation_block__no_print"]//div[@aria-hidden="false"][' . $number . ']//div[@class="product-card__img-container"]/a/img', 'go to product ' . $number . ' ');
        }
    }

    /**
     * Проверка поля ввода номера на валидацию при его заполнении через буфер обмена.
     *
     * @param $name string имя пользователя
     * @param $familyName string фамилия пользователя
     * @param $phone mixed телефон пользователя
     * @throws \Exception
     */
    public function checkPhoneMaskValidationForPaste($name, $familyName, $phone)
    {
        $I = $this->tester;

        $typeOfDelivery = $I->getTypeOfDelivery();
        foreach($phone as $number){
            $I->addTextToClipboard($number);
            $I->fillValid('//input[@id="'. $typeOfDelivery .'_userContact_firstName"]', $name);
            $I->fillValid('//input[@id="'. $typeOfDelivery .'_userContact_lastName"]', $familyName);
            $I->pressKey('//input[@id="'. $typeOfDelivery .'_userContact_phone"]', array('ctrl', 'v'));
            $I->click(['xpath' => '//div[@class="main_content_wrapper"]']);
            $I->wait(SHORT_WAIT_TIME);
            $I->checkElementOnPage('//span[@class="input input_complete"]');
            $I->goFromDeliveryToPayment('selfDelivery');
            $I->moveBack();
            $I->clearField('//input[@id="'. $typeOfDelivery .'_userContact_phone"]');
        }
    }

    /**
     * Возвращает тип доставки со страницы доставки.
     *
     * @return string Тип доставки
     * @throws \Exception
     */
    public function getTypeOfDelivery()
    {
        $I = $this->tester;

        $numberOfType = $I->getNumberOfElements('//ul[@class="switcher type1 radio_style text tabs tabs2 delivery_type_switcher"]/li', 'type of delivery');
        if($numberOfType != 0){
            $arrayType = $I->grabMultipleDesc('//ul[@class="switcher type1 radio_style text tabs tabs2 delivery_type_switcher"]/li', 'type of delivery', 'class');
            codecept_debug($arrayType);
            $selectedKey = -1;
            foreach ($arrayType as $key => $value){
                $selected = strpos($value, 'selected');
                if($selected != FALSE){
                    $selectedKey = $key;
                    break;
                }
            }
            if($selectedKey == -1){
                codecept_debug($arrayType);
                $I->lookForwardTo('see type of delivery not selected.');
                Throw new \Exception("Type of delivery not selected.");
            }

            codecept_debug($selectedKey);
            codecept_debug('value ' . $value);
            $typeValue = $I->grabAttributeFrom(['xpath' => '//li[@class="' . $arrayType[$selectedKey] . '"]/div/label/input'], 'value');
            codecept_debug($typeValue);
            $deliveryTypeClass = explode(" ", $arrayType[$selectedKey]);
            $typeOfDelivery = $I->grabAttributeFrom(['xpath' => '//div[contains(@class,"' . ltrim($deliveryTypeClass[0], "for_") . '")]/form'], 'name');
            codecept_debug($typeOfDelivery);
        }else {
            $typeOfDelivery = $I->grabAttributeFrom(['xpath' => '//div[@class="main_content_inner"]/form'], 'name');
            codecept_debug($typeOfDelivery);
        }

        return $typeOfDelivery;
    }

    /**
     * Заполнение поля Промо код.
     *
     * @param string $promoCode
     * @throws \Exception
     */
    public function usePromoCode($promoCode)
    {
        $I = $this->tester;

        $I->lookForwardTo('use promo code');
        $I->waitAndClick('//div[contains(@class,"OrderFinalPrice__promocode-form")][contains(.,"промокод")]',
            'open promo input');
        $I->waitAndFill(['name' => 'promocode'], 'promo code', $promoCode);
        $I->waitAndClick('//a[.="Применить"]', 'apply promo code');
        $I->checkElementOnPage(self::XPATH_PROMO_CODE . '/div[@class="js--market-payments__result payment-promocode__success"]', 'activate block promo code');
    }

    /**
     * Проверка наличия скидки по акции.
     *
     * @throws \Exception
     */
    public function checkPromoDiscount()
    {
        $I = $this->tester;

        $I->lookForwardTo('check discount block');
        $I->checkElementOnPage(self::XPATH_CART_DETAILS . '//tr/td[text() = "Скидка по акции"]', 'discount block');
    }

    /**
     * Выбор чекбокса товара.
     *
     * @param int $itemNumber Номер товара в списке по порядку
     * @throws \Exception
     */
    public function selectCheckBoxItem($itemNumber)
    {
        $I = $this->tester;

        $I->lookForwardTo("**** select check box " . $itemNumber . " from cart");
        $I->waitAndClick(self::XPATH_TABLE_ITEMS . '//tbody['. $itemNumber * 2 .']//td[@class="check_control"]/label', 'select product checkbox');
    }

    /**
     * Удаление товара из корзины через выбор чекбокса.
     *
     * @param int $itemNumber Номер товара в списке по порядку
     * @throws \Exception
     */
    public function deleteItemCheckBox($itemNumber)
    {
        $I = $this->tester;

        $I->selectCheckBoxItem($itemNumber);
        $I->waitAndClick('//button[@class="items_delete_from_cart__js pretty_button type2"]', "delete selected");
    }

    /**
     * Увеличение количества штук товара.
     *
     * @param int $itemNumber Номер товара в списке по порядку
     * @throws \Exception
     */
    public function increaseItemValue($itemNumber)
    {
        $I = $this->tester;

        $grabValue =  $I->grabAttributeFrom(self::XPATH_TABLE_ITEMS . '['. $itemNumber . ']//input[@class="CountSelector__input js--CountSelector__input"]', 'value');
        $grabValue = $grabValue + 1;
        $value = mt_rand($grabValue, MAX_ITEM_NUMBER);
        $I->setItemValue($itemNumber, $value);
    }

    /**
     * Уменьшение количества штук товара.
     *
     * @param int $itemNumber Номер товара в списке по порядку
     * @throws \Exception Если добавлена 1 штука товара
     */
    public function reduceItemValue($itemNumber)
    {
        $I = $this->tester;

        $grabValue =  $I->grabAttributeFrom(self::XPATH_TABLE_ITEMS . '['. $itemNumber . ']//input[@class="CountSelector__input js--CountSelector__input"]', 'value');
        if($grabValue < 2){
            Throw new \Exception("reached the minimum items value");
        }

        $grabValue = $grabValue - 1;
        $value = mt_rand(1, $grabValue);
        $I->setItemValue($itemNumber, $value);
    }

    /**
     * Получение номера товара в списке.
     *
     * @return int Номер товара в списке
     */
    public function getRandomItemNumber()
    {
        $I = $this->tester;

        $productsNumber = $I->getNumberOfElements(self::XPATH_TABLE_ITEMS, 'item products');
        $randomItemNumber = mt_rand(1, $productsNumber);

        return $randomItemNumber;
    }

    /**
     * Выбор категории услуги, по ее конкретному Id.
     *
     * @param string $itemId Id категории услуги
     * @throws \Exception
     */
    public function selectCategoryService($itemId)
    {
        $I = $this->tester;

        $I->lookForwardTo('select category service ' . $itemId);
        $I->waitAndClick('//div[@class="service-btn-list"]/span[@data-id="'. $itemId .'"]', 'select category');
    }

    /**
     * Получение случайного Id услуги.
     *
     * @param string $servicesKind вид услуги
     * @param string $productId айди товара
     * @return string $itemId Id  услуги
     * @throws \Exception
     */
    public function getServiceId($productId, $servicesKind)
    {
        $I = $this->tester;

        $I->lookForwardTo('grab all service');
        $this->openServicesList($productId);
        $servicesXpath = '//div[@data-productid="'.$productId.'"]//div[@class="ProductListForBasket__content"]//div[@class="AdditionalServices__checkbox"]';
        $arrayServicesId = $I->grabMultiple($servicesXpath.'[@data-service="'.$servicesKind.'"]', 'data-id');
        codecept_debug($arrayServicesId);
        $itemId = array_rand(array_flip($arrayServicesId), 1);
        codecept_debug('random service id is ' . $itemId);

        return $itemId;
    }

    /**
     * Добавление случайной услуги из категории Дополнительные.
     *
     * @param string $itemId Id услуги
     * @param string $productId Id товара
     * @return array $serviceData Наименование, стоимость выбранной услуги
     * @throws \Exception Если выбранная услуга, ее наименование и стоимость, не соответствует добавленной
     */
    public function addService($itemId, $productId)
    {
        $I = $this->tester;

        $this->openServicesList($productId);
        $initialAmount = $I->getCartAmountBlackBox();
        $serviceXpath = '//div[@data-productid="'.$productId.'"]//div[@class="ProductListForBasket__content"]//div[@data-id="'.$itemId.'"]';
        $I->waitForElementVisible($serviceXpath);
        $currentServiceName = $I->grabTextFrom($serviceXpath.'//label');
        codecept_debug('current service name is ' . $currentServiceName);
        $I->waitAndClick($serviceXpath, 'add service' );
        $I->waitBasketLoader();
        $currentServicePrice = $I->getNumberFromLink($serviceXpath.'/..//span[@class="AdditionalServices__price-block_current-price"]',
            'get service price');
        $I->lookForwardTo("check that cart amount was increased - get after");
        $afterAmount = $I->getCartAmountBlackBox();
        $I->waitForElementVisible('//div[@data-productid="'.$productId.'"]//div[@class="ProductListForBasket__content"]//section[@data-checked="true"]//div[@data-id="'.$itemId.'"]');
        $I->lookForwardTo("check that cart amount was increased exactly by service price");
        if ($initialAmount + $currentServicePrice != $afterAmount) {
            Throw new \Exception('Cart amount was not increased by service price!');
        }

        $serviceData = array(
            "name" => $currentServiceName,
            "price" => $currentServicePrice,
        );

        return $serviceData;
    }

    /**
     * Получение наименования услуги.
     *
     * @param string $itemId Id категории услуги
     * @param int $rndSer Номер услуги в списке
     * @return string Наименование услуги
     */
    public function getCurrentServiceName($itemId, $rndSer)
    {
        $I = $this->tester;

        $I->lookForwardTo("get current service name");
        $serviceCategoryId = str_replace('#', '', $itemId);
        $currentServiceName = $I->grabTextFrom(['xpath' => '//div[@id="'. $serviceCategoryId .'"]' . self::XPATH_INSURANCE_LIST . '[' . $rndSer . ']/label']);
        codecept_debug($currentServiceName);

        return $currentServiceName;
    }


    /**
     * Добавление услуги в корзину.
     *
     * @param string $itemId Id категории услуги
     * @param int $rndSer Номер услуги в списке
     * @return string Id добавленной услуги
     * @throws \Exception
     */
    public function selectService($itemId, $rndSer)
    {
       $I = $this->tester;

       $serviceCategoryId = str_replace('#', '', $itemId);
       $I->lookForwardTo('get service id from ' . $serviceCategoryId . ' itemId is ' . $itemId);
       $bad = $I->grabAttributeFrom(['xpath' => '//div[@id="'. $serviceCategoryId .'"]' . self::XPATH_INSURANCE_LIST . '[' . $rndSer . ']'], 'data-params');
       codecept_debug("JSON - $bad");
       $good = json_decode($bad, TRUE);
       codecept_debug('array JSON');
       codecept_debug($good);
       $serviceId = $good["id"];
       codecept_debug("Service Id - $serviceId");
       $I->lookForwardTo("adding service to cart");
       $I->waitAndClick('//div[@id="'. $serviceCategoryId .'"]' . self::XPATH_INSURANCE_LIST . '[' . $rndSer . ']/label', "service checkbox", true);

       return $serviceId;
    }

    /**
     * Добавление случайной цифровой услуги.
     *
     * @param string $itemId Id услуги
     * @param string $productId Id товара
     * @return array $serviceData Наименование, стоимость выбранной услуги
     * @throws \Exception Если выбранная услуга, ее наименование и стоимость, не соответствует добавленной
     */
    public function addDigitalService($itemId, $productId)
    {
        $I = $this->tester;

        $this->openServicesList($productId);
        $initialAmount = $I->getCartAmountBlackBox();
        $serviceXpath = '//div[@data-productid="'.$productId.'"]//div[contains(@class,"ProductListForBasket__inner")][contains(.,"Доп")]//section[@class="AdditionalServices__item"]';
        $I->waitForElementVisible($serviceXpath);
        $servicesCount = $I->getNumberOfElements($serviceXpath);
        $rndSer = mt_rand(1, $servicesCount);
        $currentServicePrice = $I->getNumberFromLink($serviceXpath.'['.$rndSer.']//span[@class="AdditionalServices__price-block_current-price"]', 'get service price');
        $currentServiceName = $I->grabTextFrom($serviceXpath.'['.$rndSer.']//label');
        codecept_debug('current service name is ' . $currentServiceName);
        $I->lookForwardTo("adding service to cart");
        $I->waitAndClick($serviceXpath.'['.$rndSer.']//span[contains(.,"В корзину")]',
            'add service button' );
        $I->reloadPage();
        $I->wait(MIDDLE_WAIT_TIME);
        $I->waitForElementVisible(self::XPATH_TABLE_ITEMS."//div[@data-productid='".$itemId."'][contains(.,'".$currentServiceName."')]");
        $I->lookForwardTo("check that cart amount was increased - get after");
        $afterAmount = $I->getCartAmountBlackBox();
        //if ($initialService == 0) {
        //    Throw new \Exception("Service name was not selected");
        //}

        $I->lookForwardTo("check that cart amount was increased exactly by service price");
        codecept_debug($initialAmount);
        codecept_debug($currentServicePrice);
        codecept_debug($afterAmount);
        if ($initialAmount + $currentServicePrice != $afterAmount) {
            Throw new \Exception('Cart amount was not increased by service price!');
        }

        $serviceData = array(
            "name" => $currentServiceName,
            "price" => $currentServicePrice,
        );

        return $serviceData;
    }

    /**
     * Получение наименования добавленной цифровой услуги.
     *
     * @param string $serviceId Id добавленной услуги
     * @return string Наименование услуги
     */
    public function getAddedDigitalServiceName($serviceId)
    {
        $I = $this->tester;

        $I->lookForwardTo("grab added service name");
        $serviceName = $I->grabTextFrom(['xpath' => self::XPATH_TABLE_ITEMS . '//div[@data-productid="'. $serviceId .'"]//div[@class="ProductCardForBasket__name"]']);

        return $serviceName;
    }

    /**
     * Проверка добавленной услуги на странице подтверждения заказа.
     *
     * @param array $service Наименование и стоимость услуги
     * @throws \Exception Если добавленная услуга не соответствует услуге на странице подтверждения заказа
     */
    public function checkServiceConfirm($service)
    {
        $I = $this->tester;

        $I->lookForwardTo('check service name at confirm page');
        $initialServiceName = $service["name"];
        $currentServiceName = $I->grabTextFrom(['xpath' => '//tbody/tr[not (@class="product_data__gtm-js product_data__pageevents-js")][2]/td[1]']);
        if ($initialServiceName != $currentServiceName) {
            Throw new \Exception("Service name not the same: after - " . $initialServiceName . ", before - " .$currentServiceName);
        }

        $I->lookForwardTo("check service price at confirm page");
        $initialServicePrice = $service["price"];
        $currentServicePrice = $I->getNumberFromLink('//tbody/tr[not (@class="product_data__gtm-js product_data__pageevents-js")][2]/td[@class="price"]//ins[@class="num"]',
            'get current Service Price');
        if ($initialServicePrice != $currentServicePrice) {
            Throw new \Exception("Service price not the same: after - " . $initialServicePrice . ", before - " . $currentServicePrice);
        }
    }

    /**
     * Проверка подарка в корзине.
     *
     * @throws \Exception Если не содержит текса "Подарок!"
     */
    public function checkGift()
    {
        $I = $this->tester;

        $I->lookForwardTo('check gift');
        $giftNumber = $I->getNumberOfElements('//tbody//td[@class="product_name"]/strong', 'gift');
        if($giftNumber == 0){
            Throw new \Exception('Gift div not found');
        }

        $gift = $I->grabTextFrom(['xpath' => '//tbody//td[@class="product_name"]/strong']);
        if($gift != 'Подарок!'){
            Throw new \Exception('Gift not added to cart');
        }
    }

    /**
     * Проверка контактных данных.
     *
     * @param array $contacts Введенные контактные данные
     * @throws \Exception Если отображаемые контактные данные не равны ранее введенным
     */
    public function checkContacts($contacts)
    {
        $I = $this->tester;

        $I->lookForwardTo('grab contacts');
        $name = $I->grabAttributeFrom(['xpath' => '//input[@id="selfDelivery_userContact_firstName"]'],'value');
        $familyName = $I->grabAttributeFrom(['xpath' => '//input[@id="selfDelivery_userContact_lastName"]'], 'value');
        $contactsGrab = array(
            "name" => $name,
            "familyName" => $familyName,
            );
        if ($contactsGrab != $contacts) {
            codecept_debug($contactsGrab);
            codecept_debug($contacts);
            Throw new \Exception('Contacts data lost');
        }
    }

    /**
     * Переход к списку точек самовывоза.
     *
     * @return int Количество не выбранных точек самовывоза
     * @throws \Exception
     */
    public function goToPickupPointList()
    {
        $I = $this->tester;

        $I->lookForwardTo('open list pickup point');
        $I->waitAndClick(self::XPATH_CHOOSE_OTHER_PICKPOINT, 'choose another pickup point');
        $numberOfUnselectedStores = $I->getNumberOfElements(self::XPATH_STORES_LIST , 'unselected pickup stores');

        return $numberOfUnselectedStores;
    }

    /**
     * Проверка наличия блока экстра бонусов на странице списка товаров в корзине.
     *
     * @return string Кол-во экстрабонусов в корзине
     * @throws \Exception Если не найден блок с экстра бонусами
     */
    public function checkExtraBonusCart()
    {
        $I = $this->tester;

        $I->lookForwardTo('check extra bonus');
        $extraBonusNumber = $I->getNumberOfElements('//aside[@class="order_page_block"]//div[@class="payment_block"]/p[@class="sum"]/span[contains(., "Экстрабонусы")]',
            'extra bonus div');
        if($extraBonusNumber == 0){
            Throw new \Exception('Extra bonus div not found');
        }

        $extraBonus = $I->getNumberFromLink('//aside[@class="order_page_block"]//div[@class="payment_block"]/p[@class="sum"]/span[@class="price promo_cost"]/ins[@class="num marketing_bonus_field"]',
            'extra bonus value');

        return $extraBonus;
    }

    /**
     * Проверка наличия блока экстра бонусов и их суммы внутри шагов корзины.
     *
     * @param $extraBonusCart
     * @throws \Exception Если не найден блок с экстра бонусами или сумма экстра бонусов не равна сумме на предыдущем шаге в корзине
     */
    public function checkExtraBonusStep($extraBonusCart)
    {
        $I = $this->tester;

        $I->lookForwardTo('check extra bonus at step');
        $extraBonusNumber = $I->getNumberOfElements(self::XPATH_CART_DETAILS . '//tr/td[contains(., "Экстрабонусы для начисления")]',
            'extra bonus div - step');
        if($extraBonusNumber == 0){
            Throw new \Exception('Extra bonus div not found');
        }

        $extraBonusStep = $I->getNumberFromLink(self::XPATH_CART_DETAILS . '//tr[contains(., "Экстрабонусы для начисления")]/td[@class="price"]//ins',
            'extra bonus value');
        if($extraBonusStep != $extraBonusCart){
            Throw new \Exception('Extra bonus delivery not same: delivery - '. $extraBonusStep .', cart - '. $extraBonusCart .'');
        }
    }

    /**
     * Заполнение контактных данных при доставке День-в-день.
     *
     * @param string $name
     * @param string $familyName
     * @param string $phone
     * @param string $street
     * @param string $house
     * @throws \Exception
     */
    public function fillContactsForDelivery($name, $familyName, $phone, $street, $house)
    {
        $I = $this->tester;

        $I->lookForwardTo("fill contact details for same day delivery");

        $I->fillValid( '//input[@id="delivery_userContact_firstName"]', $name);
        $I->fillValid('//input[@id="delivery_userContact_lastName"]', $familyName);
        $I->fillValid('//input[@id="delivery_userContact_phone"]', $phone);
        $I->fillValid('//input[@id="delivery_address_street"]', $street);
        $I->fillValid('//input[@id="delivery_address_house"]', $house);
    }

    /**
     * Заполнение контактных данных при курьерской доставке.
     *
     * @param $name
     * @param $familyName
     * @param $phone
     * @param $street
     * @param $house
     * @throws \Exception
     */
    public function fillContactsForCourierDelivery($name, $familyName, $phone, $street, $house)
    {
        $I = $this->tester;

        $I->lookForwardTo("fill contact details for courier delivery if needed");

        if ( $I->getNumberOfElements('//form[@name="delivery"]//label[@for="delivery_id_0"]', "New address") == 1 )
        {
            $I->waitAndClick('//form[@name="delivery"]//label[@for="delivery_id_0"]', 'select new address param');
            $I->fillContactsForDelivery($name, $familyName, $phone, $street, $house);
        }
    }

    /**
     * Заполнение контактных данных при курьерской доставке с адресом КЛАДР.
     *
     * @param $name
     * @param $familyName
     * @param $phone
     * @param $house
     * @throws \Exception
     */
    public function fillContactsForCourierKladrDelivery($name, $familyName, $phone, $house)
    {
        $I = $this->tester;

        $I->lookForwardTo("fill contact details for courier delivery if needed");

        if ( $I->getNumberOfElements('//input[@id="delivery_id_0" and @checked="checked"]', "New address") == 1 )
        {
            $I->fillValid('//input[@id="delivery_userContact_firstName"]', $name);
            $I->fillValid('//input[@id="delivery_userContact_lastName"]', $familyName);
            $I->fillValid('//input[@id="delivery_userContact_phone"]', $phone);
            $I->fillField('//input[@id="delivery_address_street"]', 'лен');
            $I->wait(SHORT_WAIT_TIME);
            $streetsArray = $I->grabMultipleDesc('//ul[@id="ui-id-1"]/li/a', 'kladr streets', 'id');
            $streetId = array_rand($streetsArray);
            $street = $streetsArray[$streetId];
            $I->waitAndClick('//ul[@id="ui-id-1"]/li/a[@id="'. $street .'"]', 'select street');
            $I->fillValid('//input[@id="delivery_address_house"]', $house);
            $I->seeKladrNotEmpty($I->grabValueFrom(['xpath' => '//input[@id="delivery_address_streetId"]']));
        }
    }

    /**
     * Заполнение контактных данных при курьерской доставке в офис Merlion
     *
     * @param string $name
     * @param string $familyName
     * @param string $phone
     * @param string $officeCode
     * @throws \Exception
     */
    public function fillContactsForCourierDeliveryMerlion($name, $familyName, $phone, $officeCode)
    {
        $I = $this->tester;

        $I->lookForwardTo("fill contact details for courier delivery in Myakinino if needed");

        if ( $I->getNumberOfElements('//input[@id="delivery_id_0" and @checked="checked"]', "New address") == 1 )
        {
            $I->waitAndClick('//label[@for="delivery_orgDelivery_' . $officeCode  . '"]', 'select Merlion office ' . $officeCode, true);
            $I->fillValid('//input[@id="delivery_userContact_firstName"]', $name);
            $I->fillValid('//input[@id="delivery_userContact_lastName"]', $familyName);
            $I->fillValid('//input[@id="delivery_userContact_phone"]', $phone);
        }
    }

    /**
     * Переход на страницу Сервисы и Услуги.
     *
     *@throws \Exception
     */
    public function goFromCartToService()
    {
        $I = $this->tester;

        $I->lookForwardTo("**** go from Cart to Services page");
        $I->waitAndClick('//form[@id="first-order-form"]//button[@class="next-basket-step-button__js  process_order pretty_button type6"]', "button Оформить заказ");

        if ($I->getNumberOfElements('//ul[@class="order_steps"]/li[@class="selected" and text()="Сервисы и Услуги"]') == 0 ) {
            Throw new \Exception('Service step is not selected');
        }
    }

    /**
     * Переход на ШАГ сборки заказа.
     *
     * @throws \Exception
     */
    public function goToInstallStep()
    {
        $I = $this->tester;

        $I->lookForwardTo('go to install order step');
        $I->waitAndClick('//ul[@class="order_steps"]/li/a[text()="Сборка заказа"]', 'go to Install order Page');
        if ($I->getNumberOfElements('//ul[@class="order_steps"]/li[@class="selected" and text()="Сборка заказа"]') == 0 ) {
            Throw new \Exception('Service step is not selected');
        }
    }

    /**
     * Проверяет наличие кнопки типа услуги.
     *
     * @param string $itemId Id категории услуги
     * @throws \Exception Если кнопка с типом услуг отсутствует
     */
    public function checkNoneServiceByType($itemId)
    {
        $I = $this->tester;

        $I->lookForwardTo('check none service');
        $I->checkElementOnPage('//div[@class="service-btn-list"]/span[@data-id="'. $itemId .'"]', 'service button for '.$itemId);
    }

    /**
     * Сравнение наименований услуг.
     *
     * @param string $currentServiceName Наименование проверяемой услуги
     * @param string $itemId Id категории услуги
     * @param string $rndSer Номер услуги по порядку
     * @throws \Exception Если сравниваемые услуги не равны
     */
    public function compareServiceName($currentServiceName, $itemId, $rndSer)
    {
        $I = $this->tester;

        $I->lookForwardTo('compare service name');
        $initialServiceName = $this->getCurrentServiceName($itemId, $rndSer);
        if($currentServiceName != $initialServiceName){
            Throw new \Exception('Service name not same. Current - '. $currentServiceName .' , Initial - '.$initialServiceName);
        }
    }

    /**
     * Получение количества позиций на шаге подтверждения заказа.
     *
     * @return int Количество позиции на шаге подтверждения заказа
     */
    public function getNumberOfPositions()
    {
        $I = $this->tester;

        $I->lookForwardTo('get number of position');
        return $positions = $I->getNumberFromLink('//table[@class="order_details"]/thead/tr/th', 'get positions count');
    }

    /**
     * Проверка количества позиций на странице подтверждения заказа.
     *
     * @param int $positions Ожидаемое количество позиций
     * @throws \Exception Если количество позиций не соответсвует значению
     */
    public function checkNumberOfPositions($positions)
    {
        $I = $this->tester;

        $I->lookForwardTo('check number of position');
        $grabPositions = $this->getNumberOfPositions();
        if($positions != $grabPositions){
            Throw new \Exception('Positions number not same. Check - '. $positions .' , Grab - '.$grabPositions);
        }
    }

    /**
     * Подтверждение заказа и клик на повторить заказ.
     *
     * @throws \Exception Кнопка подтверждения недоступна
     */
    public function orderRepeat()
    {
        $I = $this->tester;

        $I->lookForwardTo('confirm and repeat');
        $I->checkElementOnPage(self::USER_MENU_LINK, "user menu");
        $I->wait(SHORT_WAIT_TIME);
        $I->checkElementOnPage('//div[@class="main_content_inner"]/h1', 'Поздравляем! Вы успешно оформили заказ.');
        $I->waitAndClick(self::USER_MENU_LINK, "user menu");
        $I->waitAndClick(self::XPATH_MYORDERS, "my orders link");
        $I->wait(SHORT_WAIT_TIME);
        $I->waitAndClick('//table[@class="order order_content"]//button[text()="Повторить заказ"]', 'click button Повторить заказ');
    }

    /**
     * Получение списка ID товаров в корзине.
     *
     * @return array Список id товаров в корзине
     */
    public function getProductsIdFromCart()
    {
        $I = $this->tester;

        $I->lookForwardTo('get products id from cart');
        $arrayId = array_values(array_filter($I->grabMultiple(['class' => 'ProductCardForBasket__meta'])));
        $arrayId = preg_replace("/[^0-9]/", "", $arrayId);

        return $arrayId;
    }

    /**
     * Очистка корзины.
     *
     * @throws \Exception
     */
    public function cleanCart()
    {
        $I = $this->tester;

        $I->lookForwardTo('clean cart');
        if ($I->getNumberFromLink(self::CART_COUNT,'items count in cart') > 0) {
            $I->goToCartFromTopLink();
            $I->deleteItemFromCart(0);
        }
    }

    /**
     * Проверка отсутствия текста в блоке товаров и услуг на странице подтверждения.
     *
     * @param string $textDelivery Искомый текст
     */
    public function checkDelivery($textDelivery)
    {
        $I = $this->tester;

        $grabDeliveryInfo = $I->grabTextFrom(['xpath' => '//table[@class="order_details"]/tbody[@class="block_data__gtm-js block_data__pageevents-js"]']);
        $I->assertNotContainsCourierDelivery($textDelivery, $grabDeliveryInfo);
    }

    /**
     * Переход на страницу доставки с второго шага добавления услуг.
     *
     * @throws \Exception
     */
    public function goFromSecondStepCartToDelivery()
    {
        $I = $this->tester;

        $I->lookForwardTo("**** go from second step Cart to Delivery page");
        $I->waitAndClick('//button[@class="pretty_button type4"]', "button Go from Services page");
        $I->seeCurrentUrlEquals(self::route('delivery/'));
    }

    /**
     * Получение полноформатных магазинов из списка возможных точек самовывоза.
     */
    public function getFullStorePoint()
    {
        $I = $this->tester;
        $I->wantTo('grab full store from pickup point list');
        $allMarket = $I->grabMultipleDesc('//div[@class="stores_view list_view"]//tbody/tr', 'all markets');
        $I->seeArrayNotEmpty($allMarket);
        codecept_debug($allMarket);
        $endOfFullMarket = array_search('Пункты выдачи', $allMarket) + 1;
        $fullMarket = $I->grabMultiple(['xpath' => '//div[@class="stores_view list_view"]//tbody/tr['. $endOfFullMarket .']/preceding-sibling::*']);
        array_shift($fullMarket);
        $I->seeArrayNotEmpty($fullMarket);
        codecept_debug($fullMarket);

        return $fullMarket;
    }

    /**
     * Проверка соответсвия значения номера телефона по-умолчанию.
     *
     * @param string $checkPhone Проверяемый номер телефона
     */
    public function checkDefaultPhoneSelfDelivery($checkPhone)
    {
        $I = $this->tester;

        $grabPhone = $I->grabValueFrom(['id' => 'selfDelivery_userContact_phone']);
        $I->seeDefaultPhoneNumber($checkPhone, $grabPhone);
    }

    /**
     * Выбор получения чека по СМС.
     *
     * @throws \Exception
     */
    public function selectChequeBySms()
    {
        $I = $this->tester;

        $I->waitAndClick('//div[@class="order-check__control"]/button[contains(.,"по смс")]', 'get cheque by sms');
    }

    /**
     * Проверка соответсвия значения номера телефона по-умолчанию для поулчения чека по СМС.
     *
     * @param $checkPhone
     */
    public function checkDefaultPhoneGetCheque($checkPhone)
    {
        $I = $this->tester;

        $grabPhone = $I->grabValueFrom(['id' => 'confirmOrder_paymentPhone']);
        $strWithoutChars = preg_replace('/[^0-9]/', '', $grabPhone);
        $I->seeDefaultPhoneNumber($checkPhone, $strWithoutChars);
    }

    /**
     * Применить бонусы.
     *
     * @param string $phone номер телефона для смс
     * @param bool $resend повторная отправка кода
     * @throws \Exception
     */
    public function applyBonuses($phone, $resend)
    {
        $I = $this->tester;

        $initialCartSum = $I->getNumberFromLink('//span[@class="ProductPrice__price OrderFinalPrice__price-current__price"]',
            'cart sum');
        $I->waitAndClick(['id' => 'bonus-block'], 'open bonuses form');
        $maxBonuses = $I->getNumberFromLink('//span[@class="js--payment-bonuses__max-bonus payment-bonuses__bonus"]',
            'max bonuses');
        $I->waitAndFill('//div[@id="bonus-block"]//input[@name="certificate"]', "bonuses input" ,APPROVE_BONUS_VALUE);
        $I->clearSmsByPhone($phone);
        $I->waitAndClick('//div[@id="bonus-block"]//a[contains(.,"Применить")]', 'click button Применить');
        $this->confirmUseBonuses($phone, $resend);
        $I->waitForElementVisible(['xpath' => '//tr[@class="js--cart-details__bonuses"]']);
        $cartSum = $I->getNumberFromLink('//span[@class="price cart_cost"]', 'cart sum');
        $I->seeCartAmount($initialCartSum, $cartSum + $maxBonuses);
    }

    /**
     * Подтверждение использования бонусов по смс коду.
     *
     * @param string $phone номер телефона
     * @param bool $resend повторная отправка кода
     * @throws \Exception
     */
    public function confirmUseBonuses($phone, $resend)
    {
        $I = $this->tester;

        $I->wait(SHORT_WAIT_TIME);
        if ($resend == true){
            $I->resendSmsCodeForProfile();
        }
        $code = $I->getSmsCode($phone);
        $I->waitForElementVisible('//span[@class="wizard-group-input__wrapper"]/input');
        $I->fillField('//span[@class="wizard-group-input__wrapper"]/input', $code);
        $I->waitAndClick('//button[contains(@class,"js--wizard-sms-code__confirm-code")]', 'send form');
        $I->waitForElementVisible('//div[@class="wizard-success"]');
    }

    /**
     * Отмена использования бонусов.
     *
     * @throws \Exception
     */
    public function denyBonuses()
    {
        $I = $this->tester;

        $I->waitAndClick('//div[@class="payment-bonuses__use"]//button[contains(.,"Отменить")]', 'deny used bonuses');
        $I->waitForElementNotVisible('//div[@class="payment-bonuses__use"]//button[contains(.,"Отменить")]');
    }

    /**
     * Выбор срочной доставки.
     *
     * @throws \Exception
     */
    public function selectExpressDelivery()
    {
        $I = $this->tester;

        $I->wait(SHORT_WAIT_TIME);
        $serviceName = 'Доставка сегодня в течение трех часов';
        $I->waitAndClick('//label[@class="delivery-type__label"][contains(.,"'. $serviceName .'")]', 'express delivery', true);
        $I->checkElementOnPage('//p[@class="js--delivery-time-empty delivery-time"][contains(.,"Доставка в течение трёх часов")]', 'express delivery radio button selected');
    }

    /**
     * Проверка наличия услуги срочной доставки.
     *
     * @throws \Exception
     */
    public function checkServiceExpressDelivery()
    {
        $I = $this->tester;

        $I->lookForwardTo('check added service to mini');
        $serviceName = 'Срочная доставка';
        $I->checkElementOnPage(self::XPATH_TBODY_ITEMS . '/tr/td[contains(., "'. $serviceName .'")]', 'service name');
    }

    /**
     * Увеличение количества штук товара.
     *
     * @param int $itemNumber Номер товара в списке по порядку
     * @throws \Exception
     */
    public function increaseItemBoxValue($itemNumber)
    {
        $I = $this->tester;

        $grabValue =  $I->grabAttributeFrom(self::XPATH_TABLE_ITEMS . '['. $itemNumber .']//input', 'value');
        $grabBoxValue = $I->getNumberFromLink(self::XPATH_TABLE_ITEMS . '['. $itemNumber .']//td[@class="amount"]/span[@class="product_amount_boxes"]', 'box qty');
        $oneBoxValue = $grabValue / $grabBoxValue;
        $boxValue = $grabBoxValue + 1;
        $value = $oneBoxValue * mt_rand($boxValue, MAX_ITEM_NUMBER);
        $I->setItemValue($itemNumber, $value);
    }

    /**
     * Уменьшение количества штук товара.
     *
     * @param int $itemNumber Номер товара в списке по порядку
     * @throws \Exception Если добавлена 1 коробка
     */
    public function reduceItemBoxValue($itemNumber)
    {
        $I = $this->tester;

        $grabValue =  $I->grabAttributeFrom(self::XPATH_TABLE_ITEMS . '['. $itemNumber .']//input', 'value');
        $grabBoxValue = $I->getNumberFromLink(self::XPATH_TABLE_ITEMS . '['. $itemNumber .']//td[@class="amount"]/span[@class="product_amount_boxes"]', 'box qty');
        $oneBoxValue = $grabValue / $grabBoxValue;
        if($grabBoxValue < 2){
            Throw new \Exception("reached the minimum items value");
        }

        $boxValue = $grabBoxValue - 1;
        $value = $oneBoxValue * mt_rand(1, $boxValue);
        $I->setItemValue($itemNumber, $value);
    }

    /**
     * Удаление услуг из категории "дополнительные".
     *
     * @param array $serviceId Id Услуги
     * @param string $productId айди товара
     * @throws \Exception
     */
    public function deleteExtraService($serviceId, $productId)
    {
        $I = $this->tester;

        $this->openServicesList($productId);
        $serviceXpath = '//div[@data-productid="'.$productId.'"]//div[@class="ProductListForBasket__content"]//section[@data-checked="true"]//div[@data-id="'.$serviceId.'"]';
        $I->waitForElementVisible($serviceXpath);
        $I->waitAndClick($serviceXpath.'//label','delete service button' );
        $I->waitForElementNotVisible($serviceXpath);
    }

    /**
     * Удаление цифровой услуги.
     *
     * @param string $serviceId Id Услуги
     * @throws \Exception
     */
    public function deleteDigitalService($serviceId)
    {
        $I = $this->tester;

        $I->waitForElementVisible('//div[@class="ProductListForBasket__item"][@data-productid="'.$serviceId.'"]');
        $I->waitAndClick('//div[@class="ProductListForBasket__item"][@data-productid="'.$serviceId.'"]//div[@class="ProductCardForBasket__icons"]/*[2]',
            'delete service button' );
        $I->waitForElementNotVisible('//div[@class="ProductListForBasket__item"][@data-productid="'.$serviceId.'"]');
    }

    /**
     * Открыть список услуг товара.
     *
     * @param string $productId айди товара
     * @throws \Exception
     */
    public function openServicesList($productId)
    {
        $I = $this->tester;

        $openBlock = $I->getNumberOfElements('//div[@data-productid="'.$productId.'"]//div[contains(@class,"ProductListForBasket__inner")][@data-active="false"]');
        if ($openBlock != 0){
            $I->waitAndClick('//div[@data-productid="'.$productId.'"]//div[@class="ProductListForBasket__content-title"]',
                'open service list');
        }
    }

    /**
     * Проверка  наличия галочек на чекбоксах уведомелний по СМС.
     */
    public function checkSelectedCheckboxes()
    {
        $I = $this->tester;

        $I->seeCheckboxIsChecked("#sms_me");
        $I->seeCheckboxIsChecked("#sms_delivery_notify");
    }

    /**
     * Убрать из сборки.
     *
     * @throws \Exception
     */
    public function removeFromConfiguration()
    {
        $I = $this->tester;

        $I->wait(SHORT_WAIT_TIME);
        $index = [];
        $elements = $I->grabMultiple('//tbody[@class="configuration_tbody_items"]/tr[@class="wish_control cart_expandable__gray control-attached__js"]');
        codecept_debug($elements);
        $sortElements = array_diff($elements, array('', Null, false));
        codecept_debug($sortElements);

        foreach ($sortElements as $key => $element)
        {
            if (strpos($element, 'Убрать из сборки') !== false)
            {
                array_push($index, $key);
            }
        }
        codecept_debug($index);

        $num = mt_rand(1, count($index));
        codecept_debug('random num is ' . $num);
        $I->waitAndClick("//tr[contains(@class, 'wish_control')][".($index[$num-1]+1)."]/td/button[contains(text(),'Убрать из сборки')]", 'delete from configuration', true);
        $I->wait(SHORT_WAIT_TIME);
    }

    /**
     * Переход cо "Сборки заказа" на страницу "Способ и адрес доставки".
     *
     * @throws \Exception
     */
    public function goFromAssemblyToDelivery()
    {
        $I = $this->tester;

        $I->wait(SHORT_WAIT_TIME);
        $I->goFromCartToCheckout();

        if ($I->getNumberOfElements('//ul[@class="order_steps"]/li[@class="selected" and text()="Способ и адрес доставки"]') == 0 ) {
            Throw new \Exception('Delivery step is not selected');
        }
    }

    /**
     * Проверка что выбран стандартный вид доставки по умолчанию.
     */
    public function checkDefaultKindOfDelivery()
    {
        $I = $this->tester;

        $I->seeCheckboxIsChecked(['id' => 'delivery_deliveryType_0']);
    }

    /**
     * Покупка конфигурации в сборке".
     *
     * @throws \Exception
     */
    public function buyConfigurationInAssembly()
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(@class,"config-assemble")]', "button Купить в сборке");
        $I->waitForElementVisible('//span[contains(@class,"expandable_control_configuration__citilink")]');
    }

    /**
     * Проверка количества мест для самовывоза.
     *
     * @param int $shopsCount количество магазинов
     * @param int $pickPointsCount количество пунктов выдачи
     * @throws \Exception
     */
    public function checkPointsCount($shopsCount, $pickPointsCount)
    {
        $I = $this->tester;

        $shops = 0;
        $pickPoints = 0;

        if (($shopsCount == 0 && $pickPointsCount == 1)||($shopsCount == 1 && $pickPointsCount == 0)){
            $I->waitForElementNotVisible('//label[@class="open-delivery-choose_js radio-unclick_js"]');
        }else{
            $addresses = $I->grabTextFrom(['class' => 'delivery_info__description']);
            $address = explode(' ',$addresses);
            if (stristr($addresses, 'магаз') == true && stristr($addresses, 'выдач') == true) {
                $shops = $address[0];
                $pickPoints = $address[3];
            }elseif(stristr($addresses, 'магаз') == true && stristr($addresses, 'выдач') == false){
                $shops = $address[0];
            }elseif(stristr($addresses, 'магаз') == false && stristr($addresses, 'выдач') == false){
                $pickPoints = $address[0];
            }
            codecept_debug($shops."количество магазинов");
            codecept_debug($pickPoints."количество пунктов выдачи");
        }
    }

    /**
     * Проверка услуги сборки конфигурации в корзине.
     *
     * @param string $serviceName навзвание услуги сборки
     * @throws \Exception
     */
    public function checkConfigurationService($serviceName)
    {
        $I = $this->tester;

        $I->waitForElementVisible('//div[@class="ProductCardComponents__inner"][contains(.,"'.$serviceName.'")]');
    }

    /**
     * Получить айди товаров из корзины.
     *
     * @return array $itemsId
     */
    public function getItemsId()
    {
        $I = $this->tester;

        $itemsId = [];
        $itemsCount = $I->getNumberOfElements(self::XPATH_TABLE_ITEMS);
        for ($i = 1; $i <= $itemsCount; $i++) {
            $itemId = $I->grabAttributeFrom(self::XPATH_TABLE_ITEMS . '['.$i.']', "data-productid");
            array_push($itemsId, $itemId);
        }

        return $itemsId;
    }

    /**
     * Получение точек наличия товара.
     *
     * @return array $itemStockStores точки наличия товара
     * @throws \Exception
     */
    public function getProductStockStores()
    {
        $I = $this->tester;

        $I->waitAndClick('//a[contains(@class,"ProductCardForBasket__union-link")]', 'open stock stores popup');
        $itemStockStores = $I->grabMultiple('//div[@class="AvailabilityInStoreStoresGroupItem"]//div[@class="AvailabilityInStoreStoresGroupItem__title"]');

        return $itemStockStores;
    }

    /**
     * Проверка адреса доставки на страницы подтверждения заказа.
     *
     * @return string $address
     */
    public function checkShippingAddressOnConfirmation()
    {
        $I = $this->tester;

        $deliveryAddress = 'Москва г. , ул. '.BUYER_STREET.', д. '.BUYER_HOUSE;
        $address = $I->grabTextFrom(['class' => 'get_order']);
        $address = stristr($address, 'Москва');
        $I->seeThatValuesAreEquals($deliveryAddress, $address);

        return $address;
    }

    /**
     * Проверка наличия услуг сборки в корзине.
     *
     * @return bool $serviceCount наличие услуг сборки
     */
    public function checkCartForInstallService()
    {
        $I = $this->tester;

        $serviceCount = $I->getNumberOfElements('//tr[contains(@id,"service-item")]');

        if ($serviceCount > 0) {
            $serviceCount = true;
        }else{
            $serviceCount = false;
        }

        return $serviceCount;
    }

    /**
     * Проверка наличия информации о доставке только по телефону или в пункте выдачи.
     *
     * @throws \Exception
     */
    public function checkDeliveryByPhoneOrPickPoint()
    {
        $I = $this->tester;

        $I->waitForElementVisible('//blockquote[contains(.,"по телефону")]');
    }

    /**
     * Проверка полей контактных данных для неавторизованного пользователя.
     *
     * @throws \Exception
     */
    public function checkContactInputs()
    {
        $I = $this->tester;

        $contactInfo = $I->grabMultiple('//div[@class="pretty_form other_person"]/div[@class="form_item"]//span[@class="input"]');
        if (!empty($contactInfo)) {
            Throw new \Exception('Delivery contacts contains foreign symbols');
        }
    }

    /**
     * Получение количества товара в корзине.
     *
     * @return int $productCountFromCart количество товара в корзине
     */
    public function getProductCount()
    {
        $I = $this->tester;

        $productCountFromCart = $I->getNumberOfElements('//td[@class="product_name"]', 'cart page product counter');

        return $productCountFromCart;
    }

    /**
     * Получение стоимости услуги на стадиии сборки заказа.
     *
     * @param string $serviceId Id услуги
     * @return string Стоимость услуги
     */
    public function getServicePriceAtOrderAssembly($serviceId)
    {
        $I = $this->tester;

        $I->lookForwardTo("get current service price from " . $serviceId);
        $servicePrice = $I->getNumberFromLink(self::XPATH_TABLE_ITEMS . '//div[@data-productid="'. $serviceId .'"]//div[@class="ProductCardForBasket__price-block"]',
            'get current Service Price');

        return $servicePrice;
    }

    /**
     * Сравнение данных добавленной услуги в корзине на стадиии сборки заказа.
     *
     * @param array $serviceData данные об услуге
     * @throws \Exception
     */
    public function compareServiceDataAtOrderAssembly($serviceData)
    {
        $I = $this->tester;

        $serviceName = $this->getAddedDigitalServiceName($serviceData['id']);
        $servicePrice = $this->getServicePriceAtOrderAssembly($serviceData['id']);
        $I->seeValuesAreEquals($serviceData['price'], $servicePrice);
        $I->seeProductNameContains($serviceData['name'], $serviceName);
    }
}
