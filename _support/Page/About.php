<?php
/**
 * Created by PhpStorm.
 * User: alekseev.v
 * Date: 14.12.2016
 * Time: 17:50
 */

namespace Page;


class About
{
    // include url of current page
    public static $URL = '/brands/';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    const TYPE_MAIN_MINI = 'Пункты выдачи';
    const TYPE_SHOPS = 'Магазины';
    const TYPE_PARTNER = 'Партнерские';
    const XPATH_DIGITAL_SERVICE_BLOCK = '//table[@class="data item"]'; //путь к блокам цифровых услуг
    const XPATH_PROTECTION_SERVICE_BLOCK = '//div[@class="table"]'; //путь к блокам цифровых услуг

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
     * Выбор фильтров по типу на странице адресов магазинов.
     *
     * @param string $type тип адреса магазина TYPE_MINI, TYPE_MAIN_MINI, TYPE_SHOPS, TYPE_PARTNER
     * @throws \Exception
     */
    public function chooseTypeOfStore($type)
    {
        $I = $this->tester;

        $I->waitAndClick('//label[contains(.,"'.$type.'")]', 'type of address');
        $I->wait(LONG_WAIT_TIME);
    }

    /**
     * Проверка поиск адреса магазина на emoji.
     *
     * @param string $param запрос поиска
     * @throws \Exception
     */
    public function checkSearchStoreAddressForEmoji($param)
    {
        $I = $this->tester;
        $I->waitAndFill(['name' => 'search'], 'search address',$param);
        $I->pressKey(['name' => 'search'], \WebDriverKeys::ENTER);
        $I->checkMandatoryElements();
    }

    /**
     * Переход на страницу магазина из списка.
     *
     * @return mixed название выбранного магазина
     * @throws \Exception
     */
    public function selectStore()
    {
        $I = $this->tester;

        $I->lookForwardTo('select store');
        $storeCount = $I->getNumberOfElements('//div[@class="StoresGroup__content"]/div/div', 'stores');
        $storeRnd = mt_rand(1, $storeCount);
        $storeName = $I->grabTextFrom('//div[@class="StoresGroup__content"]/div['. $storeRnd .']/div//a');
        $I->waitAndClick('//div[@class="StoresGroup__content"]/div['. $storeRnd .']/div//a', ''. $storeRnd .' store');
        $selectedStoreName = $I->grabTextFrom('//h1');
        if (!stristr($storeName, $selectedStoreName)){
            Throw new \Exception("Wrong store page: need - ".$storeName." , grab - ".$selectedStoreName);
        }

        return $storeName;
    }

    /**
     * Проверка страницы магазина.
     *
     * @param string $typeStore Тип магазина
     * @throws \Exception Если тип магазина не соотвествует типу переданному в параметре
     */
    public function checkStorePage($typeStore)
    {
        $I = $this->tester;

        $I->lookForwardTo('check page store');
        $bad = $I->grabAttributeFrom(['xpath' => '//div[@class="main_content_wrapper"]//div[@id="store_div"]'], 'data-location');
        $good = json_decode($bad, TRUE);
        $grabTypeStore = $good["type"];
        codecept_debug('store type is ' . $grabTypeStore);
        if($typeStore == self::TYPE_SHOPS){
            $typeStore = 'full';
        }

        $I->seeValuesAreEquals($typeStore, $grabTypeStore);
        $I->checkElementOnPage('//div[@class="main_content_wrapper"]//div[@id="store_div"]/div[@class="route_delivery_info"]', 'route information');
        $I->checkElementOnPage('//div[@class="main_content_wrapper"]/aside/div[@id="mapBox"]', 'map');
    }

    /**
     * Добавление программы страхования в корзину.
     *
     * @return array $serviceData выбранная программа страхования
     * @throws \Exception
     */
    public function addProtectionServiceToCart()
    {
        $I = $this->tester;

        $I->waitForElementVisible('//div[@class="block desc-block"][contains(.,"Стоимость программы и объем защиты")]');
        $servicesCount = $I->getNumberOfElements(self::XPATH_PROTECTION_SERVICE_BLOCK.'//thead/tr[2]/th', 'services');
        $serviceNum = mt_rand(1, $servicesCount);
        $serviceName = $I->grabTextFrom(self::XPATH_PROTECTION_SERVICE_BLOCK.'//thead/tr[2]/th['.$serviceNum.']');
        codecept_debug('название услуги со страницы - "' . $serviceName . '"');
        $serviceName = substr($serviceName, 0, -6);
        codecept_debug('название услуги после удаления последних символов - "' . $serviceName . '"');
        $serviceCost = $I->getNumberFromLink(self::XPATH_PROTECTION_SERVICE_BLOCK.'//tr[contains(.,"Стоимость")]/td[not(contains(.,"Стоимость"))]['.$serviceNum.']', 'service cost');
        $serviceId = $I->grabAttributeFrom(self::XPATH_PROTECTION_SERVICE_BLOCK.'//tr[6]/td['.($serviceNum+1).']/div/button', 'data-itemid');
        $I->waitAndClick(self::XPATH_PROTECTION_SERVICE_BLOCK.'//tr[6]/td['.($serviceNum+1).']/div/button', 'add service');

        $serviceData = array(
            "name" => $serviceName,
            "price" => $serviceCost,
            "id" => $serviceId
        );
        codecept_debug($serviceData);

        return $serviceData;
    }

    /**
     * Переход в корзину по кнопке "в корзине" со страницы страхования.
     *
     * @param string $xpath путь к блоку услуги
     * @throws \Exception
     */
    public function goToCartByButton($xpath)
    {
        $I = $this->tester;

        $I->waitAndClick($xpath.'//div[not(contains(@class,"not_display"))]/a', 'go to cart');
        $I->waitForElementVisible('//div[@class="Basket__title"][contains(.,"Корзина")]');
    }

    /**
     * Выбор категории товаров в цифровых услугах.
     *
     * @throws \Exception
     */
    public function chooseDigitalServiceItemsCategory()
    {
        $I = $this->tester;

        $categoriesCount = $I->getNumberOfElements('//ul[@class="col"]', 'products categories');
        $categoryNum = mt_rand(1, $categoriesCount);
        $I->waitAndClick('//ul[@class="col"]['.$categoryNum.']/li', 'open category');
        $I->waitForElementVisible('//a[. = "Цифровые услуги"]');
    }

    /**
     * Добавление цифровой услуги в корзину.
     *
     * @return array $serviceData выбранная цифровая услуга
     * @throws \Exception
     */
    public function addDigitalServiceToCart()
    {
        $I = $this->tester;

        $servicesCount = $I->getNumberOfElements(self::XPATH_DIGITAL_SERVICE_BLOCK, 'services');
        $serviceNum = mt_rand(1, $servicesCount);
        $serviceName = $I->grabTextFrom(self::XPATH_DIGITAL_SERVICE_BLOCK.'['.$serviceNum.']//td[@class="l"]');
        codecept_debug($serviceName);
        $serviceData = $I->grabAttributeFrom(self::XPATH_DIGITAL_SERVICE_BLOCK.'['.$serviceNum.']//div[@class="button actions"]',
            'data-params');
        codecept_debug($serviceData);
        $serviceData = json_decode($serviceData);
        $serviceCost = $serviceData->price;
        $serviceId = $serviceData->id;
        $I->waitAndClick(self::XPATH_DIGITAL_SERVICE_BLOCK.'['.$serviceNum.']//button[contains(@class,"add_to_cart")]', 'add service to cart');
        $I->waitForElementVisible(self::XPATH_DIGITAL_SERVICE_BLOCK.'['.$serviceNum.']//div[not(contains(@class,"not_display"))]/a');
        $serviceData = array(
            "name" => $serviceName,
            "price" => $serviceCost,
            "id" => $serviceId
        );

        return $serviceData;
    }
}
