<?php

namespace Page;

class B2B
{
// include url of current page
    public static $URL = '/b2b/companies/';

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

    /**
     * Переход на форму регистрацию компании.
     *
     * @throws \Exception
     */
    public function goToCompanyRegistrationForm()
    {
        $I = $this->tester;

        $I->waitAndClick(['id' => 'createNewCompany'], 'open registration form');
        $I->waitForElementVisible('//div[@class="main_content_inner wide"]/h1[contains(.,"Регистрация")]');
    }

    /**
     * Проверка формы регистрации компании на валидность полей.
     *
     * @param string $legalForm правовая форма
     * @throws \Exception
     */
    public function fillRegistrationForm($legalForm)
    {
        $I = $this->tester;

        $I->waitAndClick(['name' => 'contractorLegalForm'], 'open registration form');
        $I->waitAndClick('//option[@value="'.$legalForm.'"]', 'choose legal form');
        $I->fillValid(['id' => 'editCompany_contractorInn'], '5993183028');
        $I->waitAndClick('//div[@class="b2b-check-exists-inn-contractor-box"]/a', 'click next button');
        $I->waitForElementVisible(['id' => 'editCompany_organizationName']);
        $I->fillValid(['id' => 'editCompany_organizationName'], 'тестовая компания');
        $I->fillValid(['id' => 'editCompany_contractorName'], 'тестовая организация ');
        $I->fillValid(['id' => 'editCompany_contractorOgrn'], '5172648351178');
        if ($legalForm != 'ip') {
            $I->fillValid(['id' => 'editCompany_contractorKpp'], '663001747');
        }

        $I->fillValid(['id' => 'editCompany_contractorUrAddressIndex'], '432000');
        $I->fillValid(['id' => 'editCompany_contractorUrAddressCity'], 'Москва');
        $I->fillValid(['id' => 'editCompany_addressStreet'], 'Красносельская');
        $I->fillValid(['id' => 'editCompany_contractorContact'], 'Тестер');
        $I->fillValid(['id' => 'editCompany_contractorFax'], '82286661488');
        $I->fillValid(['id' => 'editCompany_contractorEmail'], 'test@mail.ru');
    }

    /**
     * Проверка формы регистрации компании на сообщения об ошибках.
     *
     * @throws \Exception
     */
    public function checkRegistrationFormForErrorMessage()
    {
        $I = $this->tester;

        $I->waitAndClick(['name' => 'contractorLegalForm'], 'open registration form');
        $I->waitAndClick('//option[@value="legal"]', 'choose legal form');
        $I->fillValid(['id' => 'editCompany_contractorInn'], '5993183028');
        $I->waitAndClick('//div[@class="b2b-check-exists-inn-contractor-box"]/a', 'click next button');
        $I->clearField(['id' => 'editCompany_contractorInn']);
        $I->waitAndClick(['id' => 'editCompany_submit'], 'submit form');
        $I->waitAndClick('//div[@class="b2b-question__control"]/a', 'accept pop up');
        $mandatoryInputs = $I->getNumberOfElements('//span[@class="label label__require"]');
        $errorMessages = $I->getNumberOfElements('//div[@class="message error"]');
        $I->seeValuesAreEquals($mandatoryInputs-1,$errorMessages);
    }

    /**
     * Переход на вкладку "Электронные лицензии и подписки".
     *
     * @throws \Exception
     */
    public function goToLicensesAndSubscribes()
    {
        $I = $this->tester;

        $I->waitAndClick('//ul[@class="col"][contains(.,"Электронные лицензии и подписки")]', 'go to license');
        $I->waitForElementVisible('//div[@class="main_content_inner"]/h1[contains(.,"Электронные лицензии и подписки")]');
    }

    /**
     * Проверка отображения Velvica Iframe.
     *
     * @throws \Exception
     */
    public function checkVelvicaIframe()
    {
        $I = $this->tester;

        $I->waitForElementVisible('//iframe[@class="velvica-frame"]');
        $I->switchToIFrame('velvica-frame');
        $I->waitForElementVisible('//h2[@class="minishowcase_header"]');
        $I->waitForElementNotVisible('//body/h1[contains(.,"404")]');
    }
}
