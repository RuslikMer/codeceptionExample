<?php

namespace Page;

class Login {

    // include url of current page
    public static $URL = '/login/';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    public static $loginButton = '//span[contains(@class,"js--SignIn__action_sign-in__text")][contains(.,"Войти")]';
    public static $loginPopup = '//form[contains(@class,"SignIn")]';
    const B2B = 'Контрагент';

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

    /**
     * Выбираем компанию при авторизации b2b, скрываем форму анкеты.
     *
     * @throws \Exception
     */
    public function chooseCompanyWhileLoginQuestionnaireClose()
    {
        $I = $this->tester;

        $I->waitAndClick(Cart::USER_MENU_LINK, 'button profile');
        $I->waitForElementVisible(Cart::USER_MENU_LINK . '//div[@class="UserMenu__footer"]');
        $I->waitAndClick(Cart::USER_MENU_LINK . '//div[@class="Accordion"]', 'switch accordeon');
        $I->waitAndClick(Cart::USER_MENU_LINK . '//li[@class="UserMenu__menu-item"]/a[contains(.,"Контрагент")]', 'choose company');
    }

    /**
     * Выбираем физлицо при авторизации.
     *
     * @param string $userType контрагент B2B \физлицо
     * @throws \Exception
     */
    public function chooseTypeOfContractorAfterLogin($userType)
    {
        $I = $this->tester;

        if (is_null($userType)){
            $user = $I->getUserByType();
            $userType = $user['username'];
        }

        $I->waitAndClick(Cart::USER_MENU_LINK, 'open user menu');
        $I->waitAndClick('//div[@class="Accordion"]//span[contains(.,"Переключиться")]', "open users types");
        $I->waitAndClick('//li[@class="UserMenu__menu-item"][contains(.,"'.$userType.'")]', 'choose contractor');
        $I->waitAndClick(Cart::USER_MENU_LINK, 'open user menu');
        $I->checkElementOnPage('//div[@class="UserMenu__header"][contains(.,"'.$userType.'")]');
    }

    /**
     * Скрытие окна с подтверждением номера телефона.
     *
     * @throws \Exception
     */
    public function closeConfirmPhoneWindow()
    {
        $I = $this->tester;

        if($I->getNumberOfElements('//div[@class="phone-confirm-message-box__btn-box"]/a[text()="Подтвердить телефон"]', 'confirm phone window') > 0) {
            $I->waitAndClick('//div[contains(@class,"Popup__show-popup")]//button[@class="Popup__close"]', 'close confirmation window button');
        }
    }

    /**
     * Проверка наличия и корректного перехода по кнопке подтверждения номера телефона.
     *
     * @throws \Exception
     */
    public function goToEmailConfirmation()
    {
        $I = $this->tester;

        $I->waitForElementVisible('//div[@class="phone-confirm-message-box__btn-box"]/a[text()="Подтвердить телефон"]');
        $I->waitAndClick('//div[@class="phone-confirm-message-box__btn-box"]/a[text()="Подтвердить телефон"]', 'go to confirmation');
        $I->waitForElementVisible(['class' => 'js--profile__confirm-email-btn']);
    }

    /**
     * Авторизация B2B через форму сверху справа.
     *
     * @param string $name Логин пользователя
     * @param string $password Пароль пользователя
     * @throws \Exception
     */
    public function doLoginTopRightB2B($name, $password)
    {
        $this->doLoginTopRight($name, $password);
        $this->chooseCompanyWhileLoginQuestionnaireClose();
    }

    /**
     * Авторизация через форму сверху справа.
     *
     * @param string $name Логин пользователя
     * @param string $password Пароль пользователя
     * @throws \Exception
     */
    public function doLoginTopRight($name, $password)
    {
        $I = $this->tester;

        //$I->disableCaptchaCheck();
        $I->openLoginRegistrationWindow();
        $I->wait(SHORT_WAIT_TIME);
        $I->submitForm(self::$loginPopup, [
            'login' => $name,
            'pass' => $password
        ], 'submit');
    }

    /**
     * Раскрытие окна регистрации/авторизации.
     *
     * @throws \Exception
     */
    public function openLoginRegistrationWindow()
    {
        $I = $this->tester;

        $I->waitAndClick('//a[contains(.,"Войти")]', 'login/registration link');
        $I->checkElementOnPage('//div[contains(@class,"AuthGroup")]', 'popup auth');
    }

    /**
     * Логаут пользователя.
     *
     * @throws \Exception
     */
    public function doLogout()
    {
        $I = $this->tester;

        $I->waitAndClick(Cart::USER_MENU_LINK, 'user menu');
        $I->waitAndClick('//div[contains(@class,"UserMenu")]//a[@class="UserMenu__menu-link UserMenu__menu-link_logout"]',
            'logout button');
    }

    /**
     * Логин со страницы ошибочного пароля.
     *
     * @param string $name
     * @param string $password
     * @throws \Exception
     */
    public function doLoginAfterFailEnter($name, $password)
    {
        $I = $this->tester;

        $I->lookForwardTo('login from wrong-auth page');
        $I->doLoginFromPage($name, $password);
    }

    /**
     * Логин со страницы /login/.
     *
     * @param string $name
     * @param string $password
     * @throws \Exception
     */
    public function doLoginFromPage($name, $password)
    {
        $I = $this->tester;

        //$I->disableCaptchaCheck();
        $I->lookForwardTo('login from /login/ page');
        $I->waitAndFill('//div[@class="LoginPageLayout"]//input[@name="login"]', 'username field', $name);
        $I->waitAndFill('//div[@class="LoginPageLayout"]//input[@name="pass"]', 'password field', $password);
        $I->waitAndClick(['id' => 'formSubmit'], 'login button', true);
    }

    /**
     * Проверка страницы ошибочного ввода пароля.
     *
     * @throws \Exception
     */
    public function checkFailEnterPage()
    {
        $I = $this->tester;

        $I->lookForwardTo('do fail login');
        $this->doLoginFromPage('sdfsdf', '34534');
        $I->checkElementOnPage(['class' => 'LoginPageLayout__error-message'], 'warning block - wrong pass');
    }

    /**
     * Проверка, что авторизация прошла успешно.
     *
     * @throws \Exception
     */
    public function checkLoginDropdown()
    {
        $I = $this->tester;

        try{
            $I->checkElementOnPage('//div[contains(@class,"DropDown__trigger")]');
        }catch (\Exception $e){
            $I->fail('Authorization was not successful');
        }
    }

    /**
     * Авторизация через форму сверху справа заведомо некорректными учетными данными.
     *
     * @param string $username Логин пользователя
     * @param string $password Пароль пользователя
     * @throws \Exception
     */
    public function doLoginFail($username, $password)
    {
        $I = $this->tester;

        //$I->disableCaptchaCheck();
        $I->openLoginRegistrationWindow();
        $I->waitForElementClickable(self::$loginButton);
        $I->submitForm(self::$loginPopup, [
            'login' => $username,
            'pass' => $password
        ], 'submit');
    }
}
