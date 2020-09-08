<?php
namespace Page;

class Registration
{
    // include url of current page
    public static $URL = '/registration/';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */
    const REG_FORM = '//form[@class="SignUp js--SignUp"]';//путь к форме регистрации
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
     * Получение token'a для подтверждения номера телефона.
     *
     * @return mixed Token
     */
    public function getConfirmToken()
    {
        $I = $this->tester;

        return $I->grabAttributeFrom(self::REG_FORM.'//input[contains(@class,"js--SignUp__input-sms-code__container-input")]', 'data-sms-request-token');
    }

    /**
     * Ввод смс-кода для подтверждения номера телефона.
     *
     * @param string $smsCode
     * @throws \Exception
     */
    public function inputSmsCode($smsCode)
    {
        $I = $this->tester;

        $I->clearField(self::REG_FORM.'//input[contains(@class,"js--SignUp__input-sms-code__container-input")]');
        $I->waitAndFill(self::REG_FORM.'//input[contains(@class,"js--SignUp__input-sms-code__container-input")]',
            'sms code', $smsCode, true);
    }

    /**
     * Повторный запрос смс-кода.
     *
     * @throws \Exception
     */
    public function resendSmsCode()
    {
        $I = $this->tester;

        $I->waitForElementVisible(self::REG_FORM.'//span[contains(.,"Отправить еще")]', 61);
        $I->waitAndClick(self::REG_FORM.'//span[contains(.,"Отправить еще")]',
            'resend sms code', true);
        $I->waitForElementNotVisible(self::REG_FORM.'//span[contains(.,"Отправить еще")]');
    }

    /**
     * Заполнение шага контактных данных.
     *
     * @param $email
     * @param $phone
     * @param string $name Имя пользователя, для тестов используется зарезервированное UNITTEST
     * @param string $b2b юр лицо
     * @param string $password
     * @throws \Exception
     */
    public function fillRegistrationContactForm($name, $password, $email, $phone, $b2b)
    {
        $I = $this->tester;

        $I->waitAndFill(self::REG_FORM.'//input[@name="name"]',
            'registration name', $name, true);
        $I->waitAndFill(self::REG_FORM.'//input[@type="email"]',
            'registration email', $email, true);
        $I->waitAndClick('//div[@class="Popup__html ps"]//div[@class="AuthGroup__separator"]', 'click on separator');
        $loginExists = $I->getNumberOfElements('//div[@class="InputBox__error"]');
        codecept_debug('number of error messages after entering email - ' . $loginExists);
        if($loginExists != 0){
            $I->deleteUserByFriend();
            $I->reloadPage();
            $I->openRegistrationForm();
            $I->waitAndFill(self::REG_FORM.'//input[@name="email"]',
                'registration email', $email, true);
        }

        $I->waitAndFill(self::REG_FORM.'//input[@type="tel"]',
            'registration phone', $phone, true);
        $I->waitAndClick('//div[@class="Popup__html ps"]//div[@class="AuthGroup__separator"]', 'click on separator');
        $numberExists = $I->getNumberOfElements('//div[@class="InputBox__error"]');
        if($numberExists != 0){
            $I->deleteUserByFriend();
            $I->reloadPage();
            $I->openRegistrationForm();
            $I->waitAndFill(self::REG_FORM.'//input[@name="email"]',
                'registration email', $email, true);
            $I->waitAndFill(self::REG_FORM.'//input[@type="tel"]',
                'registration phone', $phone, true);
        }

        $I->waitAndClick(self::REG_FORM.'//button[contains(.,"Подтвердить")]', 'confirm registration');
        $I->waitForElementVisible(self::REG_FORM.'//span[contains(.,"Код из SMS")]');
        $this->inputSmsCode($I->getConfirmSmsCode($I->getConfirmToken()));
        $I->checkElementOnPage(self::REG_FORM.'//button[contains(@class,"js--SignUp__button_sign-up ") and @disabled]', 'button disabled');
        $I->waitAndFill(self::REG_FORM.'//input[@name="password"]',
            'registration name', $password, true);
        if ($b2b == true){
            $I->waitAndClick(self::REG_FORM.'//span[contains(.,"юридическое")]', 'select b2b');
        }
    }

    /**
     * Заполнение формы ввода смс-кода.
     *
     * @param $smsCode
     * @throws \Exception
     */
    public function submitConfirmSmsCodeForm($smsCode)
    {
        $this->inputSmsCode($smsCode);
        //$this->confirmSmsCode();
    }

    /**
     * Заполнение формы персональных данных.
     *
     * @throws \Exception
     */
    public function submitRegistrationPersonalForm()
    {
        $I = $this->tester;

        $I->waitAndClick(self::REG_FORM.'//div[@class="SignUp__sign-up"]', 'confirm registration');
        $this->skipFlashMessage();
    }

    /**
     * Закрыть всплывающее сообщение об успешной регистрации.
     *
     * @throws \Exception
     */
    public function skipFlashMessage()
    {
        $I = $this->tester;

        $I->clickButtonIfExist('//section[@class="flash-message flash-message_success"]//button', 'close flash message');
    }
}
