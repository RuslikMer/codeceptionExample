<?php
namespace Page;
use \Facebook\WebDriver\WebDriverElement;

class Config
{
    // include url of current page
    public static $URL = '/configurator/';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $deleteConfigButton = '//button[@type="button"][contains(.,"Удалить")]';
    public static $deleteDraftButton = '//button[@type="submit"][@title="Убрать из моих конфигураций"]';
    //типы конфигураций
    const FOR_GAMES = 'Игров';
    const FOR_HOME = 'дом';
    const FOR_OFFICE = 'офис';
    const FOR_DESIGN = 'дизайн';

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
     * Добавление сборки в корзину целиком.
     *
     * @throws \Exception
     */
    public function addConfigToCart() 
    {
        $I = $this->tester;
        
        $I->lookForwardTo('select config');
        $I->waitAndClick('//button[@class="js--configuration-aside__buy-button configuration-aside__buy-button pretty_button type6"]', 'buy in config');
        $confirm = $I->getNumberOfElements(['class' => 'confirm-popup__content']);
        if ($confirm != 0){
            $I->waitAndClick('//button[contains(.,"Да")]', 'confirm');
        }

        $I->waitForElementVisible('//button[@class="next-basket-step-button__js  process_order pretty_button type6"]', ELEMENT_WAIT_TIME);
    }

    /**
     * Выбор услуги "экспресс сборка".
     *
     * @return int $service наличие услуги
     * @throws \Exception
     */
    public function chooseExpressInstall()
    {
        $I = $this->tester;

        $I->lookForwardTo('choose express install');
        $service = $I->getNumberOfElements('//label[@for="expressAssembly"]');
        if ($service != 0) {
            $I->waitAndClick('//label[@for="expressAssembly"]', 'select checkbox with express install');
            $I->seeCheckboxIsChecked("#expressAssembly");
        }else{
            $I->moveBack();
        }

        return $service;
    }

    /**
     * Выбор услуги "купить без сборки".
     *
     * @return int $service наличие услуги
     * @throws \Exception
     */
    public function chooseWithoutInstall()
    {
        $I = $this->tester;

        $I->lookForwardTo('choose without assembly');
        $service = $I->getNumberOfElements('//label[@for="withoutAssembly"]');
        if ($service != 0) {
            $I->waitAndClick('//label[@for="withoutAssembly"]', 'select checkbox without install');
            $I->seeCheckboxIsChecked("#withoutAssembly");
        }else{
            $I->moveBack();
        }

        return $service;
    }

    /**
     * Добавление в корзину компонентов сборки отдельно.
     *
     * @throws \Exception
     */
     public function orderConfigSeparated()
    {
        $I = $this->tester;
        
        $I->lookForwardTo('add separated components');
        $I->waitAndClick('//button[@class="pretty_button type1 configuration-disassemble_js"]', 'buy in config separate');
        $I->waitForElementVisible( '//button[@class="pretty_button type6 configuration-assemble_js"]');
        $I->waitForElementVisible('//div[@class="cl-preloader"]', ELEMENT_WAIT_TIME);
        $I->waitForElementNotVisible('//div[@class="cl-preloader"]', ELEMENT_WAIT_TIME);
    }

    /**
     * Применить фильтр "Есть все комплектующие".
     *
     * @param string $filter Наименование типа
     * @throws \Exception
     */
    public function filterConfigByAvailable($filter)
    {
        $I = $this->tester;
        
        $I->lookForwardTo('filter config');
        $numberElements = $I->getNumberOfElements('//div[@id="filterGroup1"]/span[contains(text(),"'. $filter .'")]',
            'all components available is selected');
        if($numberElements == 0){
            $I->waitAndClick('//div[@id="filterGroup1"]//span[@class="dropdown__toggle"]', 'open filter menu', true);
            $I->waitAndClick('//div[@id="filterGroup1"]/div/ul/li/a/span[1]', 'all components available', true);
            $I->waitAndClick('//div[@id="filterGroup1"]//span[@class="dropdown__toggle"]', 'close filter menu');
        }

        $I->see($filter, ['xpath' => '//div[@id="filterGroup1"]/span']);
    }

    /**
     * Выбор фильтрации конфигураций по типу и ее проверка.
     *
     * @param $filter string Наименование типа, либо часть наименования
     * @throws \Exception
     */
    public function filterByType($filter)
    {
        $I = $this->tester;

        $I->lookForwardTo('filter config by type' . $filter);
        $I->waitAndClick('//div[@class="configurator-menu-slider__text"][contains(., "'. $filter .'")]', 'select filter'.$filter);
        $arrayType = $I->grabMultiple(['xpath' => '//div[@class="configurator-list__items"]//span[@class="item"][1]']);
        codecept_debug($arrayType);
        foreach ($arrayType as $type){
            if(stristr($type, $filter) === FALSE) {
                Throw new \Exception('Not filter by type ' . $filter);
            }
        }
    }

    /**
     * Выбор фильтра по типу - Игровой.
     *
     * @throws \Exception
     */
    public function filterGaming()
    {
        $this->filterByType('Игров');
    }

    /**
     * Выбор фильтра по типу - Для дома.
     *
     * @throws \Exception
     */
    public function filterHome()
    {
        $this->filterByType('Для дома');
    }

    /**
     * Выбор фильтра по типу - Для офиса.
     *
     * @throws \Exception
     */
    public function filterOffice()
    {
        $this->filterByType('Для офиса');
    }

    /**
     * Выбор фильтра по типу - Для дизайна.
     *
     * @throws \Exception
     */
    public function filterDesign()
    {
        $I = $this->tester;

        $I->waitAndClick('//button[@class="slick-next slick-arrow"]', 'scroll slick track');
        $this->filterByType('Для дизайна');
    }

    /**
     * Проверка фильтрации по типу.
     *
     * @throws \Exception
     */
    public function checkFilterByType()
    {
        $this->filterGaming();
        $this->filterHome();
        $this->filterOffice();
        $this->filterDesign();
    }

    /**
     * Проверка фильтрации списка конфигураций по цене.
     *
     * @throws \Exception
     */
    public function checkFilterByPrice()
    {
        $I = $this->tester;

        $arrayFilters = $I->grabMultiple(['xpath' => '//div[@id="filterGroup4"]//ul[contains(@class,"filters-popup")]/li']);
        foreach ($arrayFilters as $key => $value){
            $I->waitAndClick('//div[@id="filterGroup4"]/span', 'dropdown filter');
            $position = $key + 1;
            $quantity = $I->getNumberFromLink('//div[@id="filterGroup4"]//ul[contains(@class,"filters-popup")]/li['. $position .']//span[@class="filters-popup__link-total"]',
                "quantity from filter");
            $I->waitAndClick('//div[@id="filterGroup4"]//ul[contains(@class,"filters-popup")]/li['. $position .']', 'filter');
            $I->waitForElementChange(['xpath' => '//span[@class="configurator-list__total configurator-list__total_top"]'], function(WebDriverElement $el) {
                return $el->isDisplayed();
            }, 10);
            $total = $I->getNumberFromLink('//span[@class="configurator-list__total configurator-list__total_top"]', "quantity from listing");
            $I->seeQuantityConfigEqual($quantity, $total);
        }
    }

    /**
     * Проверка фильтра по типу платформы.
     *
     * @throws \Exception
     */
    public function checkFilterByCpu()
    {
        $I = $this->tester;

        $arrayFilters = $I->grabMultiple(['xpath' => '//div[@id="filterGroup10"]//ul[contains(@class,"filters-popup")]/li']);
        codecept_debug($arrayFilters);
        $initListing = $this->getListing();
        codecept_debug($initListing);
        foreach ($arrayFilters as $key => $value){
            $I->waitAndClick('//div[@id="filterGroup10"]/span', 'dropdown filter');
            $position = $key + 1;
            $I->waitAndClick('//div[@id="filterGroup10"]//ul[contains(@class,"filters-popup")]/li['. $position .']', 'filter');
            $I->waitForElementChange(['xpath' => '//span[@class="configurator-list__total configurator-list__total_top"]'], function(WebDriverElement $el) {
                return $el->isDisplayed();
            }, 10);
            $grabListing = $this->getListing();
            codecept_debug($grabListing);
            $I->seeProductsListingNotEquals($initListing, $grabListing);
            $initListing = $grabListing;
        }
    }

    /**
     * Получить список конфигураций.
     *
     * @return string[] Список конфигураций
     */
    public function getListing()
    {
        $I = $this->tester;

        $arrayConfig = $I->grabMultiple(['xpath' => '//div[@class="configurator-list__items"]//span[@class="h3 configuration-card__name"]']);
        $I->seeArrayNotEmpty($arrayConfig);

        return $arrayConfig;
    }

    /**
     * Проверка сортировки.
     *
     * @throws \Exception
     */
    public function checkSort()
    {
        $I = $this->tester;

        $arrayFilters = $I->grabMultiple(['xpath' => '//div[@id="sorting"]//li']);
        codecept_debug($arrayFilters);
        array_shift($arrayFilters);
        $initListing = $this->getListing();
        codecept_debug($initListing);
        $I->waitAndClick(['id' => 'sorting'], 'dropdown sort');
        foreach ($arrayFilters as $key => $value){
            $I->waitAndClick('//div[@class="configurator-list__sorting sorting"]//span', 'dropdown sort');
            $position = $key + 2;
            $I->waitAndClick('//div[@class="configurator-list__sorting sorting"]//div/ul/li[' . $position . ']/span', 'sort');
            $I->waitForElementChange(['xpath' => '//span[@class="configurator-list__total configurator-list__total_top"]'],
                function (WebDriverElement $el) {
                    return $el->isDisplayed();
                }, 10);
            $grabListing = $this->getListing();
            codecept_debug($grabListing);
            $I->seeProductsListingNotEquals($initListing, $grabListing);
            $initListing = $grabListing;
        }
    }

    /**
     * Переход на страницу конфигурации.
     *
     * @throws \Exception
     */
    public function goToConfigCard()
    {
        $I = $this->tester;

        $I->lookForwardTo('select config');
        $configCount = $I->getNumberOfElements('//div[contains(@class,"configuration-card__footer_user")]//a[contains(.,"Подробнее")]');
        $configNum = mt_rand(1, $configCount);
        $I->waitAndClick('//div[@class="configuration-card configuration-card"]['.$configNum.']//div[contains(@class,"configuration-card__footer_user")]//a[contains(.,"Подробнее")]',
            'config in listing');
        $I->waitForText('Состав конфигурации');
    }

    /**
     * Клик по ссылке на выгрузку конфигурации в Excel.
     *
     * @throws \Exception
     */
    public function downloadConfigExcel()
    {
        $I = $this->tester;

        $I->lookForwardTo('download excel file');
        $I->waitAndClick('//div[@class="configuration-aside__excel-section"]/a', 'download excel link');
    }

    /**
     * Добавление конфигурации в избранное.
     *
     * @throws \Exception
     */
    public function addToMyConfig()
    {
        $I = $this->tester;

        $I->lookForwardTo('add to my config');
        $I->waitAndClick('//button[@type="submit"][contains(.,"Добавить в мои конфигурации")]', 'add to my config button');
        $I->waitForText('Сохранено в моих конфигурациях ');
    }

    /**
     * Удаление конфигурации из избранного.
     *
     * @throws \Exception
     */
    public function deleteFromMyConfig()
    {
        $I = $this->tester;

        $I->lookForwardTo('delete config');
        $I->waitAndClick('//button[@type="submit"][@title="Убрать из моих конфигураций"]', 'delete config button');
        $I->waitForText('Добавить в мои конфигурации');
    }

    /**
     * Проверка добавления и удаления из избранного.
     *
     * @throws \Exception
     */
    public function checkFavoriteConfig()
    {
        $this->addToMyConfig();
        $this->deleteFromMyConfig();
    }

    /**
     * Проверка совместимости конфигурации.
     *
     * @return bool $bool true/false
     * @throws \Exception
     */
    public function checkCompatibilityOfConfig()
    {
        $I = $this->tester;

        $I->checkElementOnPage(['class' => 'main_content_inner']);
        $bool = null;
        $I->lookForwardTo('check compatibility');
        $compatible = $I->grabTextFrom('//span[contains(@class, "configurator-compatibility__explain")]');
        $error = $I->getNumberOfElements(['class' => 'configuration-aside__why']);
        if(strpos($compatible, 'Компоненты системного блока совместимы*') && $error == 0) {
            $bool = true;
        }
        else {
            $bool = false;
        }

        return $bool;
    }

    /**
     * Переход в корзину через поп ап (при выборе услуги "без сборки").
     *
     * @throws \Exception
     */
    public function goToCartFromPopUp()
    {
        $I = $this->tester;

        $I->switchToNextTab();
        $I->waitForElementVisible(['id' => 'ui-id-1']);
        $I->waitAndClick('//div[@id="ui-id-1"]//a[contains(.,"корзину")]', 'go to cart');
        $I->waitForElementVisible('//button[contains(@class,"configuration-assemble_js")][not(contains(@disabled,"disabled"))]');
    }

    /**
     * Получить id всех комплектующих сборки.
     *
     * @return array $allId список всех id комплектующих
     * @throws \Exception
     */
    public function getAllId()
    {
        $I = $this->tester;

        $I->waitForElementVisible('//div[contains(@class,"configuration-product-item")]');
        $allId = $I->grabMultiple('//div[contains(@class,"configuration-product-item")][@data-product-id]', 'data-product-id');

        return $allId;
    }

    /**
     * Переход на страницу создания конфигурации.
     *
     * @throws \Exception
     */
    public function goToConfigurationCreation()
    {
        $I = $this->tester;

        $I->waitAndClick('//div[contains(@class,"js--configurator-menu__create-button")]', 'go to configuration creation');
        $I->waitForElementVisible('//div[@class="configuration-add-feature-item"]');
    }

    /**
     * Поиск комплектующих по id.
     *
     * @param string $id айди комплектующего
     * @throws \Exception
     */
    public function addConfigComponents($id)
    {
        $I = $this->tester;

        $I->waitAndFill(['name' => 'productId'],'fill search field' ,$id);
        $I->waitAndClick('//input[contains(@class,"configuration-add-feature-by-id__submit")]', 'add components to configuration');
        $I->waitForElementVisible('//div[@class="configuration-feature__package"]//div[@data-product-id="'.$id.'"]');
    }

    /**
     * Добавить конфигурацию в корзину.
     *
     * @throws \Exception
     */
    public function addConfigurationToCart()
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(@class,"js--configuration-aside__buy-button")]', 'click to buy button');
    }

    /**
     * Сохранить конфигурацию в список своих из общего списка конфигураций.
     *
     * @throws \Exception
     */
    public function saveToMyConfigList()
    {
        $I = $this->tester;

        $I->lookForwardTo('save to my config list');
        $I->waitAndClick('//button[@type="submit"][contains(.,"Сохранить")]', 'save to my config list button');
        $I->waitForText('Сохранена');
    }

    /**
     * Нажать "использовать для новой сборки".
     *
     * @throws \Exception
     */
    public function useForNewConfig()
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(.,"Использовать для новой сборки")]', 'use for new config button');
        $I->wait(SHORT_WAIT_TIME);
        $message = $I->getNumberOfElements('//button[contains(.,"Сохранить")]');
        if ($message == 0){
            $I->waitForText('Сборка собственного системного блока');
        }
    }

    /**
     * Получить id сборки.
     *
     * @return string $id
     * @throws \Exception
     */
    public function getConfigId()
    {
        $I = $this->tester;

        $id = $I->grabAttributeFrom('//div[@id="configurationInfo"]', 'data-id');

        return $id;
    }

    /**
     * Сохранить черновик сборки.
     *
     * @param string $configType
     * @throws \Exception
     */
    public function saveConfig($configType)
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(.,"Сохранить")]', 'save draft');
        $I->waitAndFill(['name'=>'name'], 'fill name input', 'Тест');
        $I->waitAndFill(['name'=>'description'], 'fill description input', 'Тестовая');
        $I->waitAndClick('//div[contains(@class,"ui-dialog")]//button[contains(.,"Сохранить конфигурацию")]', 'save new config');
        if ($configType == 'Draft') {
            $I->waitForText('Сборка собственного системного блока', 30);
        }else{
            $I->waitForElementVisible('//button[contains(.,"Редактировать")]');
        }
    }

    /**
     * Удалить черновик сборки.
     *
     * @throws \Exception
     */
    public function deleteConfigDraft()
    {
        $I = $this->tester;

        $I->waitAndClick('//button[contains(.,"Удалить")]', 'delete draft');
    }

    /**
     * Выбор группы компонентов.
     *
     * @param string $group
     * @throws \Exception
     */
    public function chooseComponentGroup($group)
    {
        $I = $this->tester;

        $I->waitAndClick('//div[@class="configuration-add-feature-item"][contains(.,"'.$group.'")]', 'component group');
        $I->waitForText($group);
    }

    /**
     * Переход в список моих конфигураций.
     *
     * @throws \Exception
     */
    public function goToMyConfigList()
    {
        $I = $this->tester;

        $I->waitAndClick('//div[contains(@class,"configurator-my-conf-link")][contains(.,"Мои конфигурации")]', 'open config list');
        $I->waitForElementVisible('//div[contains(@class,"configurator-orange-button_active")]');
    }

    /**
     * Проверить наличие конфигурации в списке своих конфигураций.
     *
     * @param string $id номер сборки
     * @throws \Exception
     */
    public function checkConfigAvailabilityInMyList($id)
    {
        $I = $this->tester;

        $I->waitForElementVisible('//button[@data-id="'.$id.'"]');
    }

    /**
     * Удаление конфигураций из своего списка.
     *
     * @throws \Exception
     */
    public function deleteConfigFromMyList()
    {
        $I = $this->tester;

        $I->lookForwardTo('delete config');

        while (($I->getNumberOfElements(self::$deleteConfigButton)) != 0){
            $I->waitAndClick(self::$deleteConfigButton, 'delete config button');
            $I->waitAndClick('//button[contains(.,"Удалить конфигурацию")]', 'delete config button');
            $I->reloadPage();
        }

        while (($I->getNumberOfElements(self::$deleteDraftButton)) != 0){
            $I->waitAndClick(self::$deleteDraftButton, 'delete config button');
            $I->reloadPage();
        }

        $I->reloadPage();
        $configCount = $I->getNumberFromLink('//span[@class="configurator-list__total configurator-list__total_top"]', 'count of config');
        $I->seeValuesAreEquals($configCount, 0);
    }
}
