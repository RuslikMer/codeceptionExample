<?php
namespace Page;

class CheckOut
{
    // include url of current page
    public static $URL = '';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    const KIND_OF_DELIVERY_XPATH = '//label[@class="DeliverySubTypeCard RadioCard Radio"]';//путь к видам доставки
    const X_KBT = '//span[@class="LiftingOptions__radio-label Radio__label"]';//блок доставки КБТ
    const X_KBT_ELEVATOR = self::X_KBT . '[contains(.,"лифт")]';//Чекбокс "подъем на лифте"
    const X_KBT_UPTOSTAGE = self::X_KBT . '[contains(.,"этаж")]';//Чекбокс "подъем на этаж"
    const X_INSURER_DATA = '//div[@class="OrderSection"][contains(.,"Страхователь")]';//путь к блоку данных страхователя
    const X_INSTALL_DATA = '//div[@class="StepTwoLayout__installationService"]';//путь к блоку услуг установки
    const RADIO_BUTTON = '//label[@class="RadioButton Radio"]';
    const ORDER_INFO = '//div[@class="CheckoutLayout__aside"]//div[@class="AsideOrderInfoItem"]';//путь к блоку с информацией
    const POPUP_INFO = '//div[@class="Notices__content"]'; //поп ап с информацией о товаре
    public static $pickUpPointList = '//div[@class="ScrollableList__items"]/div[contains(@class,"OrderDeliveryCard")]';//список точек самовывоза

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
     * @var \Tester;
     */
    protected $tester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->tester = $I;
    }

    /**
     * Заполнение персональных данных в корзине.
     *
     * @param $name string имя пользователя
     * @param $familyName string фамилия пользователя
     * @param $phone string телефон пользователя
     * @throws \Exception
     * @return array $contacts
     */
    public function fillContacts($name, $familyName, $phone)
    {
        $I = $this->tester;

        $I->wait(LONG_WAIT_TIME);
        $popUp = $I->getNumberOfElements(self::POPUP_INFO.'[contains(.,"Товар недоступен")]');
        if (!empty($popUp)){
            $this->closePopUpOfNotAvailability();
        }

        $I->lookForwardTo("**** fill contact details for self delivery");
        $contactCard = $I->getNumberOfElements(['class'=>'ContactsCard']);
        if ($contactCard == 0) {
            $I->clearFieldWithAttribute(['name' => 'firstName']);
            $I->waitAndFill(['name' => 'firstName'], 'first name', $name);
            $I->clearFieldWithAttribute(['name' => 'lastName']);
            $I->waitAndFill(['name' => 'lastName'], 'last name', $familyName);
            $I->clearFieldWithAttribute(['name' => 'phone']);
            $I->waitAndFill(['name' => 'phone'], 'phone', $phone);
        }

        $contacts = array(
            "name" => $name,
            "familyName" => $familyName,
        );

        return $contacts;
    }

    /**
     * Изменение персональных данных авторизованного покупателя.
     *
     * @param $name string имя страхователя
     * @param $familyName string фамилия страхователя
     * @param $phone string телефон страхователя
     * @throws \Exception
     * @return array $contacts
     */
    public function editContacts($name, $familyName, $phone)
    {
        $I = $this->tester;

        $I->lookForwardTo("**** edit contact details");
        $I->moveMouseOver(['class' => 'ContactsCard__controls']);
        $I->waitAndClick(['class' => 'ContactsCard__controls'], 'click edit button');
        $contacts = $this->fillContacts($name, $familyName, $phone);

        return $contacts;
    }

    /**
     * Заполнение персональных данных страхователя в корзине.
     *
     * @param $name string имя страхователя
     * @param $familyName string фамилия страхователя
     * @param $phone string телефон страхователя
     * @param $email string почта страхователя
     * @throws \Exception
     * @return array $contacts
     */
    public function fillInsurerContacts($name, $familyName, $phone, $email)
    {
        $I = $this->tester;

        $I->lookForwardTo("**** fill contact details for insurance");
        $I->waitAndFill(self::X_INSURER_DATA.'//input[@name="firstName"]', 'first name', $name);
        $I->waitAndFill(self::X_INSURER_DATA.'//input[@name="lastName"]', 'last name', $familyName);
        $I->waitAndFill(self::X_INSURER_DATA.'//input[@name="phone"]', 'phone', $phone);
        $I->waitAndFill(self::X_INSURER_DATA.'//input[@name="email"]', 'email', $email);

        $contacts = array(
            "name" => $name,
            "familyName" => $familyName,
        );

        return $contacts;
    }

    /**
     * Автозаполнение персональных данных страхователя в корзине,
     * если заполнены данные покупателя.
     *
     * @param $email string почта страхователя
     * @throws \Exception
     */
    public function autoFillInsurerContacts($email)
    {
        $I = $this->tester;

        $I->waitAndClick('//span[.="Использовать данные получателя"]', 'auto fill contact details for insurance');
        $I->waitAndFill(self::X_INSURER_DATA.'//input[@name="email"]', 'email', $email);
    }

    /**
     * Выбор способа доставки - Самовывоз.
     *
     * @throws \Exception
     */
    public function chooseSelfDelivery()
    {
        $this->chooseTypeOfDelivery('Самовывоз');
    }

    /**
     * Выбор способа доставки - Курьером.
     *
     * @throws \Exception
     */
    public function chooseCourierDelivery()
    {
        $I = $this->tester;

        $this->chooseTypeOfDelivery('Доставка');
        $I->wait(SHORT_WAIT_TIME);
    }

    /**
     * Выбор способа доставки.
     *
     * @param string $type способа доставки
     * @throws \Exception
     */
    public function chooseTypeOfDelivery($type)
    {
        $I = $this->tester;

        $I->lookForwardTo("choose type of delivery");
        $I->waitForElementVisible('//button[@class="BinTab BinTab_active Tab Button"]');
        $typeOfDelivery = $I->grabTextFrom('//button[@class="BinTab BinTab_active Tab Button"]');
        codecept_debug("Type of delivery is: " . $typeOfDelivery);
        $I->wait(LONG_WAIT_TIME);
        $notAvailablePickPoint = $I->getNumberOfElements(self::POPUP_INFO.'//h1[contains(.,"недоступен")]');
        if ($notAvailablePickPoint != 0){
            $I->waitAndClick(self::POPUP_INFO.'/button[@class="Notices__close Button"]', 'close notice');
        }

        if(strpos($typeOfDelivery, $type) === false){
            $I->wait(MIDDLE_WAIT_TIME);
            $classButton = $I->grabAttributeFrom('//button[contains(.,"'.$type.'")]', 'class');
            codecept_debug($classButton);
            $disabled = stristr($classButton, 'disabled');
            if($disabled === FALSE){
                $I->waitAndClick('//button[contains(.,"'.$type.'")]', 'delivery tab');
            } else {
                Throw new \Exception('Type of delivery ' . $type . ' not available');
            }
        }

        $I->waitAndFill(['id' => 'comment'], 'comment', 'Тестовый заказ');
    }

    /**
     * Выбор сохраненного адреса доставки.
     *
     * @param int $addressNumber номер сохраненного адреса
     * @throws \Exception
     */
    public function chooseSavedAddressOfDelivery($addressNumber = null)
    {
        $I = $this->tester;

        $I->lookForwardTo("choose saved address of delivery");
        $I->waitAndClick('//label[contains(@class,"OrderDeliveryAddressCard OrderDeliveryAddressCard_current")]', 'open addresses list');
        $countOfAddresses = $I->getNumberOfElements('//label[contains(@class,"SelectSavedAddresses__item")]', 'count of addresses');
        if($addressNumber == 1){
            $I->waitAndClick('//label[contains(@class,"OrderDeliveryAddressCard OrderDeliveryAddressCard_current")]', 'select current address');
        }elseif ($countOfAddresses !== 0 ) {
            if ($addressNumber > 1){
                $addressNumber = $addressNumber - 1;
            }elseif($addressNumber == null){
                $addressNumber = mt_rand(1, $countOfAddresses);
            }

            $addressName = $I->grabTextFrom('//label[contains(@class,"SelectSavedAddresses__item")][' . $addressNumber . ']');
            $I->waitAndClick('//label[contains(@class,"SelectSavedAddresses__item")][' . $addressNumber . ']', 'select address');
            $controlAddressName = $this->getCurrentAddressName();
            $I->seeValuesAreEquals($addressName, $controlAddressName);
        }
    }

    /**
     * Проверка выбранного адреса доставки в офис Мерлион.
     *
     * @throws \Exception
     */
    public function checkSelectedMerlionAddress()
    {
        $controlAddressName = $this->getCurrentAddressName();
        if (stripos($controlAddressName, 'Красногорск, Строителей б-р, 4') === false){
            Throw new \Exception('adresses not equals');
        }
    }

    /**
     * Переход из чекаута в корзину по кнопке "Вернуться в корзину".
     *
     * @throws \Exception
     */
    public function goFromCheckoutToCart()
    {
        $I = $this->tester;

        $I->waitAndClick('//a[contains(@class,"Checkout__back-link")]', 'back to cart');
    }

    /**
     * Получение выбранного адреса доставки.
     *
     * @return string $currentAddress
     */
    public function getCurrentAddressName()
    {
        $I = $this->tester;

        $I->lookForwardTo("get current address of delivery");
        $currentAddress = $I->grabTextFrom('//label[contains(@class,"OrderDeliveryAddressCard OrderDeliveryAddressCard_current")]');

        return $currentAddress;
    }

    /**
     * Заполнение данных при курьерской доставке.
     *
     * @param $street string улица
     * @param $house string номер дома
     * @throws \Exception
     */
    public function fillContactsForCourierDelivery($street, $house)
    {
        $I = $this->tester;

        $I->lookForwardTo("**** fill contact details for courier delivery");

        $I->waitForElementVisible(['name' => 'STEP_TWO']);
        if ($I->getNumberOfElements('//label[contains(@class,"SelectSavedAddresses__item")]') == 0) {
            $city = $I->getCurrentCity();
            $I->clearFieldWithAttribute('//input[contains(@name,"city")]');
            $I->waitAndFill('//input[contains(@name,"city")]', 'city', $city);
            $I->waitForElementVisible('//span[@class="AutoSuggest__item"]');
            $I->doubleClick('//span[@class="AutoSuggest__item"]');
            $I->waitAndFill('//input[contains(@name,"street")]', 'street', $street);
            $I->waitAndFill(['name' => 'house'], 'house', $house);
        }
    }

    /**
     * Выбор вида курьерской доставки.
     *
     * @param string $kind вид доставки
     * @param bool $citilink сотрудник
     * @return string $deliveryPrice стоимость доставки
     * @throws \Exception
     */
    public function chooseKindOfDelivery($kind, $citilink)
    {
        $I = $this->tester;

        $I->lookForwardTo("**** choose kind of courier delivery");
        $deliveryPrice = 0;
        $discount = 0;
        $I->waitForElementVisible(self::KIND_OF_DELIVERY_XPATH);
        $countOfKind = $I->getNumberOfElements(self::KIND_OF_DELIVERY_XPATH);
        if ($countOfKind !== 0 &&  $citilink == false) {
            $kindOfDelivery = $I->grabTextFrom(self::KIND_OF_DELIVERY_XPATH . '/span[contains(@class,"InputBox_checked")]/..//span[@class="RadioCard__title"]');
            codecept_debug("kind of delivery is: " . $kindOfDelivery);
            if ($kindOfDelivery !== $kind) {
                $I->waitAndClick(self::KIND_OF_DELIVERY_XPATH . '[contains(.,"' . $kind . '")]', 'choose kind of delivery');
            }

            $I->waitForElementVisible(self::ORDER_INFO.'[contains(.,"Доставка")]');
            $deliveryPrice = $I->getNumberFromLink(self::ORDER_INFO.'[contains(.,"Доставка")]',
                'current delivery price');
            $I->wait(SHORT_WAIT_TIME);
            $discountAvailability = $I->getNumberOfElements(self::ORDER_INFO.'[contains(.,"Скидка")]');
            if ($discountAvailability !== 0) {
                $discount = $I->getNumberFromLink(self::ORDER_INFO.'[contains(.,"Скидка")]',
                    'discount price');
            }
        }

        $ownPrice = 0;
        $pricesArray = $I->grabMultiple('//aside[@class="AsideOrder"]//span[contains(@class,"AsideOrderProductItem__price")]');
        if (!is_array($pricesArray)){
            $pricesArray = explode(' ', $pricesArray);
            codecept_debug($pricesArray);
        }

        foreach ($pricesArray as $price) {
            $ownPrice += preg_replace("/[^0-9]/", "", $price);
        }

        $totalPrice = $I->getNumberFromLink('//div[@class="CheckoutLayout__aside"]//div[@class="AsideOrder__total AsideOrderInfoItem"]',
            'total price');
        $I->seeValuesAreEquals($totalPrice, $ownPrice+$deliveryPrice-$discount);

        return $deliveryPrice;
    }

    /**
     * Выбор стандартного вида курьерской доставки.
     *
     * @param bool $citilink сотрудник
     * @return string $deliveryPrice стоимость доставки
     * @throws \Exception
     */
    public function chooseStandardDelivery($citilink = false)
    {
        return $this->chooseKindOfDelivery('Стандартная доставка', $citilink);
    }

    /**
     * Выбор вида курьерской доставки "день в день".
     *
     * @param bool $citilink сотрудник
     * @return string $deliveryPrice стоимость доставки
     * @throws \Exception
     */
    public function chooseDeliveryInDay($citilink = false)
    {
        return $this->chooseKindOfDelivery('День в день', $citilink);
    }

    /**
     * Выбор срочного вида курьерской доставки.
     *
     * @param bool $citilink сотрудник
     * @return string $deliveryPrice стоимость доставки
     * @throws \Exception
     */
    public function chooseFastDelivery($citilink = false)
    {
        return $this->chooseKindOfDelivery('Срочная доставка', $citilink);
    }

    /**
     * Переход к списку точек самовывоза.
     *
     * @param string $type тип списка точек самовывоза
     * @return string $pickPointName название точки самовывоза
     * @throws \Exception
     */
    public function changePickupPoint($type = null)
    {
        $I = $this->tester;

        $I->lookForwardTo('change pickup point');
        $pickPointName = '';
        $I->waitForElementVisible('//div[@class="SelfDeliveryLayout"]');
        $changePickPointButton = $I->getNumberOfElements('//button[contains(.,"Изменить")]');
        if ( $changePickPointButton != 0 ){
            $I->waitAndClick('//button[contains(.,"Изменить")]', 'change pickup point');
            $I->wait(SHORT_WAIT_TIME);
            if ($type != null) {
                $this->changeTypeOfPickupPoint($type);
            }

            $pickPointName = $this->selectPickupPoint();
        }elseif ( $changePickPointButton == 0 ){
            $pickPointName = $this->selectPickupPoint();
        }

        $this->choosePayment(PAY_CASH);

        return $pickPointName;
    }

    /**
     * Оформить заказ.
     *
     * @throws \Exception
     */
    public function orderConfirmOnly()
    {
        $I = $this->tester;

        $this->choosePayment(PAY_CASH);
        $I->wait(SHORT_WAIT_TIME);
        $I->scrollTo('//span[.="Оформить заказ"]');
        $I->waitForElementClickable('//span[.="Оформить заказ"]');
        $I->waitAndClick('//span[.="Оформить заказ"]', "button Оформить заказ", true);
        $I->checkElementOnPage(['class' => 'OrderComplete__heading'], 'Поздравляем! Вы успешно оформили заказ.');
        codecept_debug('Order number is ' . $I->grabTextFrom(['class' => 'OrderCompleteItem__order-id']));
    }

    /**
     * Выбор чекбокса "Подъем на этаж" и ввод значения в поле "Этаж" - Ручной подъем.
     *
     * @param int $param Номер этажа
     * @return int $upToStagePrice стоимость подъема на этаж
     * @throws \Exception
     */
    public function liftUpToStage($param)
    {
        $I = $this->tester;

        $I->lookForwardTo('set checkbox - pickup to floor');
        $I->waitForElementNotVisible('//div[contains(@class,"RadioGroup_disabled")]'.self::X_KBT);
        $I->waitAndClick(self::X_KBT_UPTOSTAGE, 'click checkbox - pickup to floor');
        $I->lookForwardTo('set '. $param .' floor');
        $I->fillField(self::X_KBT_UPTOSTAGE.'//input', $param);
        $I->waitForElementVisible(self::ORDER_INFO.'[contains(.,"этаж")]');
        $upToStagePrice = $I->getNumberFromLink(self::ORDER_INFO.'[contains(.,"этаж")]',
            'up to stage price');
        $I->seeValueNotEmpty($upToStagePrice);

        return $upToStagePrice;
    }

    /**
     * Выбор чекбокса "Подъем на этаж", ввод значения в поле "Этаж", выбор чекбокса "Подъем на лифте".
     *
     * @param int $param Номер этажа
     * @return int $upToStagePrice стоимость подъема на этаж
     * @throws \Exception
     */
    public function liftUptoStageElevator($param)
    {
        $I = $this->tester;

        $I->lookForwardTo('set checkbox up to stage');
        $I->waitAndClick(self::X_KBT_ELEVATOR, 'click checkbox up to stage');
        $I->lookForwardTo('set N stage');
        $I->fillField(self::X_KBT_UPTOSTAGE.'//input', $param);
        $I->lookForwardTo('set checkbox Use elevator');
        $I->wait(SHORT_WAIT_TIME);
        $I->waitAndClick(self::X_KBT_ELEVATOR, 'select checkbox use elevator');
        $I->wait(SHORT_WAIT_TIME);
        $upToStagePrice = $I->getNumberFromLink(self::ORDER_INFO.'[contains(.,"этаж")]',
            'up to stage price');
        $I->seeValueNotEmpty($upToStagePrice);

        return $upToStagePrice;
    }

    /**
     * Выбор даты в календаре.
     *
     * @throws \Exception
     */
    public function chooseDate()
    {
        $I = $this->tester;

        $I->lookForwardTo("**** choose date");
        $I->click(['class' => 'react-datepicker__input-container']);
        $I->waitForElementVisible(['class' => 'react-datepicker__input-container']);
        //$date = getdate();
        //$day = $date['mday'];
        $I->waitAndClick('//div[@class="react-datepicker__month"]//div[contains(@class, "react-datepicker__day") and @aria-disabled="false"]',
            'choose date');
    }

    /**
     * Заполнение данных для услуг установки.
     *
     * @param $street string улица
     * @param $house string номер дома
     * @throws \Exception
     */
    public function fillInstallContacts($street, $house)
    {
        $I = $this->tester;

        $I->lookForwardTo("**** fill contact details for installation");
        $city = $I->getCurrentCity();
        codecept_debug($city);
        $I->waitAndFill(self::X_INSTALL_DATA.'//input[contains(@name,"city")]', 'city', $city);
        $I->waitForElementVisible('//span[@class="AutoSuggest__item"]');
        $I->doubleClick('//span[@class="AutoSuggest__item"]');
        $I->waitAndFill(self::X_INSTALL_DATA.'//input[contains(@name,"street")]', 'street', $street);
        $I->waitAndFill(self::X_INSTALL_DATA.'//input[@name="house"]', 'house', $house);
        $this->chooseDate();
    }

    /**
     * Автозаполнение данных для услуг установки,
     * если выбран способ доставки.
     *
     * @throws \Exception
     */
    public function autoFillInstallContacts()
    {
        $I = $this->tester;

        $I->lookForwardTo("**** auto fill contact details for installation");
        $I->click('//div[@class="checkboxStyleDecorator__title"]');
        $this->chooseDate();
    }

    /**
     * Отказ от услуги установки.
     *
     * @throws \Exception
     */
    public function denyInstallService()
    {
        $I = $this->tester;

        $I->lookForwardTo("**** deny installation service");
        $I->waitAndClick('//span[.="Отказаться от услуги"]', 'deny service');
        $I->waitForElementNotVisible(self::X_INSTALL_DATA);
    }

    /**
     * Заполнить почту для выставления счета для b2b пользователя.
     *
     * @param string $email почта для выставления счета
     * @throws \Exception
     */
    public function fillEmailForReceiptB2B($email)
    {
        $I = $this->tester;

        $I->waitAndFill('//div[@class="B2BPaymentLayout__email"]//input[@type="email"]', 'b2b email field', $email);
        $I->waitForElementNotVisible('//form[@class="StepThree ConfirmationPayment"]//button[contains(@class,"Button_disabled")]');
    }

    /**
     * Функция подтверждения заказа для авторизованного B2B пользователя.
     * Созданный заказ сразу отменяется.
     *
     * @param string $email почта для выставления счета
     * @param bool $cancelOrder отмена заказа, по умолчанию true
     * @throws \Exception
     */
    public function orderConfirmB2B($email, $cancelOrder = true)
    {
        $I = $this->tester;

        $I->wait(SHORT_WAIT_TIME);
        $this->fillEmailForReceiptB2B($email);
        $I->waitAndClick('//div[@class="ConfirmationPayment"]//button', "button Оформить заказ");
        $I->checkElementOnPage('//div[@class="main_content_inner"]/h1', 'Поздравляем! Вы успешно оформили заказ.');
        if ($cancelOrder != false) {
            $I->cancelOrderB2B();
        }
    }

    /**
     * Проверка  наличия галочек на чекбоксах уведомелний по СМС.
     */
    public function checkSelectedCheckboxes()
    {
        $I = $this->tester;

        $I->wait(SHORT_WAIT_TIME);
        $I->seeCheckboxIsChecked('//input[@name="smsWithOrderId"]');
        $I->seeCheckboxIsChecked('//input[@name="smsOnDeliveryInStore"]');
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
        $cart = $I->getNumberFromLink('//div[@class="AsideOrder__total AsideOrderInfoItem"]', 'total price');
        $I->waitForElementVisible(self::RADIO_BUTTON.'[contains(.,"получении")]');
        $cash = $I->getNumberOfElements(self::RADIO_BUTTON.'[contains(.,"получении")]');
        if ($cash == 0) {
            Throw new \Exception('wrong status for pay-by-cash, should be always available');
        }

        $card = $I->getNumberOfElements(self::RADIO_BUTTON.'[contains(.,"картой")]');
        if (($card == 0) AND ($cart <= LIMIT_PAY_CARD)) {
            Throw new \Exception('wrong status for pay-by-card');
        } elseif (($card == 1) AND ($cart > LIMIT_PAY_CARD)){
            Throw new \Exception('wrong status for pay-by-card');
        }

        $credit = $I->getNumberOfElements(self::RADIO_BUTTON.'[contains(.,"кредит")]');
        if (($credit == 0) AND ($cart >= LIMIT_PAY_CREDIT) AND ($installService == false)) {
            Throw new \Exception('wrong status for credit payment');
        } elseif (($credit == 1) AND ($cart < LIMIT_PAY_CREDIT) AND ($installService == false)){
            Throw new \Exception('wrong status for credit payment');
        }

        $yandex = $I->getNumberOfElements(self::RADIO_BUTTON.'[contains(.,"Яндекс")]');
        codecept_debug('Yandex payment status is disabled? - ' . $yandex);
        if (($yandex == 0) AND ($cart <= LIMIT_PAY_YANDEX)) {
            Throw new \Exception('wrong status for pay-by-yandex');
        } elseif (($yandex == 1) AND ($cart > LIMIT_PAY_YANDEX)){
            Throw new \Exception('wrong status for pay-by-yandex');
        }

        $instalments = $I->getNumberOfElements(self::RADIO_BUTTON.'[contains(.,"рассрочку")]');
        codecept_debug('Payment by instalments status is disabled? - ' . $instalments);
        if (($instalments == 0) AND ($cart <= LIMIT_PAY_YANDEX)) {
            Throw new \Exception('wrong status for pay-by-instalments');
        } elseif (($instalments == 1) AND ($cart > LIMIT_PAY_YANDEX)){
            Throw new \Exception('wrong status for pay-by-instalments');
        }

        $webMoney = $I->getNumberOfElements(self::RADIO_BUTTON.'[contains(.,"WebMoney")]');
        codecept_debug($webMoney);
        if (($webMoney == 0) AND ($cart < LIMIT_WEBMONEY)) {
            Throw new \Exception('wrong status for pay-by-webmoney');
        } elseif (($webMoney == 1) AND ($cart > LIMIT_WEBMONEY)){
            Throw new \Exception('wrong status for pay-by-webmoney');
        }
    }

    /**
     * Проверка всех ограничений типов оплаты для НЕавторизованного пользователя
     *
     * @throws \Exception В случае не ожидаемой доступности типов оплат
     */
    public function checkAllPayLimitNonAuth()
    {
        $I = $this->tester;

        $I->lookForwardTo('check payment availability');

        $I->waitForElementVisible('//div[@class="ConfirmationPayment"]');
        $cash = $I->getNumberOfElements(self::RADIO_BUTTON.'[contains(.,"получении")]');
        codecept_debug($cash);

        $card = $I->getNumberOfElements(self::RADIO_BUTTON.'[contains(.,"картой")]');
        codecept_debug($card);

        $credit = $I->getNumberOfElements(self::RADIO_BUTTON.'[contains(.,"кредит")]');
        codecept_debug($credit);

        $yandex = $I->getNumberOfElements(self::RADIO_BUTTON.'[contains(.,"Яндекс")]');
        codecept_debug($card);

        $instalments = $I->getNumberOfElements(self::RADIO_BUTTON.'[contains(.,"рассрочку")]');
        codecept_debug($card);

        $webMoney = $I->getNumberOfElements(self::RADIO_BUTTON.'[contains(.,"WebMoney")]');
        codecept_debug($credit);

        if ($cash == 0) {
            Throw new \Exception('wrong status for cash payment');
        }
        if ($card == 0) {
            Throw new \Exception('wrong status for card payment');
        }
        if ($credit != 0) {
            Throw new \Exception('wrong status for credit payment');
        }
        if ($yandex != 0) {
            Throw new \Exception('wrong status for yandex payment');
        }
        if ($instalments != 0) {
            Throw new \Exception('wrong status for payment by instalments');
        }
        if ($webMoney != 0) {
            Throw new \Exception('wrong status for webMoney payment');
        }
    }

    /**
     * Выбор точки самовывоза из списка.
     *
     * @return string $pickPointName
     * @throws \Exception
     */
    public function selectPickupPoint()
    {
        $I = $this->tester;

        $I->lookForwardTo('select pickup point');
        $I->waitForElementVisible('//div[@class="ScrollableList__items"]');
        $numberOfUnselectedStores = $I->getNumberOfElements(self::$pickUpPointList, 'unselected pickup stores');
        $I->seeValueNotEmpty($numberOfUnselectedStores);
        $num = mt_rand(1, $numberOfUnselectedStores);
        $I->moveMouseOver(self::$pickUpPointList . '['. $num .']');
        $pickPointName = $I->grabTextFrom(self::$pickUpPointList .'['. $num .']//div[@class="OrderDeliveryCard__name"]/b');
        $I->waitAndClick( self::$pickUpPointList .'['. $num .']', 'activate pick point');
        $I->waitAndClick(self::$pickUpPointList . '['. $num .']//button', 'select pick up point');
        $I->wait(SHORT_WAIT_TIME);
        $I->waitForElementVisible('//div[contains(.,"Дата и время выдачи товара")]');

        return $pickPointName;
    }

    /**
     * Выбор списка точек самовывоза Магазины\Пункты выдачи.
     *
     * @param string $type тип списка точек самовывоза
     * @throws \Exception
     */
    public function changeTypeOfPickupPoint($type)
    {
        $I = $this->tester;

        $I->waitAndClick( '//button[contains(.,"'.$type.'")]', 'change type of pick points list');
        $I->waitForElementVisible('//button[@class="BinTab BinTab_active Tab Button"][contains(.,"'.$type.'")]');
    }

    /**
     * Возвращает имя выбранной точки самовывоза.
     *
     * @return string $pointName Имя точки самовывоза
     * @throws \Exception
     */
    public function getSelectedPickPointName()
    {
        $I = $this->tester;

        $I->lookForwardTo('grab name of Selected pickup point');
        $I->waitForElementVisible('//div[@class="SelfDeliveryLayout__activeStore"]//div[@class="OrderDeliveryCard__name"]/b');
        $pointName = $I->grabTextFrom('//div[@class="SelfDeliveryLayout__activeStore"]//div[@class="OrderDeliveryCard__name"]/b');
        $debugName = $I->translit($pointName);
        codecept_debug('pickup point is ' . $debugName);

        return $pointName;
    }

    /**
     * Выбор типа оплаты
     *
     * @param int $paymentToChoose Номер метода оплаты, используются константы - PAY_CASH = 1;
     * PAY_CARD = 2;
     * PAY_CREDIT = 3;
     * PAY_YANDEX = 4;
     * PAY_TERMINAL = 5;
     * PAY_WEB = 6;
     * PAY_ORG = 7;
     * @throws \Exception
     */
    public function choosePayment($paymentToChoose)
    {
        $I = $this->tester;

        $I->lookForwardTo('choose payment of type ' . $paymentToChoose);
        $I->waitForElementVisible('//div[@class="ConfirmationPayment"]');
        $I->waitAndClick(self::RADIO_BUTTON.'[contains(.,"'. $paymentToChoose .'")]', 'payment selector');
    }

    /**
     * Проверка способа доставки - Самовывоз.
     *
     * @throws \Exception
     * @return int $notAvailableDelivery невозможность доставки
     */
    public function checkCourierDeliveryAvailable()
    {
        $I = $this->tester;

        $notAvailableDelivery = $I->getNumberOfElements('//button[contains(@class,"BinTab_disabled")][contains(.,"Доставка")]');
        if ($notAvailableDelivery == 0) {
        $this->chooseTypeOfDelivery('Доставка');
        }

        $I->wait(SHORT_WAIT_TIME);

        return $notAvailableDelivery;
    }

    /**
     * Проверка на наличие товара.
     *
     * @throws \Exception
     */
    public function checkItemsAvailability()
    {
        $I = $this->tester;

        $notAvailableItems = $I->getNumberOfElements('//div[@class="Notice"][contains(.,"Наличие товаров изменилось")]');
        if ($notAvailableItems != 0) {
            $I->waitAndClick('//button[contains(@class,"Notices__submit")][contains(.,"Продолжить оформление заказа")]', 'continue order');
        }
    }

    /**
     * Проверка отображения уведомления о некорректном вводе.
     *
     * @throws \Exception
     */
    public function checkPhoneMaskAlert()
    {
        $I = $this->tester;

        for($i = 0; $i < 10; $i++){
            $str = $I->generateString($i);
            $I->waitAndFill(['name' => 'phone'],'phone number', $str);
            $I->unFocus();
            $I->checkElementOnPage('//div[contains(@class,"inputFieldDecorator__helpRow_theme_error")]', 'message error');
        }
    }

    /**
     * Метод проверки работы маски на телефон.
     *
     * @param int $length Длина строки с номером
     * @param int $start Первые две цифры в номере
     * @throws \Exception Если строка после обработки в поле не валидна
     */
    public function checkPhoneMask($length, $start)
    {
        $I = $this->tester;
        $I->lookForwardTo('check phone mask');
        $notSeven = 0;
        do{
            $str = $I->generateString($length);
            $strWithoutChars = preg_replace('/[^0-9]/', '', $str);
            $notSeven = substr($strWithoutChars, 0, 2);
            codecept_debug("first number: $notSeven");
        } while ($notSeven != $start);
        $notStart = substr($strWithoutChars, 0, 1);
        $phone = substr($strWithoutChars, 0, $length);
        if($length > 10){
            codecept_debug('length 11');
            if($notStart == 7 || $notStart == 8) {
                $phone = substr($phone, 1);
                codecept_debug('delete 7, 8 '.$phone);
            }
        }

        $phone = "+7$phone";
        codecept_debug($phone);
        $str = preg_replace('/[^0-9]/', '', $str);
        $I->clearFieldWithAttribute(['name' => 'phone']);
        $I->waitAndFill(['name' => 'phone'],'phone number',$str{0});
        $I->waitAndFill(['name' => 'phone'],'phone number',substr($str, 1));
        $phoneGrab = $I->grabAttributeFrom(['name' => 'phone'],'value');
        $phoneGrab = str_replace('(', '', $phoneGrab);
        $phoneGrab = str_replace(')', '', $phoneGrab);
        $phoneGrab = str_replace('-', '', $phoneGrab);
        $phoneGrab = str_replace(' ', '', $phoneGrab);
        codecept_debug($phoneGrab);
        if ($phoneGrab !== $phone){
            Throw new \Exception("Fail mask, grab: $phoneGrab , expected: $phone");
        }
    }

    /**
     * Проверка поля ввода телефонного номера - 11 знаков, начало с цифры 8.
     *
     * @throws \Exception
     */
    public function checkPhoneMaskEight()
    {
        $this->checkPhoneMask(11, 89);
    }

    /**
     * Проверка поля ввода телефонного номера - 11 знаков, начало с цифры 7.
     *
     * @throws \Exception
     */
    public function checkPhoneMaskSeven()
    {
        $this->checkPhoneMask(11, 79);
    }

    /**
     * Проверка поля ввода телефонного номера.
     *
     * @throws \Exception
     */
    public function checkPhoneInput()
    {
        $this->checkPhoneMaskEight();
        $this->checkPhoneMaskSeven();
    }

    /**
     * Закрыть сообщение недоступности товара в точке самовывоза.
     *
     * @throws \Exception
     */
    public function closePopUpOfNotAvailability()
    {
        $I = $this->tester;

        $I->waitForElementVisible(self::POPUP_INFO.'[contains(.,"Товар недоступен")]');
        $I->waitAndClick(self::POPUP_INFO.'/button', 'close alert');
        $I->waitForElementNotVisible(self::POPUP_INFO.'[contains(.,"Товар недоступен")]');
    }

    /**
     * Возможность оформления заказа.
     *
     * @param string $paymentType тип оплаты
     * @param string $email почта для электронного чека
     * @throws \Exception
     */
    public function possibilityOfCheckout($email, $paymentType = null)
    {
        $I = $this->tester;

        $onlinePaymentMethods = array(PAY_CARD, PAY_YANDEX, PAY_WEBMONEY);

        if (in_array($paymentType, $onlinePaymentMethods)) {
            $I->waitForElementVisible('//span[contains(.,"Email")]');
            $I->fillValid('//div[@class="ContactForCheckLayout__email"]//input[@type="email"]', $email);
            $I->waitAndClick('//div[@class="checkboxStyleDecorator__title"][contains(.,"указаны верно")]',
                "confirm delivery user data", true);
        }

        $I->waitForElementVisible('//button[contains(@class,"buttonStyleDecorator_disabled")]');
    }
}
