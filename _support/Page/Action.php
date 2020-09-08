<?php
namespace Page;

class Action
{
    // include url of current page
    public static $URL = '/actions/';
    const DISCOUNT_ITEM = 'товар';
    const DISCOUNT_SERVICE = 'услуги';
    const DISCOUNT_KIT = 'комплект';
    const DISCOUNT_BONUS = 'Бонусы';
    const DISCOUNT_GIFT = 'Подарки';
    const DISCOUNT_B2B = 'юридических';
    const DISCOUNT_CONTEST = 'Конкурс';
    const DISCOUNT_OTHER = 'Другое';
    const DISCOUNT_INSTALLMENT = 'Рассрочка';
    const DISCOUNT_DISCOUNT_LABEL = 'Скидка';
    const DISCOUNT_GIFT_LABEL = 'Подарок';
    const XPATH_ACTION_ITEM_BLOCK = '//div[contains(@class,"action_items")]';

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
     * Выбор случайной акции с бонусами на странице акций.
     *
     * @throws \Exception
     */
    public function goToActionWithBonuses()
    {
        $I = $this->tester;

        $I->waitAndClick('//input[@value="bonus_filter"]', "type of action - with bonuses");
        $I->checkElementOnPage('//div[@data-filter-tag-promotion="bonus_filter"]', 'active action link');
        $I->selectSingleActionPage();
    }

    /**
     * Выбор случайной акции на странице акций.
     *
     * @throws \Exception
     */
    public function selectSingleActionPage()
    {
        $I = $this->tester;

        $actionsCount = $I->getNumberOfElements('//div[contains(@class,"PromotionCardsLayout__action") and not(contains(@style,"display: none;"))]');
        codecept_debug($actionsCount);
        $number = mt_rand(1, $actionsCount);
        $I->waitAndClick('//div[contains(@class,"PromotionCardsLayout__action") and not(contains(@style,"display: none;"))][' . $number . ']//a',
            "action link");
    }

    /**
     * Переход на случайную страницу акции по ее типу.
     *
     * @param string $action тип акции
     * @return int $searchActions искомые акции
     * @throws \Exception
    */
    public function goToRandomActionPageByType($action)
    {
        $I = $this->tester;
        $I->lookForwardTo("go to action page ");
        $searchActions = $I->getNumberOfElements('//div[@class="PromotionCard__badges"][contains(.,"'.$action.'")]');
        if ($searchActions != 0) {
            $num = mt_rand(1, $searchActions);
            $I->waitAndClick('//div[contains(@class,"PromotionCardsLayout__action")][contains(.,"комплект")][' . $num . ']//a',
                'select action');
        }

        return $searchActions;
    }

    /**
     * Добавление в корзину товара со страницы акции.
     *
     * @param int $number Номер товара на странице акции
     * @return int $productPrice цена товара на странице акции
     * @throws \Exception
     */
    public function addToCartFromAction($number)
    {
        $I = $this->tester;

        if (is_null($number)) {
            $I->lookForwardTo("add random product from actions page");
            $productQuantity = $I->getNumberOfElements(Home::XPATH_PRODUCT_CARD, "products at action page");
            $number = mt_rand(1, $productQuantity);
        }

        $I->lookForwardTo("add to cart product " . $number . " from tiles");
        $productPrice = $I->getNumberFromLink('//div[@class="GroupGrid__item"][' . $number . ']'. Home::XPATH_PRODUCT_CARD . '//span[@class="ProductCardVertical__price-current_current-price"]',
           'get current product price');
        $I->waitAndClick('//div[@class="GroupGrid__item"][' . $number . ']'. Home::XPATH_PRODUCT_CARD . '//div[contains(@class,"ProductCardVertical__button-buy")]',
            'add to cart product ' . $number);
        $I->continueShopping();

        return $productPrice;
    }

    /**
     * Добавление в корзину товара со страницы акции.
     *
     * @return int $productPrice цена товара на странице акции
     * @throws \Exception
     */
    public function addToCartFromPromo()
    {
        $I = $this->tester;

        $productClass = $I->grabAttributeFrom('//div[@class="beacon-card"]/div', 'class');
        $I->waitForElementVisible('//div[starts-with(@name,"citik-goods")]//div[@class="'.$productClass.'"]');
        $productsCount = $I->getNumberOfElements('//div[starts-with(@name,"citik-goods")]//div[@class="'.$productClass.'"]');
        $productNum = mt_rand(1, $productsCount);
        $productXpath = '//div[starts-with(@name,"citik-goods")]//div[@class="'.$productClass.'"]['.$productNum.']';
        $I->moveMouseOver($productXpath);
        $productPrice = $I->getNumberFromLink($productXpath.'//b[contains(.,"₽")]', 'get current product price');
        $I->waitAndClick($productXpath.'//button[contains(.,"В корзину")]', 'add to cart product ' . $productNum);
        $I->waitForElementVisible('//div[contains(.,"Товар добавлен в корзину")]');

        return $productPrice;
    }

    /**
     * Проверка адреса страницы акции promo\actions.
     *
     * @return string $type тип старницы акции promo\action
     * @throws \Exception
     */
    public function checkTypeOfActionPage()
    {
        $I = $this->tester;

        $url = $I->getFullUrl();
        if (stristr($url, 'promo')){
            $type = 'promo';
        }else{
            $type = 'action';
        }

        return $type;
    }

    /**
     * Переход на страницу товара со страницы акций.
     *
     * @param int $number Номер товара на странице акции
     * @throws \Exception
     */
    public function goToProductFromActionPage($number = null)
    {
        $I = $this->tester;

        if (is_null($number)) {
            $I->lookForwardTo("go to random product from actions page");
            $productQuantity = $I->getNumberOfElements(Home::XPATH_PRODUCT_CARD, "product items");
            $number = mt_rand(1, $productQuantity);
        }

        $I->lookForwardTo("go to product " . $number . " from actions page");
        $I->waitAndClick('//div[@class="GroupGrid__item"][' . $number . ']'. Home::XPATH_PRODUCT_CARD . '//div[@class="ProductCardVertical__name"]//a',
            'go to product');
    }

    /**
     * Получение промо кода из акции.
     *
     * @return string Промокод
     * @throws \Exception
     */
    public function getPromoCode()
    {
        $I = $this->tester;

        $I->lookForwardTo('grab promo code');
        $I->switchToNextTab();
        $promoCode = '';
        $code = $I->getNumberOfElements('//div[contains(.,"промокод")]/b');
        if ($code != 0) {
            $promoCode = $I->grabTextFrom('//div[contains(.,"промокод")]/b');
            $promoCode = trim($promoCode, '«»');
        }

        return $promoCode;
    }

    /**
     * Выбор фильтра акций.
     *
     * @param string $sort Типы фильтров, задаются константами из класса.
     * @throws \Exception
     */
    public function setSort($sort)
    {
        $I = $this->tester;

        $I->lookForwardTo('check '. $sort .' sort enable');
        $actionsFilterCount = $I->getNumberOfElements('//div[contains(@class,"FilterCategory") and contains(.,"Акции")]//label[not(contains(@class,"Checkbox_disabled"))]');
        codecept_debug('filter Count');
        codecept_debug($actionsFilterCount);
        $I->lookForwardTo('set sort by '. $sort .'');
        $I->waitAndClick('//div[contains(@class,"FilterCategory") and contains(.,"Акции")]//label[not(contains(@class,"Checkbox_disabled"))][contains(.,"'. $sort .'")]',
            $sort .' sort');
        $I->wait(SHORT_WAIT_TIME);
    }

    /**
     * Фильтрация товаров в акции по размеру скидки.
     *
     * @param int $percentFromDescription процент скидки из олписания акции
     * @return int или array $discount процент скидки или размер скидки (диапазон, если массив)
     * @throws \Exception
     */
    public function sortActionsItemsByDiscount($percentFromDescription)
    {
        $I = $this->tester;

        $I->lookForwardTo('sort items by discount');
        $discounts = $I->getNumberOfElements('//ul[contains(@class, "action_items_tab")]/li');

        if (empty($discounts)){
            $discount = $percentFromDescription;
            codecept_debug($discount);
        }else{
            $index = mt_rand(1,$discounts);
            $I->waitAndClick('//ul[contains(@class, "action_items_tab")]/li['.$index.']', 'select random discount');
            $discount = $I->getNumberFromLink('//ul[contains(@class, "action_items_tab")]/li['.$index.']', 'get discount');
            codecept_debug($discount);

            if (strlen($discount) > 2){
                $length = strlen($discount);
                $half = (int) ($length / 2);
                $startPercent = substr($discount,0, $half);
                $endPercent = substr($discount,$half);
                $discount = [$startPercent,$endPercent];
                codecept_debug($discount);
            }
        }
        codecept_debug($discount .'скидка со страницы акции');

        return $discount;
    }

    /**
     * Проверка фильтрации акций.
     *
     * @param string $filter тип акции
     *
     * @throws \Exception
     */
    public function checkActionsFilter($filter)
    {
        $I = $this->tester;

        $I->lookForwardTo('check actions filter');
        $labels = $I->grabMultiple('//div[contains(@class,"PromotionCardsLayout__action") and not(contains(@style,"display: none;"))]//div[@class="PromotionCard__badges"]');
        $sortLabels = array_diff($labels, array('', NULL, false));

        foreach ($sortLabels as $label) {
            if (stristr($label, $filter) == false) {
                Throw new \Exception('Filter by ' . $filter . ' does not work');
            }
        }
    }

    /**
     * Переход на промо страницу акции.
     *
     * @return int $promo наличе промо страницы
     * @throws \Exception
     */
    public function goToPromoPage()
    {
        $I = $this->tester;

        $promo = $I->getNumberOfElements('//a[@class="btn-promo"]');

        if ($promo != 0) {
            $promoUrl = $I->grabAttributeFrom('//a[@class="btn-promo"]', 'href');
            $I->waitAndClick('//a[@class="btn-promo"]', 'go to promo page');
            $I->switchToNextTab();
            $I->seeThatUrlsAreEquals($promoUrl, $I->getFullUrl(), 'promo url', 'current page url');
        }
        return $promo;
    }

    /**
     * Проверка наличия категорий на промо странице.
     *
     * @return int $categoriesCount количество категорий
     */
    public function checkCategoriesOnPromoPage()
    {
        $I = $this->tester;

        $I->wait(SHORT_WAIT_TIME);
        $categoriesCount = $I->getNumberOfElements('//div[starts-with(@name, "citik-goods-panel")]//div[@id]');
        codecept_debug($categoriesCount);

        return $categoriesCount;
    }

    /**
     * Выбор категории на промо странице.
     *
     * @param int $categoriesCount количество категорий
     * @return string $category выбранная категория
     * @throws \Exception
     */
    public function choosePromoCategory($categoriesCount)
    {
        $I = $this->tester;

        $num = (mt_rand(1,$categoriesCount));
        $I->waitAndClick('//div[. = "Категории"]', 'open category list');
        $I->waitForElementVisible('//div[starts-with(@name, "citik-goods-panel")]//div[@id]['.$num.']');
        $category = $I->grabTextFrom('//div[starts-with(@name, "citik-goods-panel")]//div[@id]['.$num.']');
        codecept_debug($category.' category name');
        $I->waitAndClick('//div[starts-with(@name, "citik-goods-panel")]//div[@id]['.$num.']', 'select category');
        $I->waitForElementVisible('//div[starts-with(@name, "citik-goods-panel")]//button[text()]');

        return $category;
    }

    /**
     * Выбор фильтра промокоды.
     *
     * @throws \Exception
     */
    public function setPromoCodeFilter()
    {
        $I = $this->tester;

        $I->waitAndClick('//a[@class="actions-breadcrumb__link"]', 'select filter by promocode');
        $I->waitForElementVisible('//li[@class="actions-breadcrumb__link-box selected"]');
    }

    /**
     * Добавление комплекта товаров в корзину.
     *
     * @throws \Exception
     */
    public function addItemsSetToCart()
    {
        $I = $this->tester;

        $lines = $I->getNumberOfElements(['class' => 'goods__line']);
        $line = mt_rand(1, $lines);
        $sets = $I->getNumberOfElements('//div[@class="goods__line"]['.$line.']//div[@onclick]');
        $set = mt_rand(1, $sets);
        $setsData = $I->grabAttributeFrom('//div[@class="goods__line"]['.$line.']//div[@onclick]['.$set.']', 'onclick');
        $data = json_decode($setsData, TRUE);
        codecept_debug('array JSON');
        codecept_debug($data);
        $ids = $data["ids"];
        $I->waitAndClick('//div[@class="goods__line"]['.$line.']//div[@onclick]['.$set.']',
            'add kit to cart');

        return $ids;
    }
}
