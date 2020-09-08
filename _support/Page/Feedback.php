<?php
namespace Page;

class Feedback
{
    // include url of current page
    public static $URL = '/feedback/';

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
        return static::$URL . $param;
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
     * Открыть окно формы обратной связи.
     *
     * @throws \Exception
     */
    public function openFeedbackForm()
    {
        $I = $this->tester;

        $I->lookForwardTo('open feedback form from top');
        $I->waitAndClick('//div[@class="Footer__contact-buttons-block"]//button[contains(.,"вопрос")]', 'feedback form');
        $I->checkElementOnPage('//div[contains(@class,"js--MainMenu__feedback-popup__popup-content")]',
            'feedback dropdown window');
    }

    /**
     * Открыть окно формы "перезвонить".
     *
     * @throws \Exception
     */
    public function openCallbackForm()
    {
        $I = $this->tester;

        $I->lookForwardTo('open Callback form');
        $I->waitAndClick('//div[@class="Footer__contact-buttons-block"]//button[contains(.,"Перезвонить")]', 'Callback form');
        $I->checkElementOnPage('//div[contains(@class,"js--MainMenu__callback-popup__popup-content")]',
            'callback dropdown window');
    }

    /**
     * Проверка формы "Перезвонить мне".
     *
     * @param string $phone Номер телефона
     * @param string $name Имя покупателя
     * @param string $captcha капча если необходимо
     * @throws \Exception
     */
    public function checkCallBackForm($phone, $name, $captcha = null)
    {
        $I = $this->tester;

        $I->lookForwardTo('check call back form');
        $I->waitAndFill(['name' => 'request_call[phone]'], 'phone number', $phone);
        $I->waitAndFill(['name' => 'request_call[name]'], 'customer name', $name);
        $I->waitAndClick('//label[@class="Checkbox js--CallMeBackInPopupView__checkbox-privacy"]', 'checkbox privacy');
        if ($captcha != null) {
            $I->waitAndFill('//div[@class="CallMeBackInPopupView__captcha"]//input[@name="captcha"]', 'captcha', $captcha);
            $I->checkElementOnPage('//button[contains(@class,"js--CallMeBackInPopupView__button-submit") and not(contains(@disabled,"disabled"))]',
                'confirm button enabled');
        }

        $I->waitAndClick('//button[contains(@class,"js--CallMeBackInPopupView__button-submit")]', 'confirm button', true);
        if ($captcha != null) {
            $I->waitForElementVisible('//div[@class="Popup__message"][contains(.,"Неверно введен код!")]');
        }
    }

    /**
     * Проверка формы "Отправить вопрос".
     *
     * @param string $name Имя
     * @param string $email Электронная почта
     * @param string $question Текст вопроса
     * @param bool $b2b для юр лица
     * @param string $captcha капча если необходимо
     * @throws \Exception Если кнопка "Отправить вопрос" недоступна
     */
    public function checkAskForm($name, $email, $question, $b2b, $captcha)
    {
        $I = $this->tester;

        $I->lookForwardTo('check ask form');
        $I->waitAndFill(['name' => 'feedback[question]'], 'question', $question);
        $I->waitAndFill(['name' => 'feedback[phone]'], 'email', $email);
        $I->waitAndClick('//button[contains(.,"Продолжить")]', 'next form');
        if ($b2b != false) {
            $I->waitAndClick('//div[@class="FeedbackInPopupView__content"]//label[.="Я юридическое лицо"]', 'select b2b');
            $I->waitAndFill(['name' => 'feedback[company]'], 'b2b name', 'TEST company');
        }

        $I->waitAndFill(['name' => 'feedback[name]'], 'name', $name);
        if ($captcha != null) {
            $I->waitAndFill('//div[@class="FeedbackInPopupView__captcha FeedbackInPopupView__inputItem"]//input[@name="captcha"]', 'captcha', $captcha);
        }

        $I->waitAndClick('//button[contains(@class,"FeedbackInPopupView__button-body")][contains(.,"Отправить")]', 'confirm button', true);
        if ($captcha != null) {
            $I->waitForElementVisible('//div[@class="Popup__message"][contains(.,"Неверно введен код")]');
        }
    }

    /**
     * Проверка сообщения о неверно введеном коде.
     *
     * @throws \Exception При отсутствии сообщения
     */
    public function checkMessageWrongCode()
    {
        $I = $this->tester;

        $I->wantTo('check message - wrong code');
        $I->checkElementOnPage('//pre[contains(.,"\u041d\u0435\u0432\u0435\u0440\u043d\u043e \u0432\u0432\u0435\u0434\u0435\u043d \u043a\u043e\u0434!")]',
            'message');
    }
}
