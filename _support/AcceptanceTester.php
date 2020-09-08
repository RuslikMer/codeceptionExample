<?php

use Page\Feedback as Feedback;
use Page\About as About;
use Page\Supplies as Supplies;
use Page\Cart as CartPage;
use Page\CheckOut as CheckOutPage;
use Page\Home as HomePage;
use Page\Login as LoginPage;
use Page\Menu as MenuPage;
use Page\Search as SearchPage;
use Page\Listing as ListingPage;
use Page\Action as ActionPage;
use Page\Product as ProductPage;
use Page\Catalog as CatalogPage;
use Page\Profile as ProfilePage;
use Page\NotFound as NotFound;
use Page\Brands as Brands;
use Page\Compare as Compare;
use Page\Reviews as Reviews;
use Page\Config as ConfigPage;
use Page\Forum as Forum;
use Page\Used as UsedPage;
use Page\Amp as AmpPage;
use Page\Registration as Registration;
use Page\B2B as B2B;
use \Facebook\WebDriver\WebDriverElement;
use Codeception\Util\Locator;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */

class AcceptanceTester extends \Codeception\Actor {

    use _generated\AcceptanceTesterActions;

     /**
     * Продолжить покупки для всплывающей корзины.
     *
     * @throws Exception
     */
    public function continueShopping()
    {
        $I = $this;
        $fileName = __FILE__;
        codecept_debug($fileName);
        codecept_debug(!stristr(__FILE__, 'b2b'));

        $I->wait(16);
        if (!stristr(__FILE__, 'b2b')) {
            $I->waitForElementVisible('//section[@class="UpsaleBasket js--UpsaleBasket"]');
            $I->waitAndClick('//div[contains(@class,"Popup__show-popup")]//button[@class="Popup__close"]/*[@class="Icon"]', 'continue Shopping', true);
        }
    }

    /**
     * Получение текущего домена
     */
    public function getCurrentUrlJS()
    {
        return $this->executeJS("return location.href");
    }

    /**
     * Получение наименования случайного серого города.
     *
     * @return mixed Наименование города
     * @throws \Exception
     */
    public function getGrayCity()
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->getGrayCity();
    }

    /**
     * Получение списка городов со страницы выбора городов.
     *
     * @throws \Exception
     */
    public function checkDuplicateCities()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->checkDuplicateCities();
    }

    /**
     * Открыть список городов.
     *
     * @throws \Exception
     */
    public function openCitiesList()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->openCitiesList();
    }

    /**
     * Проверка инпута поиска города на emoji.
     *
     * @param string Emoji
     * @throws \Exception
     */
    public function checkCitySearchByEmoji($param)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->checkCitySearchByEmoji($param);
    }

    /**
     * Выбор серого города из списка.
     *
     * @throws \Exception
     */
    public function changeCityToGrayRandom()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->changeCityToGrayRandom();
    }

    /**
     * Определение окружения - продакшн сайт или тестовый контейнер.
     *
     * @param $scenario
     * @return bool
     * @throws \Exception
     */
    public function isProduction($scenario)
    {
        $I = $this;

        if ( $I->getFullUrl() == 'https://www.citilink.ru/' ) {
            return true; }
        else
        {
            return false;
            $I->setCookie('testing','1', array('domain' => '.citik.ru'));
        }
    }

    /**
     * Добавление товара в корзину со страницы Сравнения.
     *
     * @param int $param номер товара на странице сравнения
     * @throws \Exception
     */
    public function addToCartFromCompare($param)
    {
        $I = $this;

        $compare = new Compare($I);
        $compare->addToCartFromCompare($param);
    }

    /**
     * Переход в сравнение из Листинга
     *
     * @throws \Exception
     */
    public function goToCompareProductLink()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->goToCompareProductLink();
    }

    /**
     * Добавление в сравнение из Листинга.
     *
     * @param int $param номер товара в листинге для добавления
     * @throws \Exception
     */
    public function addToCompareFromListing($param)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->addToCompareFromListing($param);
    }

    /**
     * Проверка что список сравнения пуст.
     *
     * @throws \Exception
     */
    public function checkCompareListIsEmpty()
    {
        $I = $this;

        $compare = new Compare($I);
        $compare->checkCompareListIsEmpty();
    }

    /**
     * Добавление в сравнение из КТ.
     *
     * @throws \Exception
     */
    public function addToCompareFromProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->addToCompare();
    }

    /**
     * Удаление из сравнения из КТ.
     *
     * @throws \Exception
     */
    public function deleteFromCompareFromProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->deleteFromCompare();
    }

    /**
     * Переход в список сравнения из КТ.
     *
     * @throws \Exception
     */
    public function goToCompareFromProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->goToCompare();
    }

    /**
     * Переход в категорию со страницы поиска.
     *
     * @param string $category - название категории
     * @throws \Exception
     */
    public function goToCategoryFromSearch($category)
    {
        $I = $this;

        $searchPage = new SearchPage($I);
        $searchPage->goToCategoryFromSearch($category);
    }

    /**
     * Выбор случайной акции с бонусами на странице акций.
     *
     * @throws \Exception
     */
    public function goToActionWithBonuses()
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        $actionPage->goToActionWithBonuses();
    }

    /**
     * Выбор случайной акции на странице акций.
     *
     * @throws \Exception
     */
    public function selectSingleActionPage()
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        $actionPage->selectSingleActionPage();
    }

    /**
     * Переход на страницу товара со страницы акций.
     *
     * @param int $number Номер товара на странице акции
     * @throws \Exception
     */
    public function goToProductFromActionPage($number = null)
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        $actionPage->goToProductFromActionPage($number);
    }

    /**
     * Переход в КТ по клику на товар в Листинге.
     *
     * @param int $sectionNum Порядковый номер секции в листинге
     * @param int $itemNum Порядковый номер товара в секции листинга
     * @param string $info переход в КТ с отзывом, обзором или рейтингом
     * @return string $productName
     * @throws \Exception
     */
    public function goToProductFromListing($sectionNum = null, $itemNum = null, $info = null)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->goToProductFromListing($sectionNum, $itemNum, $info, $listingPage::PRODUCT_TITLE);
    }

    /**
     * Переход в КТ по клику на картинку товара в Листинге.
     *
     * @param int $sectionNum Порядковый номер секции в листинге
     * @param int $itemNum Порядковый номер товара в секции листинга
     * @param string $info переход в КТ отзывом, обзором или рейтингом
     * @return string $productName
     * @throws \Exception
     */
    public function goToProductFromListingImage($sectionNum = null, $itemNum = null, $info = null)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->goToProductFromListing($sectionNum, $itemNum, $info, $listingPage::PRODUCT_IMAGE);
    }

    /**
     * Переход в КТ по клику на товар в листинге по айди товра.
     *
     * @param string $itemId айди товара в листинге
     * @return string $productName название товара
     * @throws \Exception
     */
    public function goToProductFromListingById($itemId = null)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->goToProductFromListingById($itemId);
    }

    /**
     * Ввод строки в поле поиска.
     *
     * @param string $param Строка для поиска
     * @throws \Exception
     */
    public function searchString($param)
    {
        $I = $this;

        $searchPage = new SearchPage($I);
        $searchPage->searchString($param);
    }

    /**
     * Переход в корзину через попап - в листинге.
     *
     * @throws \Exception
     */
    public function goToCartFromListingPopup()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->goToCartFromPopup();
    }

    /**
     * Переход в корзину по изменяющейся кнопке "в корзину".
     *
     * @param int $itemId
     * @throws \Exception
     */
    public function goToCartByButtonFromListing($itemId)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->goToCartByButton($itemId);
    }

    /**
     * Удаление всех товаров из корзины.
     *
     * @param string $param номер позиции товара
     * @throws \Exception
     */
    public function deleteItemFromCart($param = null)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        if (empty($param)) {
            $cartPage->deleteAll();
        } else {
            $cartPage->deleteItem($param);
        }
    }

    /**
     * Заполнение строки поиска и эмуляция нажатия Enter с клавиатуры.
     *
     * @param string $param Поисковый запрос
     * @throws \Exception
     */
    public function searchStringByEnter($param)
    {
        $I = $this;

        $searchPage = new SearchPage($I);
        $searchPage->searchStringByEnter($param);
    }

    /**
     * Проверка корректного исправления опечаток поискового запроса.
     *
     * @param string $param ожидаемое исправление
     * @throws \Exception
     */
    public function checkTypoCorrection($param)
    {
        $I = $this;

        $searchPage = new SearchPage($I);
        $searchPage->checkTypoCorrection($param);
    }

    /**
     * Проверка быстрого поиска.
     *
     * @param string $param Поисковый запрос
     */
    public function searchFastString($param)
    {
        $I = $this;

        $searchPage = new SearchPage($I);
        $searchPage->searchFastString($param);
    }

    /**
     * Заполнение строки поиска и переход на страницу "Показать все результаты".
     *
     * @param string $param Поисковый запрос
     * @throws \Exception
     */
    public function searchStringAllResults($param)
    {
        $I = $this;

        $searchPage = new SearchPage($I);
        $searchPage->searchStringAllResults($param);
    }

    /**
     * Добавить в корзину из списка.
     *
     * @param int $sectionNum Порядковый номер секции в листинге
     * @param int $itemNum Порядковый номер товара в секции листинга
     * @param string $viewType тип отображения листинга
     * @param string $buyButton путь к кнопке "в корзину"
     * @return string $itemId Id товара
     * @throws \Exception
     */
    public function addToCartFromListing($sectionNum = null, $itemNum = null, $viewType = \Page\Listing::VIEW_GRID, $buyButton = \Page\Listing::GRID_BUYBUTTON)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->addToCart($sectionNum, $itemNum, $viewType, $buyButton);
    }

    /**
     * waitAndClick с параметром для ручного выбора времени ожидания.
     *
     * @param string $link Xpath элемента
     * @param int $waitSec Время ожидания
     * @param string $link_description Описание
     * @throws \Exception
     */
    public function waitSecAndClick($link, $waitSec, $link_description = null)
    {
        $I = $this;

        if (!is_null($link_description)) {
            $I->lookForwardTo("trying to click " . $link_description);
        }
        $I->waitForElement($link, $waitSec);
        $I->click($link);
    }

    /**
     * Заполнение поля ввода.
     *
     * @param mixed $link Поле ввода
     * @param string $description Описание поля
     * @param string $data Данные для ввода
     * @param null $noScroll Отключение скрола
     * @throws \Exception
     */
    public function waitAndFill($link, $description, $data, $noScroll = null)
    {
        $I = $this;

        $I->lookForwardTo("fill " . $description . " with " . $data);

        $I->waitAndClick($link, $description, $noScroll);
        $I->clearFieldWithAttribute($link);
        $I->fillField($link, $data);
        $I->wait(SHORT_WAIT_TIME);
    }

    /**
     * Переход на страницу товара с главной, товар может быть указан числом или выбран случайным образом.
     *
     * @param int $categoryNumber Номер рекомендуемой категории товаров
     * @param int $productNumber Номер товара в категории по порядку
     * @throws \Exception
     */
    public function goToProductFromMainPage($categoryNumber = null, $productNumber = null)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToProductFromMainPage($categoryNumber, $productNumber);
    }

    /**
     * Проверка загрузки картинки капчи в попапе.
     *
     * @throws \Exception
     */
    public function checkCaptchaInPopUp()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->checkCaptcha('//div[contains(@class,"ps--active-y")]');
    }

    /**
     * Проверка загрузки картинки капчи на странице восстановления пароля.
     *
     * @throws \Exception
     */
    public function checkCaptchaOnResetPage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->checkCaptcha('//form[@id="reset-by-email-form"]');
    }

    /**
     * Очищаем корзину и выставляем Москву, если требуется.
     *
     * @throws \Exception
     */
    public function doCleanUp()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->doCleanUp();
    }

    /**
     * очищаем список желаний.
     *
     * @throws \Exception
     */
    public function wishListCleanUp()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->wishListCleanUp();
    }

    /**
     * Переход на главную по мини лого.
     *
     * @throws \Exception
     */
    public function goToHomePageBySmallLogo()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToHomePageBySmallLogo();
    }

    /**
     * очищаем корзину, если требуется.
     *
     * @throws \Exception
     */
    public function cartCleanUp()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->cartCleanUp();
    }

    /**
     * Добавление товара в корзину - кнопками в правом блоке.
     *
     * @param bool $close закрыть всплывающую корзину
     * @throws \Exception
     */
    public function addToCartFromProductPage($close = true)
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->addToCartFromProductPage($close);
    }

    /**
     * Добавление товара в корзину - кнопками в правом блоке (для b2b).
     *
     * @param bool $close закрыть всплывающую корзину
     * @throws \Exception
     */
    public function addToCartFromProductPageB2b($close = true)
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->addToCartFromProductPageB2b($close);
    }

    /**
     * Добавление в корзину из каталога (плитки).
     *
     * @param int $number Номер добавляемого товара
     * @param int $categoryNumber подкатегории добавляемого товара сверху вниз.
     * @throws Exception Не увеличилась сумма товаров в корзине
     */
    public function addToCartFromCatalog($number = null, $categoryNumber = null)
    {
        //функционал добавления в корзину отсутствует
    }

    /**
     * Выбор из основного меню категорий.
     *
     * @param int $param
     * @throws \Exception
     */
    public function clickMainMenuCategory($param = null)
    {
        $I = $this;

        if (is_null($param)) {
            $param = mt_rand(1, 10);
        }

        $menuPage = new MenuPage($I);
        $menuPage->selectMainCategory($param);
    }

    /**
     * Раскрытие категории меню.
     *
     * @param int $param
     * @throws \Exception
     */
    public function openMenuCategory($param = null)
    {
        $I = $this;

        if (is_null($param)) {
            $param = mt_rand(1, 11);
        }

        $menuPage = new MenuPage($I);
        $menuPage->openMenuCategory($param);
    }

    /**
     * Выбор доставки день в день.
     *
     * @throws \Exception
     */
    public function chooseSameDayDelivery()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->chooseSameDayDelivery();
    }

    /**
     * Выбор курьерской доставки.
     *
     * @return int $notAvailableDelivery невозможность доставки
     * @throws \Exception
     */
    public function chooseCourierDelivery()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->chooseCourierDelivery();
    }

    /**
     * Выбор типа доставки - Самовывоз.
     *
     * @throws \Exception
     */
    public function chooseSelfDelivery()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->chooseSelfDelivery();
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
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->fillContactsForCourierDelivery($name, $familyName, $phone, $street, $house);
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
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->fillContactsForCourierKladrDelivery($name, $familyName, $phone, $house);
    }

    /**
     * Заполнение контактных данных при курьерской доставке в офисы Мерлион
     *
     * @param string $name
     * @param string $familyName
     * @param string $phone
     * @param string $officeCode
     * @throws \Exception
     */
    public function fillContactsForCourierDeliveryMerlion($name, $familyName, $phone, $officeCode)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->fillContactsForCourierDeliveryMerlion($name, $familyName, $phone, $officeCode);
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
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->fillContactsForDelivery($name, $familyName, $phone, $street, $house);
    }

    /**
     * Переход в Корзину по ссылке сверху справа (виджет корзины).
     *
     * @throws \Exception
     */
    public function goToCartFromProductButton()
    {
        $this->goToCartFromTopLink();
    }

    /**
     * Подтверждение заказа для авторизованного пользователя и отмена созданного заказа.
     *
     * @param string $paymentType Тип оплаты подтверждаемого заказа
     * @throws \Exception
     */
    public function orderConfirm($paymentType = PAY_CASH)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->orderConfirm($paymentType);
    }

    /**
     * Отмена созданного заказа для авторизованного B2B пользователя.
     *
     * @throws \Exception
     */
    public function cancelOrderB2B()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->cancelOrderB2B();
    }

    /**
     * Переход на подтверждение заказа с отменой СМС и комментарием.
     *
     * @throws \Exception
     */
    public function goFromPaymentToConfirmation()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goFromPaymentToConfirmation();
    }

    /**
     * Переход на подтверждение заказа.
     *
     * @throws \Exception
     */
    public function goFromPaymentToConfirmationWithSMS()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goFromPaymentToConfirmationWithSMS();
    }

    /**
     * Заполнение данных при самовывозе.
     *
     * @param $name string имя пользователя
     * @param $familyName string фамилия пользователя
     * @param $phone string телефон пользователя
     * @return array $contacts
     * @throws \Exception
     */
    public function fillContactsForSelfDelivery($name, $familyName, $phone)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->fillContactsForSelfDelivery($name, $familyName, $phone);
    }

    /**
     * Переход в Корзину по ссылке сверху справа (виджет корзины).
     *
     * @throws \Exception
     */
    public function goToCartFromTopLink()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goToCartFromTopLink();
    }

    /**
     * Добавление в корзину со страницы акции.
     *
     * @param int $number
     * @return int $productPrice цена товара на странице акции
     * @throws \Exception
     */
    public function addToCartFromAction($number = null)
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        return $actionPage->addToCartFromAction($number);
    }

    /**
     * Добавление в корзину товара со страницы акции.
     *
     * @return int $productPrice цена товара на странице акции
     * @throws \Exception
     */
    public function addToCartFromPromo()
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        return $actionPage->addToCartFromPromo();
    }

    /**
     * Получить тип страницы акции promo\actions.
     *
     * @return string $type тип старницы акции promo\action
     * @throws \Exception
     */
    public function getTypeOfActionPage()
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        return $actionPage->checkTypeOfActionPage();
    }

    /**
     * Добавление в корзину товаров с главной страницы (плитки).
     *
     * @param int $number Номер товара на странице
     * @return string Id товара
     * @throws \Exception
     */
    public function addToCartFromTile($number)
    {
        $this->goToProductFromMainPage($number);
        $this->addToCartFromProductPage();
        $itemId = $this->getItemIdFromProductPage();
        $this->goToHomePageBySmallLogo();

        return $itemId;
    }

    /**
     * Добавление в корзину товаров с главной страницы (плитки) для b2b.
     *
     * @param int $number Номер товара на странице
     * @return string Id товара
     * @throws \Exception
     */
    public function addToCartFromTileB2b($number)
    {
        $this->goToProductFromMainPage($number);
        $this->addToCartFromProductPageB2b();
        $itemId = $this->getItemIdFromProductPage();
        $this->goToHomePageBySmallLogo();

        return $itemId;
    }

    /**
     * Добавление в корзину товаров с главной страницы (плитки).
     *
     * @param int $categoryNumber Номер категории
     * @param int $productNumber Номер товара в категории
     * @param $link string class блока на главной
     * @return string Id товара
     * @throws \Exception
     */
    public function addToCartFromTileXpath($link, $categoryNumber, $productNumber)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->addToCart($link, $categoryNumber, $productNumber);
    }

    /**
     * Переход на домашнюю страницу сайта.
     *
     * @throws \Exception
     */
    public function openHomePage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->open();
    }

    /**
     * Проверка обязательных элементов на страницах сайта.
     *
     * @throws \Exception
     */
    public function checkMandatoryElements()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->checkMandatoryElements();
    }

    /**
     * Проверка обязательных элементов на страницах AMP сайта.
     *
     * @throws \Exception
     */
    public function checkAmpMandatoryElements()
    {
        $I = $this;

        $ampPage = new AmpPage($I);
        $ampPage->checkMandatoryElements();
    }

    /**
     * Проверка главной страницы сайта.
     *
     * @throws \Exception
     */
    public function checkHomePage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->checkHomePage();
    }

    /**
     * Получение числа из элемента, заданного ссылкой и описанием.
     *
     * @param mixed $link
     * @param string $linkDescription
     * @return string
     */
    public function getNumberFromLink($link, $linkDescription)
    {
        $I = $this;

        if (!is_null($linkDescription)) {
            $I->lookForwardTo("get number from " . $linkDescription);
        }
        $numberFromLink = preg_replace("/[^0-9]/", "", $I->grabTextFrom($link));

        if (empty($numberFromLink) ) {
            $numberFromLink = 0;
        }
        codecept_debug("the number from link is " . $numberFromLink);
        return $numberFromLink;
    }

    /**
     * Получение текущей суммы товаров в Корзине (указывается на каждой странице сверху справа).
     *
     * @return int Сумма корзины
     */
    public function getCurrentCartAmountInitial()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->getTopRightAmountInitial();
    }

    /**
     * Получение текущей суммы товаров в Корзине при оформлении заказа (из черного блока).
     *
     * @return int $cartSum сумма корзины
     * @throws \Exception
     */
    public function getCartAmountBlackBox()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->getCartAmountBlackBox();
    }

    /**
     * Получение текущей суммы товаров в Корзине при оформлении заказа без учета доставки (из черного блока).
     *
     * @return int
     */
    public function getCartAmountBlackBoxWithOutDelivery()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->getCartAmountWithOutDelivery();
    }

    /**
     * Возвращает массив с логином и паролем для авторизации на сайте.
     *
     * @return array ['username' => '', 'password' => '']
     */
    public function getUser()
    {
        return [
            'username' => $this->grabFromConfig('username'),
            'password' => $this->grabFromConfig('password'),
        ];
    }

    /**
     * Возвращает массив с логином и паролем для авторизации на сайте.
     *
     * @param string $userType Тип пользователя
     *
     * @return array ['username' => '', 'password' => '']
     */
    public function getUserByType($userType = '')
    {
        $constantName = 'USERNAME';
        if (defined($constantName) && defined('PASSWORD')) {
            if (!empty($userType)) {
                $constantName .= '_' . $userType;
            }
            return [
                'username' => constant($constantName),
                'password' => PASSWORD,
            ];
        }

        return $this->getUser();
    }

    /**
     * B2B логин.
     *
     * @param string $username Логин пользователя
     * @param string $password Пароль пользователя
     * @throws \Exception
     */
    public function doLoginB2B($username = '', $password = '')
    {
        $I = $this;

        if (empty($username) && empty($password)) {
            $user = $this->getUserByType('B2B');
            $username = $user['username'];
            $password = $user['password'];
        }

        $loginPage = new LoginPage($I);
        $loginPage->doLoginTopRightB2B($username, $password);
    }

    /**
     * Авторизация через форму сверху справа.
     *
     * @param bool $confirm пропуск закрытия окна подтверждения номера
     * @param string $username Логин пользователя
     * @param string $password Пароль пользователя
     * @throws \Exception
     */
    public function doLogin($username = '', $password = '', $confirm = true)
    {
        $I = $this;

        if (empty($username) && empty($password)) {
            $user = $this->getUserByType();
            $username = $user['username'];
            $password = $user['password'];
        }

        $loginPage = new LoginPage($I);
        $loginPage->doLoginTopRight($username, $password);
        if ($confirm == true) {
            $loginPage->closeConfirmPhoneWindow();
        }

        $loginPage->checkLoginDropdown();
    }

    /**
     * Логаут пользователя через форму сверху справа.
     *
     * @throws \Exception
     */
    public function doLogout()
    {
        $I = $this;

        $loginPage = new LoginPage($I);
        $loginPage->doLogout();
    }

    /**
     * Раскрытие окна регистрации/авторизации
     *
     * @throws \Exception
     */
    public function openLoginRegistrationWindow()
    {
        $I = $this;

        $loginPage = new LoginPage($I);
        $loginPage->openLoginRegistrationWindow();
    }

    /**
     * Скрытие окна с подтверждением номера телефона.
     *
     * @throws \Exception
     */
    public function closeConfirmPhoneWindow()
    {
        $I = $this;

        $loginPage = new LoginPage($I);
        $loginPage->closeConfirmPhoneWindow();
    }

    /**
     * метод клика на элементе страницы, включает ожидание элемента и вывод его описания.
     *
     * @param mixed $link ссылка на элемент
     * @param string $linkDescription описание элемента
     * @param string $noScroll отключение скроллирования к элементу
     * @throws \Exception
     */
    public function waitAndClick($link, $linkDescription, $noScroll = null)
    {
        $I = $this;


        $I->lookForwardTo("trying to click " . $linkDescription);
        //если переменная не определена, то прокручиваем страницу к элементу

        try {
            $I->waitForElementVisible($link, ELEMENT_WAIT_TIME);
        } catch (\Exception $ex) {
            Throw new \Exception('Cannot wait for ' . $linkDescription . ' within ' . ELEMENT_WAIT_TIME . ' seconds');
        }

        if ( $I->getNumberOfElements($link, $linkDescription) == 0 ){
            Throw new \Exception('There is no element ' . $linkDescription . ' at the page');
        };

        if (is_null($noScroll)) {
            try {
                //$location = $I->getElementLocation($link);
                //$y = array_pop($location)/2;
                //codecept_debug($y);
                $I->scrollTo($link, null, -100);
            } catch ( \Exception $ex) {
                Throw new \Exception('Cannot scroll to ' . $linkDescription);
            }
        }

        $I->click($link);
    }

    /**
     * проверка наличия на странице указанного элемента.
     *
     * @param mixed $link ссылка на элемент
     * @param string $linkDescription Описание элемента
     * @throws \Exception
     */
    public function checkElementOnPage($link, $linkDescription = null)
    {
        $I = $this;

        if (!is_null($linkDescription)) {
            $I->lookForwardTo("check that " . $linkDescription . " is visible on page");
        }

        try {
            $I->seeElement($link);
        } catch (\Exception $ex) {
            Throw new \Exception('Cannot see ' . $linkDescription);
        }
    }

    /**
     * Получение количества элементов, определяемых ссылкой и описанием.
     *
     * @param mixed $link ссылка на элементы
     * @param string $linkDescription описание элементов (не обязательно)
     * @return int количество найденных элементов
     */
    public function getNumberOfElements($link, $linkDescription = null)
    {
        $I = $this;

        if (!is_null($linkDescription)) {
            $I->lookForwardTo("get number of " . $linkDescription . " from page");
        }

        $elementsArray = $I->grabMultiple($link);
        codecept_debug("grabbed array");
        codecept_debug($elementsArray);
        $numberOfElements = count($elementsArray);
        codecept_debug('Number of ' . $linkDescription . ' is ' . $numberOfElements);

        return $numberOfElements;
    }

    /**
     * Переход к списку с главной страницы.
     *
     * @param int $mainCat Порядковый номер категории, счет сверху вниз
     * @param int $subCat Порядковый номер подкатегории, счет сверху вниз
     * @param int $lowCat Порядковый номер подкатегории, счет сверху вниз
     * @return string $selectedCategoryName название конечной категории
     * @throws \Exception
     */
    public function goToListing($mainCat = null, $subCat = null, $lowCat = null)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->goToListing($mainCat, $subCat, $lowCat);
    }

    /**
     * Переход к списку с главной страницы, добавление товара из списка и переход по изменяемой кнопке в корзину.
     *
     * @param int $param Порядковый номер категории, счет сверху вниз
     * @param int $subCat Порядковый номер подкатегории, счет сверху вниз
     * @return string $itemId
     * @throws \Exception
     */
    public function goToListAddToCart($param = null, $subCat = null)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->goToListAddToCart($param, $subCat);
    }

    /**
     * Проверка блока подъема на этаж КБТ при курьерской доставке. По-умолчанию, без параметров, проверяется дефолтное значение.
     * Можно проверить выбор ручного подъема и на лифте
     *
     * @param int $level Номер этажа
     * @param string $lift Признак наличия лифта, любое не пустое означает проверку выбора лифта
     * @throws \Exception
     */
    public function checkEnableLift($level = null, $lift = null)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkEnableLift($level, $lift);
    }

    /**
     * Выбор чекбокса "Подъем на этаж" и ввод значения в поле "Этаж" - Ручной подъем.
     *
     * @param int $param Номер этажа
     * @throws \Exception
     */
    public function liftUptoStage($param)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->liftUptoStage($param);
    }

    /**
     * Выбор чекбокса "Подъем на этаж", ввод значения в поле "Этаж", выбор чекбокса "Подъем на лифте".
     *
     * @param int $param Номер этажа
     * @throws \Exception
     */
    public function liftUptoStageElevator($param)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->liftUptoStageElevator($param);
    }

    /**
     * Сортировка товаров в листинге по возрастанию.
     *
     * @param string $sortType
     * @throws \Exception
     */
    public function sortProductBy($sortType)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->sortProductBy($sortType);
    }

    /**
     * Сортировка товаров в листинге по убыванию.
     *
     * @param string $sortType
     * @throws \Exception
     */
    public function sortProductByDesc($sortType)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->sortProductByDesc($sortType);
    }

    /**
     * Добавление элемента и переход в корзину из списка-таблицы.
     *
     * @return string $itemId Id товара
     * @throws \Exception
     */
    public function addAndGoToCartFromListing()
    {

        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->addAndGoToCartFromListing();
    }

    /**
     * Проверка услуги(количество услуг) подъема на этаж при подтверждении заказа.
     *
     * @param int $param Номер этажа
     * @throws \Exception Выбранный номер не совпадает с количеством услуг при подтверждении
     */
    public function checkLiftUpToStage($param)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkLiftUpToStage($param);
    }

    /**
     * Проверка услуги(одной) подъема на лифте.
     *
     * @throws \Exception Добавлено не одна услуга
     */
    public function checkLiftUpToStageElevator()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkLiftUpToStageElevator();
    }

    /**
     * Выбор типа оплаты.
     *
     * @param int $param Номер элемента по порядку, используются константы - PAY_CASH = 1;
     * PAY_CARD = 2;
     * PAY_CREDIT = 3;
     * PAY_YANDEX = 4;
     * PAY_TERMINAL = 5;
     * PAY_WEB = 6;
     * PAY_ORG = 7;
     * @throws \Exception
     */
    public function paymentVia($param)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->paymentVia($param);
    }

    /**
     * Проверка всех ограничений типов оплаты для НЕавторизованного пользователя.
     *
     * @throws \Exception В случае не ожидаемой доступности типов оплат
     */
    public function checkAllPayLimitNonAuth()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkAllPayLimitNonAuth();
    }

    /**
     * Проверка всех ограничений типов оплаты для Авторизованного пользователя.
     *
     * @param bool $installService наличие услуги сборки true/false
     * @throws \Exception В случае не ожидаемой доступности типов оплат
     */
    public function checkAllPayLimitAuth($installService = false)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkAllPayLimitAuth($installService);
    }

    /**
     * Выбор типа оплаты.
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
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->choosePayment($paymentToChoose);
    }

    /**
     * Выбор точки самовывоза.
     *
     * @param bool $changePickPoint
     * @return string выбранный пункт самовывоза
     * @throws \Exception
     */
    public function useSelfDelivery($changePickPoint = false)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->useSelfDelivery($changePickPoint);
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
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->setItemValue($itemNumber, $value);
    }

    /**
     * Переход в корзину из карточки товара по большой изменяемой кнопке Добавить в корзину -> Оформить заказ.
     *
     * @throws \Exception
     */
    public function goToCartFromProductBigButton()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->goToCartFromProductBigButton();
    }

    /**
     * Переход в корзину из карточки товара по кнопке "в корзину".
     *
     * @throws \Exception
     */
    public function goToCartFromProductSmallButton()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->goToCartFromProductSmallButton();
    }

    /**
     * Переход к списку с главной страницы, добавление товара из списка.
     *
     * @param int $param Порядковый номер категории, счет сверху вниз
     * @param int $subCat Порядковый номер подкатегории, счет сверху вниз
     * @throws \Exception
     */
    public function goToListAddToCartOnly($param, $subCat = null)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->goToListAddToCartOnly($param, $subCat);
    }

    /**
     * Получение рейтинга продукта с главной страницы.
     *
     * @param int $number Номер продукта на странице по порядку
     * @return string Рейтинг в условном формате (0,1,2,3,4,5) -> (5,4,3,2,1,0)
     */
    public function getRateFromHomePage($number)
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->getRateFromHomePage($number);
    }

    /**
     * Проверка страницы товара.
     *
     * @throws \Exception
     */
    public function checkExtraBonusProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->checkExtraBonusCard();
    }

    /**
     * Получение рейтинга товара с карточки товара
     *
     * @return string Рейтинг в условном формате (0,1,2,3,4,5) -> (5,4,3,2,1,0)
     */
    public function getRateFromProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->getRateFromProductPage();
    }

    /**
     * Получение рейтинга ПЕРВОГО товара из списка.
     *
     * @return string Рейтинг в условном формате (0,1,2,3,4,5) -> (5,4,3,2,1,0)
     */
    public function getFirstRateFromListingPage()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->getFirstRateFromListingPage();
    }

    /**
     * Проверка сортировки по цене
     *
     * @throws \Exception В случае если сортировка на сайте не совпала с сортировкой в пхп
     */
    public function checkSortByPrice()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkSortByPrice();
    }

    /**
     * Проверка сортировки по наименованию, кликает на сортировку по наименованию,
     * собирает массив наименований со страницы, сортирует массив.
     *
     * @throws \Exception В случае если сортировка на сайте не совпала с сортировкой в пхп
     */
    public function checkSortByName()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkSortByName();
    }

    /**
     * Проверка сортировки по количеству обзоров, кликает на сортировку по обзорам,
     * собирает массив количества обзоров по товарам со страницы, сортирует массив.
     *
     * @throws \Exception В случае если отличается порядок результата сортировок
     */
    public function checkSortByReview()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkSortByReview();
    }

    /**
     * Проверка сортировки по количеству отзывов, кликает на сортировку по отзывам,
     * собирает массив количества отзывов по товарам со страницы, сортирует массив.
     *
     * @throws \Exception В случае если отличается порядок результата сортировок
     */
    public function checkSortByReport()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkSortByReport();
    }

    /**
     * Сравнение наименования Категории с наименованием в хлебных крошках.
     *
     * @throws \Exception В случае если наименования не совпадают
     */
    public function checkCategoryName()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkCategoryName();
    }

    /**
     * Проверка фильтров "Показать товар".
     *
     * @throws \Exception В случае отсутствия товаров соответствующих текущему фильтру
     */
    public function checkFilterShowItem()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkFilter($listingPage::SHOW_ITEM);
    }

    /**
     * Проверка фильтров "Статус".
     *
     * @throws \Exception
     */
    public function checkFilterStatus()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkFilter($listingPage::STATUS);
    }

    /**
     * Проверка фильтра по цене
     *
     * @throws \Exception Если первый товар в списке до и после фильтрации совпадает по цене
     */
    public function checkFilterPrice()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkFilterPrice();
    }

    /**
     * Проверка фильтра по Брендам.
     *
     * @param int $brandsQuantity количество брендов для выбора
     * @throws \Exception Если в листинге присутствуют объекты, не соответствующие фильтру
     */
    public function checkFilterBrand($brandsQuantity = 1)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkFilterBrand($brandsQuantity);
    }

    /**
     * Проверка всех главных баннеров.
     *
     * @throws \Exception Если не выбран переключатель, если баннер не выбран по переключателю
     */
    public function checkBanner()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->checkBanner();
    }

    /**
     * Смена города.
     *
     * @param string $param Наименование города
     * @throws \Exception
     */
    public function changeCity($param = null)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->changeCity($param);
    }

    /**
     * Проверка отображения списка городов в алфавитном порядке.
     *
     * @param null $arrayCity Список городов
     * @throws \Exception
     */
    public function sortCity($arrayCity = null)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->sortCity($arrayCity);
    }

    /**
     * Получение списка желтых городов.
     *
     * @return array Список желтых городов
     * @throws \Exception
     */
    public function getYellowCityArray()
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->getYellowCityArray();
    }

    /**
     * Переход на шаг доставки.
     *
     * @throws \Exception
     */
    public function returnToDeliveryStage()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->returnToDeliveryStage();
    }

    /**
     * Переход в личный кабинет.
     *
     * @throws \Exception
     */
    public function goToProfile()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToProfile();
    }

    /**
     * Прокликивает по всем кнопкам в навигационной панели Личного кабинета, кроме B2B и проверяет,
     * что выбрана именно эта кнопка. Проверка вкладки Профиль.
     *
     * @throws \Exception
     */
    public function checkProfile()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->checkProfile();
    }

    /**
     * Клик на ссылку B2b кабинет - в личном кабинете обычного пользователя.
     *
     * @throws \Exception
     */
    public function checkB2BLink()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->checkB2BLink();
    }

    /**
     * grabMultiple с описанием
     *
     * @param string $cssOrXpath Xpath коллекции
     * @param string $linkDescription Описание Xpath
     * @param string $attribute Атрибут коллекции
     * @return array Массив элементов
     */
    public function grabMultipleDesc($cssOrXpath, $linkDescription, $attribute = null)
    {
        $I = $this;

        if (!is_null($linkDescription)) {
            $I->lookForwardTo("grab array of " . $linkDescription . " from page");
        }
        $arrayCollection = $I->grabMultiple($cssOrXpath, $attribute);
        return $arrayCollection;
    }

    /**
     * Проверяет состояние кнопки Сохранить в блоке Оповещения, до и после клика по чекбоксу в этом блоке.
     *
     * @throws \Exception Если не верно отображается доступность кнопки Сохранить
     */
    public function checkProfileButton()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->checkProfileButton();
    }

    /**
     * Проверка блока пагинации.
     *
     * @param string $path
     * @throws \Exception Проверка выбранной страницы
     */
    public function checkPagination($path)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkPagination($path);
    }

    /**
     * Транслитерация строки.
     *
     * @param string $str
     * @param bool $fix_umlauts
     * @return string $str
     */
    public function translit($str, $fix_umlauts = false)
    {
        // Установить опции и кодировку регулярных выражений
        mb_regex_set_options('pd');
        mb_internal_encoding('UTF-8');

        // Привести строку к UTF-8
        if (strtolower(mb_detect_encoding($str, 'utf-8, windows-1251')) == 'windows-1251') {
            $str = mb_convert_encoding($str, 'utf-8', 'windows-1251');
        }

        // Регулярки для удобства
        $regexp1 = '(?=[A-Z0-9А-Я])';
        $regexp2 = '(?<=[A-Z0-9А-Я])';

        // Массивы для замены заглавных букв, идущих последовательно
        $rus = array(
            '/(Ё' . $regexp1 . ')|(' . $regexp2 . 'Ё)/u',
            '/(Ж' . $regexp1 . ')|(' . $regexp2 . 'Ж)/u',
            '/(Ч' . $regexp1 . ')|(' . $regexp2 . 'Ч)/u',
            '/(Ш' . $regexp1 . ')|(' . $regexp2 . 'Ш)/u',
            '/(Щ' . $regexp1 . ')|(' . $regexp2 . 'Щ)/u',
            '/(Ю' . $regexp1 . ')|(' . $regexp2 . 'Ю)/u',
            '/(Я' . $regexp1 . ')|(' . $regexp2 . 'Я)/u'
        );

        $eng = array(
            'YO', 'ZH', 'CH', 'SH', 'SCH', 'YU', 'YA'
        );

        // Заменить заглавные буквы, идущие последовательно
        $str = preg_replace($rus, $eng, $str);

        // Массивы для замены одиночных заглавных и строчных букв
        $rus = array(
            '/а/u', '/б/u', '/в/u', '/г/u', '/д/u', '/е/u', '/ё/u',
            '/ж/u', '/з/u', '/и/u', '/й/u', '/к/u', '/л/u', '/м/u',
            '/н/u', '/о/u', '/п/u', '/р/u', '/с/u', '/т/u', '/у/u',
            '/ф/u', '/х/u', '/ц/u', '/ч/u', '/ш/u', '/щ/u', '/ъ/u',
            '/ы/u', '/ь/u', '/э/u', '/ю/u', '/я/u',

            '/А/u', '/Б/u', '/В/u', '/Г/u', '/Д/u', '/Е/u', '/Ё/u',
            '/Ж/u', '/З/u', '/И/u', '/Й/u', '/К/u', '/Л/u', '/М/u',
            '/Н/u', '/О/u', '/П/u', '/Р/u', '/С/u', '/Т/u', '/У/u',
            '/Ф/u', '/Х/u', '/Ц/u', '/Ч/u', '/Ш/u', '/Щ/u', '/Ъ/u',
            '/Ы/u', '/Ь/u', '/Э/u', '/Ю/u', '/Я/u'
        );

        $eng = array(
            'a', 'b', 'v', 'g', 'd', 'e', 'yo',
            'zh', 'z', 'i', 'y', 'k', 'l', 'm',
            'n', 'o', 'p', 'r', 's', 't', 'u',
            'f', 'h', 'c', 'ch', 'sh', 'sch', '',
            'i', '', 'e', 'yu', 'ya',

            'A', 'B', 'V', 'G', 'D', 'E', 'Yo',
            'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M',
            'N', 'O', 'P', 'R', 'S', 'T', 'U',
            'F', 'H', 'C', 'Ch', 'Sh', 'Sch', '',
            'I', '', 'E', 'Yu', 'Ya'
        );

        // Заменить оставшиеся заглавные и строчные буквы
        $str = preg_replace($rus, $eng, $str);

        // Исправление умляутов и других надсимвольных значков
        if ($fix_umlauts) {
            $str = preg_replace('/&(.)(tilde|uml);/', "$1",
                mb_convert_encoding($str, 'HTML-ENTITIES', 'utf-8'));
        }

        return $str;
    }

    /**
     * Проверка наличия дублирования услуги доставки в оформленном заказе. Вызов из личного кабинета.
     *
     * @throws \Exception
     */
    public function checkDuplicateDelivery()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->checkDuplicateDelivery();
    }

    /**
     * Подтверждение заказа.
     *
     * @param string $paymentType Тип оплаты подтверждаемого заказа
     * @throws \Exception
     */
    public function orderConfirmOnly($paymentType = PAY_CASH)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->orderConfirmOnly($paymentType);
    }

    /**
     * Отмена последнего заказа.
     *
     * @throws \Exception
     */
    public function orderCancel()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->orderCancel();
    }

    /**
     * Проверка наличия дублирования услуги доставки в оформленном заказе. Вызов из личного кабинета.
     *
     * @throws \Exception
     */
    public function checkDuplicateDeliveryCart()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkDuplicateDelivery();
    }

    /**
     * Добавление товара в корзину из ротатора на главной: Недавно смотрели.
     *
     * @throws \Exception
     */
    public function addToCartFromRecentlyMain()
    {
        $I = $this;

        $cartPage = new HomePage($I);
        $cartPage->addToCartFromRecentlyMain();
    }

    /**
     * Добавление товара в корзину из ротатора корзины - с этим товаром покупают.
     *
     * @throws \Exception
     */
    public function addToCartFromCartWithIt()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->addToCartFromCartWithIt();
    }

    /**
     * Переход на карточку товара из ротатора на странице 404.
     */
    public function goToProductFrom404()
    {
        $I = $this;

        $notFoundPage = new NotFound($I);
        $notFoundPage->goToProductFrom404();
    }

    /**
     * Добавление товара в корзину из раздела "Бренды". Выбор раздела бренды, затем выбор "Все бренды".
     * Далее случайный выбор из алфавитной панели, затем случайный выбор бренда из алфавитного раздела.
     * Выбор случайного товара из первого ротатора.
     *
     * @throws \Exception
     */
    public function addToCartFromBrands()
    {
        $I = $this;

        $brandsPage = new Brands($I);
        $brandsPage->addToCartFromBrands();
    }

    /**
     * Проверка категорий на странице бренда. Выбор раздела бренды, затем выбор "Все бренды".
     * Далее случайный выбор из алфавитной панели, затем случайный выбор бренда из алфавитного раздела.
     * Проверка страниц категорий бренда на 404.
     *
     * @throws \Exception
     */
    public function checkBrandCategories()
    {
        $I = $this;

        $brandsPage = new Brands($I);
        $brandsPage->checkBrandCategories();
    }

    /**
     * Выбор подкатегории "Подбери расходку", выбор конкретной модели из выпадающих списков, добавление товара в корзину.
     *
     * @throws \Exception
     */
    public function findSupplies()
    {
        $I = $this;

        $suppliesPage = new Supplies($I);
        $suppliesPage->findSupplies();
    }

    /**
     * Умное ожидание событий.
     *
     * @param int $seconds
     * @throws \Exception
     */
    public function smartWait($seconds)
    {
        $I = $this;

        $I->wait(1);
        $preloader = $I->getNumberOfElements('//div[@class="cl-preloader" and @style="display: block;"]', 'preloader block');
        $preloader = $preloader + $I->getNumberOfElements('//div[@class="cl-preloader" and @style=""]', 'preloader empty');
        if (($preloader != 0)) {
            codecept_debug('check for preloader none');
            $I->waitForElement(['xpath' => '//div[@class="cl-preloader" and @style="display: none;"]']);
        } else {
            $I->wait($seconds - 1);
        }
    }

    /**
     * Выбор категории "Уцененные товары".
     *
     * @throws \Exception
     */
    public function selectDiscountMenu()
    {
        $I = $this;

        $menuPage = new MenuPage($I);
        $menuPage->selectDiscountMenu();
    }

    /**
     * Выбор категории "Сервисы и Услуги".
     *
     * @param string $serviceName название страницы сервиса\услуги
     * @throws \Exception
     */
    public function selectServiceMenu($serviceName = null)
    {
        $I = $this;

        $menuPage = new MenuPage($I);
        $menuPage->selectServiceMenu($serviceName);
    }

    /**
     * Проверка фильтра обзоров.
     *
     * @throws \Exception Если заголовок не равен ожидаемому
     */
    public function checkFilterReview()
    {
        $I = $this;

        $reviewsPage = new Reviews($I);
        $reviewsPage->checkFilterReview();
    }

    /**
     * Переход в конечную категорию для обзоров.
     *
     * @throws \Exception
     */
    public function checkCategoryReview()
    {
        $I = $this;

        $reviewsPage = new Reviews($I);
        $reviewsPage->checkCategoryReview();
    }

    /**
     * Переход на страницу обзора - карточка товара, вкладка обзоры.
     *
     * @throws \Exception
     */
    public function readReview()
    {
        $I = $this;

        $reviewsPage = new Reviews($I);
        $reviewsPage->readReview();
    }

    /**
     * Переход на страницу Обзоров из футера.
     *
     * @throws \Exception
     */
    public function goToReviewsPage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->openPageFromFooter($homePage::REVIEWS);
    }

    /**
     * Переход на страницу Конфигуратора из футера.
     *
     * @throws \Exception
     */
    public function selectConfigMenu()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->openPageFromFooter($homePage::CONFIG);
    }

    /**
     * Применить фильтр.
     *
     * @param $filter "Есть все комплектующие" или "не важно"
     * @throws \Exception
     */
    public function filterConfigByAvailable($filter)
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->filterConfigByAvailable($filter);
    }

    /**
     * Добавление сборки в корзину целиком.
     *
     * @throws \Exception
     */
    public function addConfigToCart()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->addConfigToCart();
    }

    /**
     * Добавление в корзину компонентов сборки отдельно.
     *
     * @throws \Exception
     */
    public function orderConfigSeparated()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->orderConfigSeparated();
    }

    /**
     * Добавить в желания.
     *
     * @throws \Exception
     */
    public function addToWishList()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->addToWishList();
    }

    /**
     * Активация или отключение видимости списка желаний.
     *
     * @param string $turn activate или disable
     * @throws \Exception
     */
    public function activateOrDisableWishListVisibility($turn)
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->activateOrDisableWishListVisibility($turn);
    }

    /**
     * Получение ссылки на список желаний.
     *
     * @return string ссылку на список желаний
     * @throws \Exception
     */
    public function getWishListLink()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        return $profilePage->getWishListLink();
    }

    /**
     * Перейти в желания - через ссылку в верхнем правом блоке.
     *
     * @throws \Exception
     */
    public function goToWishListTopRight()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->goToWishListTopRight();
    }

    /**
     * Добавление в корзину одного товара из желаний, проверка суммы корзины.
     *
     * @return int $currentProductPrice цена товара
     * @throws \Exception Если сумма корзины изменилась не на сумму товаров добавленных в корзину
     */
    public function addToCartFromWishList()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        return $profilePage->addToCartFromWishList();
    }

    /**
     * Добавление в корзину всех товаров из Желаний, проверка суммы корзины.
     *
     * @return int $currentProductPrice цена товаров
     * @throws \Exception Если сумма корзины изменилась не на сумму товаров добавленных в корзину
     */
    public function addToCartFromWishListAll()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        return $profilePage->addToCartFromWishListAll();
    }

    /**
     * Переход на страницу товара, товар может быть указан числом по порядку на странице или выбран случайным образом.
     *
     * @param string $imgNameLink Дополнение Xpath перехода через наименование или картинку
     * @param null $number Номер товара по порядку
     * @throws \Exception
     */
    public function goToProductFromTopPage($imgNameLink, $number = null)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToProductFromTopPage($imgNameLink, $number);
    }

    /**
     * Переход на страницу Все товары в топ 100.
     *
     * @throws \Exception
     */
    public function goToTopHundredPage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToTopHundredPage();
    }

    /**
     * Переход на страницу товара, товар может быть указан числом по порядку на странице или выбран случайным образом.
     *
     * @param string $imgNameLink Дополнение Xpath перехода через наименование или картинку
     * @param string $productContainer Дополнение Xpath для разных типов товаров на главной
     * @param null $number Номер товара по порядку
     * @throws \Exception
     */
    public function goToProductFromSpecialPage($imgNameLink, $productContainer, $number = null)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToProductFromSpecialPage($imgNameLink, $productContainer, $number);
    }

    /**
     * Переход в карточку товара из ротатора "Недавно просмотренные".
     *
     * @param string $imgNameLink Дополнение Xpath перехода через наименование или картинку
     * @param int $number Номер товара по порядку
     * @throws \Exception
     */
    public function goToProductFromMainRotator($imgNameLink, $number = null)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToProductFromMainRotator($imgNameLink, $number);
    }

    /**
     * Получение количества элементов, определяемых ссылкой и описанием.
     *
     * @param string $link ссылка на элементы
     * @param string $linkDescription описание элементов (не обязательно)
     * @return int количество найденных элементов
     */
    public function getNumberOfElementsClear($link, $linkDescription = null)
    {
        $I = $this;

        if (!is_null($linkDescription)) {
            $I->lookForwardTo("get number of " . $linkDescription . " from page");
        }

        //$I->checkElementOnPage($link, $linkDescription);
        $elementsArray = $I->grabMultiple($link);
        $elementsArray = array_filter($elementsArray);
        codecept_debug($elementsArray);

        $numberOfElements = count($elementsArray);
        codecept_debug('Number of ' . $linkDescription . ' is ' . $numberOfElements);

        return $numberOfElements;
    }

    /**
     * Переход в карточку товара из каталога.
     *
     * @param string $productNumber Номер товара в строке подкатегории по порядку
     * @throws \Exception
     */
    public function goToProductFromCatalog($productNumber = null)
    {
        $I = $this;

        $catalogPage = new CatalogPage($I);
        $catalogPage->goToProductFromCatalog($productNumber);
    }

    /**
     * Переход в карточку товара из быстрого поиска.
     *
     * @param string $imgNameLink Дополнение Xpath перехода через наименование или картинку
     * @throws \Exception
     */
    public function goToProductFromSearchFast($imgNameLink)
    {
        $I = $this;

        $searchPage = new SearchPage($I);
        $searchPage->goToProductFromSearchFast($imgNameLink);
    }

    /**
     * Выбор категории товаров в поисковой выдаче.
     *
     * @throws \Exception
     */
    public function selectSearchCategory()
    {
        $I = $this;

        $searchPage = new SearchPage($I);
        $searchPage->selectSearchCategory();
    }

    /**
     * Поиск по форуму.
     *
     * @param string $text Поисковой запрос
     * @throws \Exception
     */
    public function searchStringForum($text)
    {
        $I = $this;

        $forumPage = new Forum($I);
        $forumPage->searchString($text);
    }

    /**
     * Проверка инпута поиска по форуму на emoji.
     *
     * @param string $text Поисковой запрос
     * @throws \Exception
     */
    public function checkForumSearchByEmoji($text)
    {
        $I = $this;

        $forumPage = new Forum($I);
        $forumPage->emojiSearch($text);
    }

    /**
     * Переход в категорию на форуме.
     *
     * @throws \Exception
     */
    public function goToForumCategory()
    {
        $I = $this;

        $forumPage = new Forum($I);
        $forumPage->goToForumCategory();
    }

    /**
     * Переход на страницу форума.
     *
     * @throws \Exception
     */
    public function openForumPage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->openPageFromFooter($homePage::FORUM);
    }

    /**
     * Переход в тему категории на форуме.
     *
     * @param int $param Номер темы в категории на форуме по порядку
     * @throws \Exception
     */
    public function goToForumTopic($param = NULL)
    {
        $I = $this;

        $forumPage = new Forum($I);
        $forumPage->goToTopic($param);
    }

    /**
     * Проверка блока пагинации на странице категории.
     *
     * @throws \Exception Проверка выбранной страницы
     */
    public function checkPaginationTopic()
    {
        $I = $this;

        $forumPage = new Forum($I);
        $forumPage->checkPaginationTopic();
    }

    /**
     * Проверка блока пагинации на странице темы.
     *
     * @throws \Exception Проверка выбранной страницы
     */
    public function checkPaginationPost()
    {
        $I = $this;

        $forumPage = new Forum($I);
        $forumPage->checkPaginationPost();
    }

    /**
     * Переход в чекаут.
     *
     * @throws \Exception
     */
    public function goFromCartToCheckout()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goFromCartToCheckout();
    }

    /**
     * Переход в карточку товара со страницы сравнения.
     *
     * @param string $imgNameLink Дополнение Xpath
     * @throws \Exception
     */
    public function goToProductFromComparePage($imgNameLink = null)
    {
        $I = $this;

        $compare = new Compare($I);
        $compare->goToProduct($imgNameLink);
    }

    /**
     * Получение массива данных о товарах из списка сравнения.
     *
     * @return array $itemsData
     */
    public function getItemsDataFromComparePage()
    {
        $I = $this;

        $compare = new Compare($I);
        return $compare->getItemsData();
    }

    /**
     * Проверка наличия добавленных товаров в списке сравнения.
     *
     * @param array $itemsId
     * @throws \Exception
     */
    public function checkAddedItemsIsVisibleInCompare($itemsId)
    {
        $I = $this;

        $compare = new Compare($I);
        $compare->checkAddedItemsIsVisible($itemsId);
    }

    /**
     * Переход в карточку товара из Заказаов в профиле.
     *
     * @throws \Exception
     */
    public function goToProductFromOrders()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->goToProductFromOrders();
    }

    /**
     * Переход в список заказов в профиле.
     *
     * @throws \Exception
     */
    public function goToOrders()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->goToOrders();
    }

    /**
     * Переход в карточку товара из списка товаров сборки заказа.
     *
     * @param string $imgNameLink
     * @throws \Exception
     */
    public function goToProductFromInstallOrderList($imgNameLink)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goToProductFromInstallOrderList($imgNameLink);
    }

    /**
     * Переход в карточку товара из списка товаров подтверждения заказа.
     *
     * @throws \Exception
     */
    public function goToProductFromConfirmOrderList()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goToProductFromConfirmOrderList();
    }

    /**
     * Переход в карточку товара в корзину из ротаторов корзины - с этим товаром также покупают.
     *
     * @throws \Exception
     */
    public function goToProductFromCartWithIt()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goToProductFromCartWithIt();
    }

    /**
     * Переход в карточку товара в корзину из ротаторов корзины через картинку - с этим товаром также покупают.
     *
     * @throws \Exception
     */
    public function goToProductFromCartPictureWithIt()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goToProductFromCartPictureWithIt();
    }

    /**
     * Переход в карточку товара из ротатора "Вам может пригодиться" по наименованию.
     *
     * @throws \Exception
     */
    public function goToProductFromCartUseful()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goToProductFromRotatorUseful();
    }

    /**
     * Переход в карточку товара из ротатора "Вам может пригодиться" по картинке.
     *
     * @throws \Exception
     */
    public function goToProductFromCartPictureUseful()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goToProductFromPictureRotatorUseful();
    }

    /**
     * Переход в карточку товара из ротатора "История просмотров" по картинке.
     *
     * @throws \Exception
     */
    public function goToProductFromRecentlyRotatorPicture()
    {
        $I = $this;

        $cartPage = new ProductPage($I);
        $cartPage->goToProductFromRecentlyRotatorPicture();
    }

    /**
     * Выбор вкладки Вопросы-Ответы в карточке товара.
     *
     * @return int $answers наличие ответов
     * @throws \Exception
     */
    public function selectAnswerPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->selectAnswerPage();
    }
    /**
     *
     * Ответ на вопрос  во вкладке Вопросы-Ответы в карточке товара.
     *
     * @param $param string текст ответа
     * @throws \Exception
     */
    public function answerForQuestion($param)
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->answerForQuestion($param);
    }

    /**
     * Переход в профиль эксперта из Вопросов-Ответов в карточке товара.
     *
     * @throws \Exception
     */
    public function goToExpertProfile()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->goToExpertProfile();
    }

    /**
     * Переход на вкладку Вопросы-Ответы в профиле другого пользователя.
     *
     * @throws \Exception
     */
    public function goToAnswersOther()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->goToAnswersOther();
    }

    /**
     * Проверка блока пагинации.
     *
     * @return int $pagination ниличие блока пагинации
     * @throws \Exception Проверка выбранной страницы
     */
    public function checkPaginationProfileOther()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        return $profilePage->checkPagination($profilePage::XPATH_REVIEW);
    }

    /**
     * Переход на карточку товара из Вопросов-Ответов.
     *
     * @throws \Exception
     */
    public function goToProductFromProductListingAnswer()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->goToProductFromProductListingAnswer();
    }

    /**
     * Переход на страницу бренда в категории.
     *
     * @throws \Exception Если выбранный бренд не соответсвует бренду в хлебных крошках.
     */
    public function selectBaseBrand()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->selectBaseBrand();
    }

    /**
     * Проверка брендов в футере.
     *
     * @throws \Exception Если выбранный бренд не соответсвует бренду в хлебных крошках.
     */
    public function checkFooterBrand()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkFooterBrand();
    }

    /**
     * Возвращает тип доставки со страницы доставки.
     *
     * @return string Тип доставки
     * @throws \Exception
     */
    public function getTypeOfDelivery()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->getTypeOfDelivery();
    }

    /**
     * Генерация рандомной строки, количиство цифр в строке задается параметром.
     *
     * @param string $length Количество цифр в результирующей строке
     * @return string
     */
    function generateString($length = NULL)
    {
        $chars = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюяАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯabdefhiknrstyzABDEFGHKNQRSTYZ0123456789-_()[]{}';
        $numChars = mb_strlen($chars, 'UTF-8');
        $string = '';
        $numLenght = 0;

        for ($i = 0; ; $i++) {
            $rndChar = rand(1, $numChars) - 1;
            $stringAdd = mb_substr($chars, $rndChar, 1, 'UTF-8');
            if (is_numeric($stringAdd)) {
                $numLenght = $numLenght + 1;
                codecept_debug($numLenght);
            }

            if ($numLenght > $length) {
                break;
            }

            $string .= $stringAdd;
        }

        return $string;
    }

    /**
     * Получение промо кода из акции.
     *
     * @return string Промокод
     * @throws \Exception
     */
    public function getPromoCode()
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        return $actionPage->getPromoCode();
    }

    /**
     * Фильтрация товаров в акции по размеру скидки.
     *
     * @param int $percentFromDescription процент скидки из олписания акции
     * @return int или array discount процент/размер скидки (диапазон, если массив)
     * @throws \Exception
     */
    public function sortActionsItemsByDiscount($percentFromDescription)
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        return $actionPage->sortActionsItemsByDiscount($percentFromDescription);
    }

    /**
     * Заполнение поля Промо код.
     *
     * @param string $promoCode
     * @throws \Exception
     */
    public function usePromoCode($promoCode)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->usePromoCode($promoCode);
    }

    /**
     * Проверка наличия скидки по акции.
     *
     * @throws \Exception
     */
    public function checkPromoDiscount()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkPromoDiscount();
    }

    /**
     * Выбор чекбокса товара.
     *
     * @param int $itemNumber Номер товара в списке по порядку
     * @throws \Exception
     */
    public function selectCheckBoxItem($itemNumber)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->selectCheckBoxItem($itemNumber);
    }

    /**
     * Увелечение количества штук товара.
     *
     * @param int $itemNumber Номер товара в списке по порядку
     * @throws \Exception
     */
    public function increaseItemValue($itemNumber)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->increaseItemValue($itemNumber);
    }

    /**
     * Уменьшение количества штук товара.
     *
     * @param int $itemNumber Номер товара в списке по порядку
     * @throws \Exception Если добавлена 1 штука товара
     */
    public function reduceItemValue($itemNumber)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->reduceItemValue($itemNumber);
    }

    /**
     * Получение номера товара в списке.
     *
     * @return int Номер товара в списке
     */
    public function getRandomItemNumberFromCart()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->getRandomItemNumber();
    }

    /**
     * Удаление товара из корзины через выбор чекбокса.
     *
     * @param int $itemNumber Номер товара в списке по порядку
     * @throws \Exception
     */
    public function deleteItemCheckBox($itemNumber)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->deleteItemCheckBox($itemNumber);
    }

    /**
     * Получение случайного Id услуги из корзины.
     *
     * @param string $servicesKind вид услуги
     * @param string $productId айди товара
     * @return string Id услуги
     * @throws \Exception
     */
    public function getServiceIdFromCart($productId, $servicesKind = null)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->getServiceId($productId, $servicesKind);
    }

    /**
     * Добавление случайной услуги в корзине.
     *
     * @param string $itemId Id услуги
     * @param string $productId Id товара
     * @return array Наименование и стоимость выбранной услуги
     * @throws \Exception Если выбранная услуга, ее наименование и стоимость, не соответствует добавленной
     */
    public function addServiceCartPage($itemId, $productId)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->addService($itemId, $productId);
    }

    /**
     * Добавление случайной цифровой услуги в корзине.
     *
     * @param string $itemId Id услуги
     * @param string $productId Id товара
     * @return array $serviceData Наименование, стоимость и id выбранной услуги
     * @throws \Exception Если выбранная услуга, ее наименование и стоимость, не соответствует добавленной
     */
    public function addDigitalServiceFromCart($itemId, $productId)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->addDigitalService($itemId, $productId);
    }

    /**
     * Проверка блока пагинации Обзоров.
     *
     * @return int $pagination ниличие блока пагинации
     * @throws \Exception Проверка выбранной страницы
     */
    public function checkPaginationReview()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        return $profilePage->checkPagination(\Page\Reviews::LISTING_TITLE_REVIEW);
    }

    /**
     * Выбор фильтра на странице акций.
     *
     * @param string $sort
     * @throws \Exception В случае
     */
    public function setSort($sort)
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        $actionPage->setSort($sort);
    }

    /**
     * Проверка добавленного подарка в корзине.
     *
     * @throws \Exception Если не содержит текса "Подарок!"
     */
    public function checkGift()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkGift();
    }

    /**
     * Проверка контактных данных.
     *
     * @param array $contacts Введенные контактные данные
     * @throws \Exception Если отображаемые контактные данные не равны ранее введенным
     */
    public function checkContacts($contacts)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkContacts($contacts);
    }

    /**
     * Логин со страницы ошибочного пароля.
     *
     * @param string $username
     * @param string $password
     * @throws \Exception
     */
    public function doLoginAfterFailEnter($username = '', $password = '')
    {
        $I = $this;

        if (empty($username) && empty($password)) {
            $user = $this->getUserByType();
            $username = $user['username'];
            $password = $user['password'];
        }

        $loginPage = new LoginPage($I);
        $loginPage->doLoginAfterFailEnter($username, $password);
    }

    /**
     * Логин со страницы /login/
     *
     * @param string $username
     * @param string $password
     * @throws \Exception
     */
    public function doLoginFromPage($username = '', $password = '')
    {
        $I = $this;

        if (empty($username) && empty($password)) {
            $user = $this->getUserByType();
            $username = $user['username'];
            $password = $user['password'];
        }

        $loginPage = new LoginPage($I);
        $loginPage->doLoginFromPage($username, $password);
        $loginPage->checkLoginDropdown();
    }

    /**
     * Проверка страницы ошибочного ввода пароля.
     *
     * @throws \Exception Если доступна кнопка логина при пустых полях, если отсутствуют ссылки на забытый пароль и регистрацию
     */
    public function checkFailEnterPage()
    {
        $I = $this;

        $loginPage = new LoginPage($I);
        $loginPage->checkFailEnterPage();
    }

    /**
     * Выбор типа магазина в списке.
     *
     * @param string $store Тип магазина
     * @throws \Exception В случае если выбранный тип магазина не соответствует ожидаемомму
     */
    public function selectTypeStore($store)
    {
        $I = $this;

        $aboutPage = new About($I);
        $aboutPage->chooseTypeOfStore($store);
    }

    /**
     * Переход на страницу магазина из списка.
     *
     * @return mixed название выбранного магазина
     * @throws \Exception
     */
    public function selectStore()
    {
        $I = $this;

        $aboutPage = new About($I);
        return $aboutPage->selectStore();
    }

    /**
     * Проверка страницы магазина.
     *
     * @param string $typeStore Тип магазина
     * @throws \Exception Если тип магазина не соотвествует типу переданному в параметре
     */
    public function checkStorePage($typeStore)
    {
        $I = $this;

        $aboutPage = new About($I);
        $aboutPage->checkStorePage($typeStore);
    }

    /**
     * Переход на страницу Адреса магазинов.
     *
     * @throws \Exception
     */
    public function openStoreAddressPage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToStoreAddressesPage();
    }

    /**
     * Проверка поиск адреса магазина на emoji.
     *
     * @param string $param запрос поиска
     * @throws \Exception
     */
    public function checkSearchStoreAddressForEmoji($param)
    {
        $I = $this;

        $aboutPage = new About($I);
        $aboutPage->checkSearchStoreAddressForEmoji($param);
    }

    /**
     * Выбрать фильтр Показывать товар - Любой.
     *
     * @throws \Exception Если фильтр останется не выбранным
     */
    public function selectFilterShowProductAny()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->selectFilterShowProductAny();
    }

    /**
     * Переход в карточку товара не в наличии.
     *
     * @throws \Exception Если отсутсвуют товары не в наличии
     */
    public function goToOutOfStockProduct()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->goToOutOfStockProduct();
    }

    /**
     * Проверка карточки товара Не в наличии.
     *
     * @throws \Exception Если дата не соответсвует формату "22 сентября 2004"
     */
    public function checkOutOfStockProduct()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->checkOutOfStockProduct();
    }

    /**
     * Переход к списку точек самовывоза.
     *
     * @return int Количество не выбранных точек самовывоза
     * @throws \Exception
     */
    public function goToPickupPointList()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->goToPickupPointList();
    }

    /**
     * Проверка наличия блока экстра бонусов на странице списка товаров в корзине.
     *
     * @return string Кол-во экстрабонусов в корзине
     * @throws \Exception Если не найден блок с экстра бонусами
     */
    public function checkExtraBonusCart()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->checkExtraBonusCart();
    }

    /**
     * Проверка наличия блока экстра бонусов и их суммы внутри шагов корзины.
     *
     * @param $extraBonusCart
     * @throws \Exception Если не найден блок с экстра бонусами или сумма экстра бонусов не равна сумме на предыдущем шаге в корзине
     */
    public function checkExtraBonusStep($extraBonusCart)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkExtraBonusStep($extraBonusCart);
    }

    /**
     * Проверка тэгов фильтров в листинге.
     *
     * @param string $tag Верхний или нижний тэг
     * @param int $half Проверяемая половина тэгов - 0,1
     * @throws \Exception Если тэги отсуствуют или после применения список товаров не изменился
     */
    public function checkListingTag($tag, $half = null)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkListingTag($tag, $half);
    }

    /**
     * Переход на страницу акции с главной.
     */
    public function goToSingleActionFromHomePage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToSingleActionFromHomePage();
    }

    /**
     * Переход на страницу акций.
     *
     * @throws \Exception
     */
    public function goToActionsFromHomePage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToActionsFromHomePage();
    }

    /**
     * Добавление нового адреса доставки в ЛК.
     *
     * @throws \Exception
     */
    public function addDeliveryAddress()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->addDeliveryAddress();
    }

    /**
     * Изменение адреса доставки в ЛК.
     *
     * @throws \Exception
     */
    public function editDeliveryAddress()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->editDeliveryAddress();
    }

    /**
     * Удаление последнего адреса доставки в ЛК.
     *
     * @throws \Exception
     */
    public function deleteDeliveryAddress()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->deleteDeliveryAddress();
    }

    /**
     * Удаление всех сохраненных адресов доставки в ЛК.
     *
     * @throws \Exception
     */
    public function deleteDeliveryAddresses()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->deleteDeliveryAddresses();
    }

    /**
     * Заполнение поля данными с предварительной очисткой.
     *
     * @param string $link Xpath
     * @param $description string Описание объекта
     * @param $data string Даныне для заполенния
     * @throws \Exception
     */
    public function waitAndReplace($link, $description, $data)
    {
        $I = $this;

        $I->lookForwardTo("fill " . $description . " with " . $data);

        $I->waitAndClick($link, $description);
        $I->doubleClick($link);
        $I->pressKey($link, \WebDriverKeys::DELETE);
        $I->wait(SHORT_WAIT_TIME);
        $I->fillField($link, $data);
    }

    /**
     * Проверка отсутствия блока подборки расходных материалов в листинге определенной категории или товаре.
     *
     * @param string $categoryOrProduct Наименование проверяемой категории или товара
     * @throws \Exception Если отображается блок подборки расходных материалов
     */
    public function checkSupplies($categoryOrProduct)
    {
        $I = $this;

        $suppliesPage = new Supplies($I);
        $suppliesPage->checkSupplies($categoryOrProduct);
    }

    /**
     * Проверка формы "Перезвонить мне".
     *
     * @param string $phone Номер телефона
     * @param string $name имя
     * @param string $captcha капча если необходимо
     * @throws \Exception
     */
    public function checkCallBackForm($phone, $name, $captcha = null)
    {
        $I = $this;

        $feedbackPage = new Feedback($I);
        $feedbackPage->checkCallBackForm($phone, $name, $captcha);
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
    public function checkAskForm($name, $email, $question, $b2b = false, $captcha = null)
    {
        $I = $this;

        $feedbackPage = new Feedback($I);
        $feedbackPage->checkAskForm($name, $email, $question, $b2b, $captcha);
    }

    /**
     * Выбрать форму "Задать вопрос".
     *
     * @throws \Exception
     */
    public function openFeedbackForm()
    {
        $I = $this;

        $feedbackPage = new Feedback($I);
        $feedbackPage->openFeedbackForm();
    }

    /**
     * Открыть окно формы "перезвонить".
     *
     * @throws \Exception
     */
    public function openCallbackForm()
    {
        $I = $this;

        $feedbackPage = new Feedback($I);
        $feedbackPage->openCallbackForm();
    }

    /**
     * Переход на страницу Обратной связи из шапки.
     *
     * @throws \Exception
     */
    public function selectFeedbackMenu()
    {
        $I = $this;

        $menuPage = new MenuPage($I);
        $menuPage->selectFeedbackMenu();
    }

    /**
     * Переход в карточку товара, который отсутствует в наличии в полноформатном магазине.
     *
     * @throws \Exception Если все товары из листинга присутствуют в наличии в магазине
     */
    public function goToProductOutOfMarket()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->goToProductOutOfMarket();
    }

    /**
     * Получение ай ди товара с главной страницы.
     *
     * @param int $categoryNumber Номер категории по порядку
     * @param int $productNumber Номер товара в категории по порядку
     * @return string Ай ди товара
     * @throws \Exception
     */
    public function getItemIdFromHomePage($categoryNumber = null, $productNumber = null)
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->getItemId($categoryNumber, $productNumber);
    }

    /**
     * Получение ID случайного товара с главной страницы.
     */
    public function getRandomItemNumberFromHomePage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->getRandomItemNumber();
    }

    /**
     * Получение Id со страницы товара.
     *
     * @return mixed
     */
    public function getItemIdFromProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->getItemId();
    }

    /**
     * Проверка вкладки Активность.
     *
     * @throws \Exception
     */
    public function checkProfileAction()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->checkProfileAction();
    }

    /**
     * Проверка вкладки Гарантийный раздел.
     *
     * @throws \Exception
     */
    public function checkProfileWarranty()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->checkProfileWarranty();
    }

    /**
     * Переход на вкладку Гарантийный раздел.
     *
     * @throws \Exception
     */
    public function selectProfileWarranty()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->selectProfileWarranty();
    }

    /**
     * Ввод номера документа в поле во вкладке Гарантийный раздел.
     *
     * @param $param string текст ответа
     * @throws \Exception
     */
    public function fillDocumentNumber($param)
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->fillDocumentNumber($param);
    }

    /**
     * Выбор вкладки "Отзывы" в карточке товара.
     *
     * @throws \Exception
     */
    public function selectOpinionProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->selectOpinionPage();
    }

    /**
     * Открыть форму добавление нового отзыва.
     *
     * @throws \Exception
     */
    public function selectNewProductOpinion()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->selectNewOpinion();
    }

    /**
     * Добавить новый отзыв.
     *
     * @param $param string новый отзыв
     * @throws \Exception
     */
    public function addNewProductOpinion($param)
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->addNewOpinion($param);
    }

    /**
     * Открыть форму "Пожаловаться" на комментарий.
     *
     * @throws \Exception
     */
    public function openAnswerCommentReportForm()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->openAnswerCommentReportForm();
    }

    /**
     * Получение даты рождения из личного кабинета.
     *
     * @return array Дата рождения День, Месяц, Год
     */
    public function getBirthDate()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        return $profilePage->getBirthDate();
    }

    /**
     * Установка даты рождения в личном кабинете.
     *
     * @param string $date Дата рождения День, Месяц, Год
     * @throws \Exception
     */
    public function setBirthDate($date)
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->setBirthDate($date);
    }

    /**
     * Активация блока редактирования личных данных.
     *
     * @throws \Exception
     */
    public function editPersonalData()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->editPersonalData();
    }

    /**
     * Сохранение изменений личных данных в личном кабинете.
     *
     * @throws \Exception
     */
    public function savePersonalData()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->savePersonalData();
    }

    /**
     * Проверка баннера на второй позиции.
     *
     * @throws \Exception Если баннер не найден на второй позиции
     */
    public function checkListingBanner()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkListingBanner();
    }

    /**
     * Переход на страницу Сервисы и Услуги.
     *
     * @throws \Exception
     */
    public function goFromCartToService()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goFromCartToService();
    }

    /**
     * Переход на ШАГ сборки заказа.
     *
     * @throws \Exception
     */
    public function goToInstallStep()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goToInstallStep();
    }

    /**
     * Добавление услуги в корзину.
     *
     * @param string $itemId Id категории услуги
     * @param int $rndSer Номер услуги в списке
     * @return string Id добавленной услуги
     * @throws \Exception
     */
    public function selectDeleteService($itemId, $rndSer)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->selectService($itemId, $rndSer);
    }

    /**
     * Проверяет наличие кнопки типа услуги.
     *
     * @param string $itemId
     * @throws \Exception Если кнопка с типом услуг отсутствует
     */
    public function checkNoneServiceByType($itemId)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkNoneServiceByType($itemId);
    }

    /**
     * Сравнение наименований услуг.
     *
     * @param string $currentServiceName Наименование проверяемой услуги
     * @param string $itemId Id категории услуги
     * @param int $rndSer Номер услуги по порядку
     * @throws \Exception Если сравниваемые услуги не равны
     */
    public function compareServiceName($currentServiceName, $itemId, $rndSer)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->compareServiceName($currentServiceName, $itemId, $rndSer);
    }

    /**
     * Проверка количества позиций на странице подтверждения заказа.
     *
     * @param int $positions Ожидаемое количество позиций
     * @throws \Exception Если количество позиций не соответсвует значению
     */
    public function checkNumberOfPositions($positions)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkNumberOfPositions($positions);
    }

    /**
     * Выбор фильтра Показать товар - Есть в магазине.
     *
     * @throws \Exception
     */
    public function selectFilterShowInShop()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->selectFilter($listingPage::SHOW_ITEM, 'Есть в Магазине');
    }

    /**
     * Проверяет, что фильтр "Показать товар - Есть в магазине" выбран.
     *
     * @throws \Exception
     */
    public function checkSelectedFilterShowInShop()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkSelectedFilter($listingPage::SHOW_ITEM, 'Есть в Магазине');
    }

    /**
     * Проверка перехода по ссылкам хлебных крошек.
     *
     * @throws \Exception Если заголовок на странице не содержит наименование хлебной крошки
     */
    public function checkBreadcrumbs()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkBreadcrumbs();
    }

    /**
     * Проверка страницы Каталог товаров.
     *
     * @throws \Exception Если наименование заголовока не соответствует "Каталог товаров"
     */
    public function checkCatalogPage()
    {
        $I = $this;

        $catalogPage = new CatalogPage($I);
        $catalogPage->checkCatalogPage();
    }

    /**
     * Подтверждение заказа и клик на повторить заказ.
     *
     * @throws \Exception Кнопка подтверждения недоступна
     */
    public function orderRepeat()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->orderRepeat();
    }

    /**
     * Получение списка ID товаров в корзине.
     *
     * @return array Список id товаров в корзине
     */
    public function getProductsIdFromCart()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->getProductsIdFromCart();
    }

    /**
     * Очистка корзины.
     *
     * @throws \Exception
     */
    public function cleanCart()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->cleanCart();
    }

    /**
     * Проверка отсутствия текста в блоке товаров и услуг на странице подтверждения.
     *
     * @param string $textDelivery Искомый текст
     */
    public function checkDelivery($textDelivery)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkDelivery($textDelivery);
    }

    /**
     * Проверка работы фильтра из блока full filter. Выбор, а затем отмена фильтра.
     *
     * @throws \Exception
     */
    public function checkSelectFullFilterSection()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkSelectFullFilterSection();
    }

    /**
     * Выбор случайного фильтра из блока full filter.
     *
     * @return string Filter Id
     * @throws \Exception
     */
    public function selectFullFilterSection()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->selectFullFilterSection();
    }

    /**
     * Переход в карточку товара из Заказаов в профиле.
     *
     * @throws \Exception
     */
    public function goToProductFromLastOrder()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->goToProductFromLastOrder();
    }

    /**
     * Ожидание выполнения применения фильтров в листинге.
     *
     * @throws \Exception
     */
    public function waitForFilterEnd()
    {
        $I = $this;

        $I->waitForElementVisible(['class' => 'FiltersNew__loading-container']);
        $I->waitForElementNotVisible(['class' => 'FiltersNew__loading-container']);
    }

    /**
     * Проверка кол-ва товаров не в наличии.
     *
     * @return int Кол-во товаров не в наличии.
     */
    public function checkListingOutOfStock()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->checkListingOutOfStock();
    }

    /**
     * Проверка наличия.
     *
     * @throws \Exception Если не в наличии
     */
    public function checkProductInStock()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->checkProductInStock();
    }

    /**
     * Переход в карточку товара в наличии.
     *
     * @throws \Exception
     */
    public function goToProductInStockFromListing()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->goToProductInStockFromListing();
    }

    /**
     * Получение текущей суммы товаров в Корзине (указывается на каждой странице сверху справа) после изменения.
     *
     * @return int Сумма корзины
     */
    public function getTopRightAmountAfterChange()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->getTopRightAmountAfterChange();
    }

    /**
     * Переход на страницу доставки с второго шага добавления услуг.
     *
     * @throws \Exception
     */
    public function goFromSecondStepCartToDelivery()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goFromSecondStepCartToDelivery();
    }

    /**
     * Выбор вкладки "Услуги" в карточке товара.
     *
     * @throws \Exception
     */
    public function selectServicesProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->selectServicesPage();
    }

    /**
     * Проверка категорий во вкладке акссессуров.
     *
     * @throws \Exception
     */
    public function checkVisibilityOfCategoriesInAccessoriesTab()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->checkVisibilityOfCategoriesInAccessoriesTab();
    }

    /**
     * Получение наименования и стоимости доступных цифровых услуг.
     *
     * @param $xpathDigitalService
     * @param $xpathDigitalServicePrice
     * @param $xpathDigitalServiceName
     * @return array Наименование, цена
     */
    public function getDigitalService($xpathDigitalService, $xpathDigitalServicePrice, $xpathDigitalServiceName)
    {
        $I = $this;

        $xpathPrice = $xpathDigitalService . $xpathDigitalServicePrice;
        codecept_debug($xpathPrice);
        $xpathName = $xpathDigitalService . $xpathDigitalServiceName;
        codecept_debug($xpathName);

        $I->waitForElementChange(['xpath' => $xpathPrice], function (WebDriverElement $el) {
            return $el->isDisplayed();
        }, SHORT_WAIT_TIME);
        $prices = $I->grabMultiple(['xpath' => $xpathPrice]);
        $I->seeArrayNotEmpty($prices);
        $names = $I->grabMultiple(['xpath' => $xpathName]);
        $I->seeArrayNotEmpty($names);
        $service = array();
        foreach ($names as $key => $value) {
            $service[] = array('name' => $value, 'price' => $prices[$key]);
        }

        return $service;
    }

    /**
     * Добавление в корзину случайной цифровой услуги.
     *
     * @param $xpathDigitalService
     * @param $xpathDigitalServicePrice
     * @param $xpathDigitalServiceName
     * @return mixed Наименование, цена добавленной услуги
     * @throws \Exception
     */
    public function addToCartDigitalService($xpathDigitalService, $xpathDigitalServicePrice, $xpathDigitalServiceName)
    {
        $I = $this;

        $service = $this->getDigitalService($xpathDigitalService, $xpathDigitalServicePrice, $xpathDigitalServiceName);
        $selectedService = array_rand($service);
        codecept_debug($selectedService . ' array random');
        codecept_debug($service[$selectedService]);
        $clickService = $selectedService + 1;
        codecept_debug($clickService . ' click service');
        $initialCartAmount = $I->getCurrentCartAmountInitial();
        codecept_debug($initialCartAmount . 'initial cart');
        $I->waitAndClick($xpathDigitalService . '[' . $clickService . ']//button[@type="submit"]', 'add digital service');
        $currentCartAmount = $I->getTopRightAmountAfterChange();
        codecept_debug($service[$selectedService]['price'] . 'price');
        $I->seeCartAmount($currentCartAmount, $initialCartAmount + $service[$selectedService]['price']);

        return $service[$selectedService];
    }

    /**
     * Переход на страницу новостей.
     *
     * @throws \Exception
     */
    public function goToNewsPage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToNewsPage();
    }

    /**
     * Получение списка новостей со страницы новостей.
     *
     * @return array Список новостей
     */
    public function checkNewsPage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->getNews();
    }

    /**
     * Выбор города из списка "В наличии сейчас".
     *
     * @throws \Exception
     */
    public function selectStoreInStockProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->selectStoreInStock();
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
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->changePassword($oldPass, $newPass);
    }

    /**
     * Переход на страницу Барахолка.
     *
     * @throws \Exception
     */
    public function openUsedPage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->openPageFromFooter($homePage::USED);
    }

    /**
     * Переход на страницу добавления объявления.
     *
     * @throws \Exception
     */
    public function addNewAdv()
    {
        $I = $this;

        $usedPage = new UsedPage($I);
        $usedPage->addNewAdv();
    }

    /**
     * Заполнение обязательных полей при создании объявления.
     *
     * @throws \Exception
     */
    public function fillRequireAdv()
    {
        $I = $this;

        $usedPage = new UsedPage($I);
        $usedPage->fillRequireAdv();
    }

    /**
     * Переход в карточку объявления из листинга.
     *
     * @return mixed Наименование товара
     * @throws \Exception
     */
    public function goToAdvCard()
    {
        $I = $this;

        $usedPage = new UsedPage($I);
        return $usedPage->goToAdvCard();
    }

    /**
     * Проверка всех ссылок в подвале.
     *
     * @throws \Exception
     */
    public function checkAllFooterLinks()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->checkAllFooterLinks();
    }

    /**
     * Проверка сообщения о неверно введеном коде.
     *
     * @throws \Exception При отсутствии сообщения
     */
    public function checkMessageWrongCode()
    {
        $I = $this;

        $feedbackPage = new Feedback($I);
        $feedbackPage->checkMessageWrongCode();
    }

    /**
     * Переход на страницу профиля по id.
     *
     * @param string $id IL01234567
     * @throws \Exception
     */
    public function goToProfileById($id)
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->goToProfileById($id);
    }

    /**
     * Отправка сообщения.
     *
     * @param string $message Текст сообщения
     * @throws \Exception
     */
    public function sendPrivateMessage($message)
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->sendPrivateMessage($message);
    }

    /**
     * Получение полноформатных магазинов из списка возможных точек самовывоза.
     */
    public function getFullStorePointCartPage()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->getFullStorePoint();
    }

    /**
     * Проверка соответсвия значения номера телефона по-умолчанию.
     *
     * @param string $checkPhone Проверяемый номер телефона
     */
    public function checkDefaultPhoneSelfDelivery($checkPhone)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkDefaultPhoneSelfDelivery($checkPhone);
    }

    /**
     * Проверка соответсвия значения номера телефона по-умолчанию для поулчения чека по СМС.
     *
     * @param $checkPhone
     */
    public function checkDefaultPhoneGetCheque($checkPhone)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkDefaultPhoneGetCheque($checkPhone);
    }

    /**
     * Выбор получения чека по СМС.
     *
     * @throws \Exception
     */
    public function selectChequeBySms()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->selectChequeBySms();
    }

    /**
     * Переход в карточку товара, для которой есть клубная цена.
     *
     * @return mixed Id карточки товара
     * @throws \Exception
     */
    public function goToProductWithClubPrice()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->goToProductWithClubPrice();
    }

    /**
     * Проверка наличия клубных цен в листинге.
     *
     * @return int $clubPriceCount количество клубных цен в листинге
     */
    public function checkClubPriceAvailabilityInListing()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->checkClubPriceAvailability();
    }

    /**
     * Получение клубной цены товара.
     *
     * @return int $clubProductPrice Клубная цена товара
     * @throws \Exception
     */
    public function checkClubPriceOnProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->checkClubPrice();
    }

    /**
     * Проверка цены для участника клуба. В том числе, что не отображается цена с короной.
     *
     * @param $nonAuthClubPrice
     */
    public function checkAuthClubPrice($nonAuthClubPrice)
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->checkAuthClubPrice($nonAuthClubPrice);
    }

    /**
     * Переход в AMP каталог.
     *
     * @throws \Exception
     */
    public function goToAmpCatalog()
    {
        $I = $this;

        $homePage = new AmpPage($I);
        $homePage->goToAmpCatalog();
    }

    /**
     * Переход к листингу товаров из каталога.
     *
     * @param null $param Категория
     * @param null $subCat Подкатегория
     * @param null $lowCat Вторая подкатегория
     * @return mixed
     * @throws \Exception
     */
    public function goToAmpListing($param = null, $subCat = null, $lowCat = null)
    {
        $I = $this;

        $ampPage = new AmpPage($I);
        return $ampPage->goToAmpListing($param, $subCat, $lowCat);
    }

    /**
     * Переход в карточку товара из листинга.
     *
     * @return null|string|string[] Id товара
     * @throws \Exception
     */
    public function goToAmpProductPage()
    {
        $I = $this;

        $ampPage = new AmpPage($I);
        return $ampPage->goToAmpProductPage();
    }

    /**
     * Добавление товара в корзину из карточки товара.
     *
     * @throws \Exception
     */
    public function addToCartFromAmpProductPage()
    {
        $I = $this;

        $ampPage = new AmpPage($I);
        $ampPage->addToCartFromAmpProductPage();
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
    public function submitRegistrationContactForm($name, $password, $email, $phone, $b2b = false)
    {
        $I = $this;

        $regPage = new Registration($I);
        $regPage->fillRegistrationContactForm($name, $password, $email, $phone, $b2b);
    }

    /**
     * Повторный запрос смс-кода для регистрации.
     *
     * @throws \Exception
     */
    public function resendSmsCodeForRegistration()
    {
        $I = $this;

        $regPage = new Registration($I);
        $regPage->resendSmsCode();
    }

    /**
     * Повторный запрос смс-кода в ЛК.
     *
     * @throws \Exception
     */
    public function resendSmsCodeForProfile()
    {
        $I = $this;

        $I->waitForElementVisible('//*[.="Отправить еще"]', 61);
        $I->waitAndClick('//*[.="Отправить еще"]', 'resend sms code', true);
        $I->waitForElementNotVisible('//*[.="Отправить еще"]');
    }

    /**
     * Переход на страницу регистрации.
     *
     * @throws \Exception
     */
    public function openRegistrationForm()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->openRegistrationForm();
    }

    /**
     * Заполнение формы ввода смс-кода.
     *
     * @param $smsCode
     * @throws \Exception
     */
    public function submitConfirmSmsCodeForm($smsCode)
    {
        $I = $this;

        $regPage = new Registration($I);
        $regPage->submitConfirmSmsCodeForm($smsCode);
    }

    /**
     * Получение token'a для подтверждения номера телефона.
     *
     * @return mixed Token
     */
    public function getConfirmToken()
    {
        $I = $this;

        $regPage = new Registration($I);
        return $regPage->getConfirmToken();
    }

    /**
     * Заполнение формы персональных данных.
     *
     * @throws \Exception
     */
    public function submitRegistrationPersonalForm()
    {
        $I = $this;

        $regPage = new Registration($I);
        $regPage->submitRegistrationPersonalForm();
    }

    /**
     * Удаление тестового пользователя.
     *
     * @param string $userId Идентификатор пользователя
     * @return mixed Статус операции
     * @throws \Exception
     */
    public function deleteUser($userId)
    {
        $I = $this;

        $resultCode = $I->deleteUserById($userId);
        $I->seeUserIsDeleted($resultCode);
    }

    /**
     * Удаление тестового пользователя.
     *
     * @param string $userId Идентификатор пользователя
     * @param string $revId Идентификатор обзора
     * @return mixed Статус операции
     * @throws \Exception
     */
    public function deleteReview($userId, $revId)
    {
        $I = $this;

        $resultCode = $I->deleteReviewById($userId, $revId);
        $I->seeValuesAreEquals($resultCode, "ok");
    }

    /**
     * Получение userId из url страницы Активности.
     *
     * @return mixed userId
     */
    public function getUserId()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        return $profilePage->getUserId();
    }

    /**
     * Клик на кнопке, если она есть на экране - например всплывающее сообщение
     *
     * @param string $link
     * @param string $description
     * @throws \Exception
     */
    public function clickButtonIfExist($link, $description)
    {
        $I = $this;

        $buttonExists = $I->getNumberOfElements($link);
        if ($buttonExists != 0) {
            $I->waitAndClick($link, $description);
        }
    }

    /**
     * Переход на старницу Активность в профиле.
     *
     * @throws \Exception
     */
    public function goToProfileAction()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->goToProfileAction();
    }

    /**
     * Отключение проверки реКапчи для тестов - если она включена в настройках основного сайта
     *
     * @throws \Exception
     */
    public function disableCaptchaCheck()
    {
        $I = $this;
        $searchString = "@captcha.provider.recaptcha_provider";
        $stringFromFile = file_get_contents("../../src/CaptchaBundle/Resources/config/services.yml");
        $firstPosition = strpos($stringFromFile, $searchString);
        codecept_debug('First position is ' . $firstPosition);
        $secondPosition = strpos($stringFromFile, $searchString, $firstPosition + 1);
        codecept_debug('Second position is ' . $secondPosition);
        $I->setCookie('captcha_token', $I->disableCaptcha());
    }

    /**
     * Применить бонусы.
     *
     * @param string $phone номер телефона для смс
     * @param bool $resend повторная отправка кода
     * @throws \Exception
     */
    public function applyBonuses($phone, $resend = false)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->applyBonuses($phone, $resend);
    }

    /**
     * Выбор последней страницы пагинации.
     *
     * @param string $path Xpath листинга товаров
     * @throws \Exception
     */
    public function selectLastPaginationPage($path)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->selectLastPaginationPage($path);
    }

    /**
     * Выбор срочной доставки.
     *
     * @throws \Exception
     */
    public function selectExpressDelivery()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->selectExpressDelivery();
    }

    /**
     * Проверка наличия услуги срочной доставки.
     *
     * @throws \Exception
     */
    public function checkServiceExpressDelivery()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkServiceExpressDelivery();
    }

    /**
     * Выбор вкладки Обзоры.
     *
     * @throws \Exception
     */
    public function selectReviewsPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->selectReviewsPage();
    }

    /**
     * Переход к новости из обзора, а затем к списку новостей.
     *
     * @throws \Exception
     */
    public function readNewsAsReview()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->readNewsAsReview();
    }

    /**
     * Увелечение количества штук товара для товаров коробками.
     *
     * @param int $itemNumber Номер товара в списке по порядку
     * @throws \Exception
     */
    public function increaseItemBoxValue($itemNumber)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->increaseItemBoxValue($itemNumber);
    }

    /**
     * Уменьшение количества штук товара для товаров коробками.
     *
     * @param int $itemNumber Номер товара в списке по порядку
     * @throws \Exception Если добавлена 1 коробка
     */
    public function reduceItemBoxValue($itemNumber)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->reduceItemBoxValue($itemNumber);
    }

    /**
     * Удаление зарегистрированного пользователя в отдельном окне браузера.
     */
    public function deleteUserByFriend()
    {
        $I = $this;

        $deleteUser = $I->haveFriend('deleteUser');
        $deleteUser->does(function (AcceptanceTester $I) {
            $I->openHomePage();
            $I->disableCaptchaCheck();
            $I->doLogin(USERNAME_REGISTRATION, PASSWORD_REGISTRATION);
            if ($I->getNumberOfElements('//div[@class="message-main middle error"]', 'warning block - wrong pass') > 0) {
                $I->recoveryPassword(PASSWORD_REGISTRATION, PHONE_REGISTRATION);
                $I->doLogin(PHONE_REGISTRATION, PASSWORD_REGISTRATION);
            }
            $I->goToProfile();
            $I->goToProfileAction();
            $I->deleteUser($I->getUserId());
        });
        $deleteUser->leave();
    }

    /**
     * Изменение контактного телефона, с помощью текущего номера телефона.
     * Авторизация по номеру телефона, требуется указывать почту профиля.
     *
     * @param $newPhone
     * @param string $phone Текущий номер телефона в профиле
     * @param bool $resend повторная отправка кода
     * @param string $email Текущая почта в профиле
     * @throws \Exception
     */
    public function editContactPhoneNumber($newPhone, $phone, $email, $resend = false)
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->editContactPhoneNumber($newPhone, $phone, $email, $resend);
    }

    /**
     * Проверка фильтрации по типу.
     *
     * @throws \Exception
     */
    public function checkFilterConfigByType()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->checkFilterByType();
    }

    /**
     * Проверка фильтрации списка конфигураций по цене.
     *
     * @throws \Exception
     */
    public function checkFilterConfigByPrice()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->checkFilterByPrice();
    }

    /**
     * Проверка фильтра по типу платформы.
     *
     * @throws \Exception
     */
    public function checkFilterConfigByCpu()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->checkFilterByCpu();
    }

    /**
     * Проверка сортировки.
     *
     * @throws \Exception
     */
    public function checkSortConfig()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->checkSort();
    }

    /**
     * Снимает фокус с полей ввода.
     */
    public function unFocus()
    {
        $I = $this;

        $I->executeJS("function blurAll(){
            var tmp = document.createElement('input');
            document.body.appendChild(tmp);
            tmp.focus();
            document.body.removeChild(tmp);
        }
        blurAll()");
    }

    /**
     * Заполняет поле и дожидается валидации.
     *
     * @param mixed $field Xpath
     * @param string $value Значение
     * @throws \Exception
     */
    public function fillValid($field, $value)
    {
        $I = $this;

        if (Locator::isXPath($field)) {
            $I->clearField($field);
            $I->appendField($field, $value);
            $this->unFocus();
            if(is_array($field)){
                $key = key($field);
                $I->waitForElement('//span[@class="input input_complete"]/input[@'.$key.'="'.$field[$key].'"]');
            }else{
                $I->waitForElement('//span[@class="input input_complete"]' . $field);
            }
        } else {
            Throw new \Exception('field param should be xpath');
        }
    }

    /**
     * Метод клика на элементе страницы, включает ожидание элемента и вывод его описания
     *
     * @param string $link ссылка на элемент
     * @param string $linkDescription описание элемента
     * @param string $noScroll отключение скроллирования к элементу
     * @throws \Exception
     */
    public function clickClickable($link, $linkDescription = null, $noScroll = null)
    {
        $I = $this;

        if (!is_null($linkDescription)) {
            $I->lookForwardTo("trying to click " . $linkDescription);
        }

        if (is_null($noScroll)) {
            try {
                $I->scrollTo($link);
            } catch ( \Exception $ex) {
                Throw new \Exception('Cannot scroll to ' . $linkDescription);
            }
        }

        $I->waitForElementClickable($link, ELEMENT_WAIT_TIME);
        $I->click($link);
    }

    /**
     * Переход на страницу создания обзора.
     *
     * @throws \Exception
     */
    public function startReview()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->startReview();
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
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->createDraftReview($header, $inner);
    }

    /**
     * Заполнение текстом в div html'а
     *
     * @param string $path Локатор
     * @param string $text Текст
     */
    public function fillByInnerHtml($path, $text)
    {
        $I = $this;

        $I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) use ($path, $text) {
            $elements = $webdriver->findElements(WebDriverBy::xpath($path));
            $webdriver->executeScript("arguments[0].innerHTML = '$text'", $elements);
        });
    }

    /**
     * Получить элемнты страницы.
     *
     * @param string $path Локатор
     * @return array $data логи браузера
     */
    public function getElements($path)
    {
        $I = $this;

        $data = array();
        $I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) use ($path, &$data) {
            $elements = $webdriver->findElements(WebDriverBy::xpath($path));
            foreach ($elements as $element){
                array_push($data, $element->getText());
            }
        });
        codecept_debug($data);
        return $data;
    }

    /**
     * Получение логов браузера.
     *
     * @return mixed $logs логи браузера
     */
    public function getJsLog()
    {
        $I = $this;

        $logs = null;
        $I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) use (&$logs) {
            $logs = $webdriver->manage()->getLog('browser');
        });

        return $logs;
    }

    /**
     * Получение координат элемента.
     *
     * @param string $path Локатор
     * @return mixed $location координаты элемента
     */
    public function getElementLocation($path)
    {
        $I = $this;

        $location = [];
        $I->executeInSelenium(function (\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) use ($path, &$location) {
            $coordinates = $webdriver->findElement(WebDriverBy::xpath($path))->getLocation();
            array_push($location, $coordinates->getX());
            array_push($location, $coordinates->getY());
        });

        return $location;
    }

    /**
     * Переход к списку обзоров.
     *
     * @throws \Exception
     */
    public function goToListingReview()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->goToListingReview();
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
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->goToEditDraftReview($header, $inner);
    }

    /**
     * Удаление конкретного черновика обзора.
     *
     * @param string $header Текст заголовка
     * @throws \Exception
     */
    public function deleteDraftReview($header)
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->deleteDraftReview($header);
    }

    /**
     * Переход на страницу конфигурации.
     *
     * @throws \Exception
     */
    public function goToConfigCard()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->goToConfigCard();
    }

    /**
     * Клик по ссылке на выгрузку конфигурации в Excel.
     *
     * @throws \Exception
     */
    public function downloadConfigExcel()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->downloadConfigExcel();
    }

    /**
     * Проверка добавления и удаления из избранного.
     *
     * @throws \Exception
     */
    public function checkFavoriteConfig()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->checkFavoriteConfig();
    }

    /**
     * Добавление в корзину случайной услуги установки.
     *
     * @return array $serviceData Наименование, цена, id услуги
     * @throws \Exception
     */
    public function addToCartInstallServiceProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->addToCartInstallService();
    }

    /**
     * Получение наименования и стоимости доступных услуг установки.
     *
     * @return array $serviceData Наименование, цена, id услуги
     * @throws \Exception
     */
    public function getInstallServiceProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->getInstallService();
    }

    /**
     * Добавление и удаление из корзины случайной услуги установки.
     *
     * @return mixed Наименование, цена добавленной услуги
     * @throws \Exception
     */
    public function deleteAfterAddFromCartInstallService()
    {
        $I = $this;

        $service = $this->getInstallServiceProductPage();
        codecept_debug($service);
        $selectedService = array_rand($service);
        codecept_debug($selectedService . ' array random');
        codecept_debug($service[$selectedService]);
        $clickService = $selectedService + 1;
        codecept_debug($clickService . ' click service');
        $I->waitAndClick(\Page\Product::XPATH_INSTALL_SERVICE . '[' . $clickService . ']' . \Page\Product::CARDIF_SERVICE,
            'add install service');
        $servicePriceCartBlock = $I->getNumberFromLink('//span[@class="service-price-text"]',
            'service price in cart block');
        codecept_debug($service[$selectedService]['price'] . ' price');
        $I->seeServicePriceEquals($servicePriceCartBlock, $service[$selectedService]['price']);
        $I->wait(SHORT_WAIT_TIME);
        $I->waitAndClick(\Page\Product::XPATH_INSTALL_SERVICE . '[' . $clickService . ']' . \Page\Product::CARDIF_SERVICE,
            'delete install service');

        return $service[$selectedService];
    }

    /**
     * Проверка наличия галочек на СМС оповещениях в подтверждении заказа.
     */
    public function checkSelectedCheckboxes()
    {
        $I = $this;

        $cart = new CartPage($I);
        $cart->checkSelectedCheckboxes();
    }

    /**
     * Восстановление пароля по смс.
     *
     * @param string $newPass Новый пароль
     * @param string $phone Номер телефона
     * @throws Exception
     */
    public function recoveryPassword($newPass, $phone)
    {
        $I = $this;

        $I->disableCaptchaCheck();
        $I->waitAndClick('//span[@id="login_form_show_js"]', 'login/registration link', true);
        $I->waitAndClick('//a[@class="auth-popup__forgot-pass"]', 'recovery pass', true);
        $I->seeCurrentUrlEquals('/forget/#email');
        $I->waitAndClick('//ul[@class="component-tabs-switcher"]/li[@data-target="phone"]', 'recovery bu phone', true);
        $I->submitForm(['xpath' => '//form[@id="reset-by-phone-form"]'], [
            'phone' => $phone,
            'captcha' => 'captcha'
        ], 'submit');
        $I->seeCurrentUrlEquals('/forget/phone/');
        $confirmToken = $I->getRecoveryConfirmToken();
        $smsCode = $I->getConfirmSmsCode($confirmToken);
        $I->submitConfirmSmsCodeForm($smsCode);
        $I->seeInCurrentUrl('/forget/check/hash/');
        $I->submitForm(['xpath' => '//form[@name="newpass"]'], [
            'newpass[pass1]' => $newPass,
            'newpass[pass2]' => $newPass
        ], 'submit');
        $I->see('Пароль успешно установлен');
    }

    /**
     * Получение токена для восстановления пароля.
     *
     * @return mixed Токен
     */
    public function getRecoveryConfirmToken()
    {
        $I = $this;

        $confirmToken = $I->grabValueFrom('//div[@class="inner"]/input[@data-name="smsRequestToken"]');
        $I->seeValueNotEmpty($confirmToken);

        return $confirmToken;
    }

    /**
     * Поиск по списку заказов.
     *
     * @param string $searchString Поисковый запрос
     * @throws \Exception
     */
    public function searchInOrdersList($searchString)
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->searchInOrdersList($searchString);
    }

    /**
     * Фильтрация логов браузера.
     *
     * @param array $array массив лога брауера
     * @param string $level уровень ошибки
     * @param array $sortMessages искомое/ые сообщение/я
     * @return array $result массив с bool на первой позиции и с сообщениями об ошибках
     */
    public function filterBrowserLogs(array $array, $level, $sortMessages)
    {
        $I = $this;
        $bool = true;
        $result = [];
        $result[0] = $bool;

        $I->wait(5);

        if (!empty($array))
        {
            for ($i = 0; $i < count($array); ++$i)
            {
                for ($j = 0; $j < count($sortMessages); ++$j)
                {
                    if (stristr($array[$i]["level"], $level) && stristr($array[$i]["message"], $sortMessages[$j]))
                    {
                        $bool = false;
                        $result[0] = $bool;
                        array_push($result, $array[$i]["message"]);
                        break;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Парсинг и Фильтрация логов браузера на ошибки.
     *
     * @param string $level уровень ошибки
     * @param array $sortMessages искомое/ые сообщение/я в логах
     * @param string $errorMessage сообщение при обнаружении ошибки
     */
    public function seeBrowserLogs($level, $sortMessages, $errorMessage)
    {
        $I = $this;
        $logs = $I->getJsLog();
        $result = $I->filterBrowserLogs($logs, $level, $sortMessages);

        $errors = '';
        for ($i = 1; $i < count($result); ++$i)
        {
            $errors .= $result[$i] . ';  ';
        }

        $I->dontSeeBrowserLogsErrors($result[0], $errorMessage .'  ERROR RESOURCES:: '.$errors);
    }

    /**
     * Переход на случайную страницу акции по ее типу.
     *
     * @param string $action тип акции
     * @throws \Exception
     */
    public function goToRandomActionPageByType($action)
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        $actionPage->goToRandomActionPageByType($action);

    }

    /**
     * Получение всех элементов cтраницы по локатору.
     *
     * @param string $path путь к элементу
     * @return mixed $elements элементы страницы
     */
    public function findAllByXpath($path)
    {
        $I = $this;
        $elements = null;
        $I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) use (&$elements,$path) {
            $elements = $webdriver->findElements(WebDriverBy::xpath($path));
        });

        return $elements;
    }

    /**
     * Добавление комплекта товаров в корзину.
     *
     * @throws \Exception
     */
    public function addItemsSetToCart()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->addItemsSetToCart();
    }

    /**
     * Проверка, что массивы ID товаров равны.
     *
     * @param array $first массив id товаров
     * @param array $second массив id товаров
     * @throws \Exception
     */
    public function checkThatArraysAreEqual($first, $second)
    {
        $I = $this;
         try
         {
             $I->assertEqualsProductsIdCartPage($first, $second);
         }
         catch (Exception $e)
         {
             array_reverse($first);
             $I->assertEqualsProductsIdCartPage($first, $second);
         }
    }

    /**
     * Получение случайных Id товаров из блока комплектов со страницы товара.
     *
     * @return array $array массив id товаров
     */
    public function getRandomItemsSetId()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->getRandomItemsSetId();
    }

    /**
     * Убрать из сборки.
     *
     * @throws \Exception
     */
    public function removeFromConfiguration()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->removeFromConfiguration();
    }

    /**
     * Переход cо "Сборки заказа" на страницу "Способ и адрес доставки".
     *
     * @throws \Exception
     */
    public function goFromAssemblyToDelivery()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->goFromAssemblyToDelivery();
    }

    /**
     * Проверка совместимости конфигурации.
     *
     * @return bool true/false
     * @throws \Exception
     */
    public function checkCompatibilityOfConfig()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        return $bool = $configPage->checkCompatibilityOfConfig();
    }

    /**
     * Добавление совместимой конфигурации.
     *
     * @throws \Exception
     */
    public function addCompatibilityConfig()
    {
        $I = $this;

        $bool = $I->checkCompatibilityOfConfig();

        if ($bool==true) {
            $I->addConfigToCart();
        }
        else {
            $I->moveBack();
            $I->filterConfigByAvailable('Есть все комплектующие');
            $I->goToConfigCard();
            $I->addConfigToCart();
        }
    }

    /**
     * Проверка корректного удаления товара из сборки в корзине.
     *
     * @throws \Exception
     */
    public function checkDeletingPositionFromConfigInTrash()
    {
        $I = $this;

        $I->removeFromConfiguration();
        $I->deleteItemFromCart(1);
        $I->goFromCartToCheckout();
        $I->goFromAssemblyToDelivery();
    }

    /**
     * Выбор фильтрации конфигураций по типу и ее проверка.
     *
     * @param $filter string Наименование типа, либо часть наименования
     *
     * @throws Exception
     */
    public function filterConfigByType($filter)
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->filterByType($filter);
    }

    /**
     * Проверка возможности покупки товаров комплектом.
     *
     * @param string $firstSelfPoint пункт выдачи выбранный при первом заказе
     * @param string $secondSelfPoint пункт выдачи при слудующем заказе, выбранный как дефолтный
     * @param string $thirdSelfPoint пункт выдачи выделенный в списке всех пунктов
     *
     * @throws Exception
     */
    public function checkThatPickPointsAreSame($firstSelfPoint,$secondSelfPoint,$thirdSelfPoint)
    {
        if($firstSelfPoint != $secondSelfPoint || $firstSelfPoint != $thirdSelfPoint){
            Throw new \Exception("Pick up point not same: first '.$firstSelfPoint.',second: '.$secondSelfPoint.',third: '.$thirdSelfPoint");
        }
    }

    /**
     * Проверка фильтрации акций.
     *
     * @param $filter string тип акции
     *
     * @throws Exception
     */
    public function checkActionsFilter($filter)
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        $actionPage->checkActionsFilter($filter);
    }

    /**
     * Получение всех товаров из списка на странице и проверка на соответствие.
     *
     * @param $categoryName string имя категории товаров
     * @throws \Exception
     */
    public function checkItemsCategoryFromListing($categoryName)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->getItemsFromListing($categoryName);
    }

    /**
     * Проверка отображения листа доверенности.
     *
     * @throws \Exception
     */
    public function checkB2bProcuration()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->checkB2bProcuration();
    }

    /**
     * Получение размера скидки на товар.
     *
     * @return int discount процент/размер скидки
     */
    public function getItemsDiscount()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->getItemsDiscount();
    }

    /**
     * Проверка соответсвия размера скидки в процентах по акции.
     *
     * @param int или array $firstDiscount процент или величина скидки выбранная на странице акции (диапозон, если массив)
     * @param int $secondDiscount процент или величина скидки со страницы товара
     * @param int $thirdDiscount процент или величина скидки в корзине
     *
     * @throws Exception
     */
    public function checkActionsPromoDiscount($firstDiscount, $secondDiscount, $thirdDiscount)
    {
        codecept_debug($firstDiscount);
        codecept_debug($secondDiscount);
        codecept_debug($thirdDiscount);

        if (is_string($firstDiscount) && empty($secondDiscount)){
            if ((int)$firstDiscount !== (int)$thirdDiscount){
                Throw new \Exception('Does not match the size of the discount');
            }
        }elseif(is_string($firstDiscount)){
            if ((int)$firstDiscount !== (int)$secondDiscount || (int)$firstDiscount !== (int)$thirdDiscount){
                Throw new \Exception('Does not match the size of the discount');
            }
        }else{
            if ((int)$firstDiscount[0] >= (int)$secondDiscount || (int)$secondDiscount >= (int)$firstDiscount[1] || (int)$firstDiscount[0] >= (int)$thirdDiscount || (int)$thirdDiscount >= (int)$firstDiscount[1]){
                Throw new \Exception('Does not match the size of the discount');
            }
        }
    }

    /**
     * Расчет процента скидки в корзине.
     *
     * @param int $cartAmountForPayment сумма покупки без скидки
     * @param int $cartDiscountAmount сумма покупки со скидкой
     * @param int $firstDiscount процент или величина скидки из описания или со страницы акции
     *
     * @return int $discount процент или величина скидки из корзины
     */
    public function calculateDiscount($cartAmountForPayment, $cartDiscountAmount, $firstDiscount)
    {
        if (strlen($firstDiscount)>2){
            $discount = $cartDiscountAmount;
        }else{
            $onePercent = $cartAmountForPayment/100;
            $discount = round($cartDiscountAmount/$onePercent);
        }
        codecept_debug($discount.'посчитанная скидка');
        return $discount;
    }

    /**
     * Получение суммы скидки в корзине.
     *
     * @return int $discount сумма скидки в корзине
     */
    public function getDiscountFromCart()
    {
        $I = $this;

        $cartPage = new cartPage($I);
        return $cartPage->getDiscount();
    }

    /**
     * Добавление текста в буфер обмена.
     *
     * @param $text string текст в буфер обмена
     */
    public function addTextToClipboard($text)
    {
        $I = $this;

        $I->executeJS("navigator.clipboard.writeText('".$text."')");
    }

    /**
     * Чтение текста из буфера обмена.
     */
    public function readTextFromClipboard()
    {
        $I = $this;

        $text = $I->executeJS("navigator.clipboard.readText()");
        codecept_debug($text);
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
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkPhoneMaskValidationForPaste($name, $familyName, $phone);
    }

    /**
     * Проверка списка желаний на видимость для других пользователей.
     *
     * @param array $wishListItems товары из списка желаний
     * @param string $wishListLink ссылка на список желаний
     * @param string $visibility установленная видимость
     * @param string $turnOff закрытие окна true
     */
    public function checkWishListForVisibility($wishListLink, $visibility, $wishListItems = null, $turnOff = null)
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->checkWishListForVisibility( $wishListLink, $visibility, $wishListItems, $turnOff);
    }

    /**
     * Получение всех товаров из списка желаний.
     *
     * @param mixed $wishListXpath ссылка на список товаров
     * @return array список товаров в желаниях
     */
    public function getItemsFromWishList($wishListXpath)
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        return $profilePage->getItemsFromWishList($wishListXpath);
    }

    /**
     * Получение сылок на ресурсы со статики.
     *
     * @return array $resources массиы с датой и временем ссылок
     */
    public function getStaticResourceLinks()
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->getStaticResourceLinks();
    }

    /**
     * Проверка что все элементы массива равны.
     *
     * @param array @$array массив данных
     * @throws Exception
     */
    public function checkArrayUnique($array)
    {

        $I = $this;

        $result = array_unique($array);

        if (count($result)>1){
            Throw new \Exception("Resources have different timestamp");
        }
        $I->wait(1);

        codecept_debug($result);
    }

    /**
     * Проверка навигации хлебных крошек.
     *
     * @throws \Exception
     */
    public function checkAmpNavigation()
    {
        $I = $this;

        $ampPage = new AmpPage($I);
        $ampPage->checkAmpNavigation();
    }

    /**
     * Проверка навигации хлебных крошек.
     *
     * @throws \Exception
     */
    public function checkAmpListingPagination()
    {
        $I = $this;

        $ampPage = new AmpPage($I);
        $ampPage->checkListingPagination();
    }

    /**
     * Выбор случайного тега в листинге.
     *
     * @return string $tagName название выбранного тега
     */
    public  function selectRandomTag()
    {
        $I = $this;

        $ampPage = new AmpPage($I);
        return $ampPage->selectRandomTag();
    }

    /**
     * Получение всех товаров из списка на странице и проверка на соответствие.
     *
     * @param string $subCategory название подкатегории
     *
     * @throws \Exception если имеется товар не из данной подкатегории
     */
    public function checkItemsCategoryFromAmpListing($subCategory)
    {
        $I = $this;

        $ampPage = new AmpPage($I);
        $ampPage->checkItemsCategoryFromAmpListing($subCategory);
    }

    /**
     * Получение значения user-agent.
     *
     * @throws \Exception
     */
    public function getUserAgent()
    {
        $config = \Codeception\Configuration::config();
        $user_agent = $config['WebDriver']['capabilities']['chromeOptions']['args']['--user_agent'];

        codecept_debug($user_agent);
    }

    /**
     * Выбор случайной подкатегории товаров из каталога уцененных товаров.
     *
     * @throws \Exception
     */
    public function goToDiscountListing()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->goToDiscountListing();
    }

    /**
     * Получение всех товаров из списка на странице и проверка наличия плашки "уценка".
     *
     * @throws \Exception если у товара в листинге нет уценки
     */
    public function checkItemsForDiscountFromListing()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkItemsForDiscountFromListing();
    }

    /**
     * Получение размера скидки на товар.
     *
     * @return int $discountCount процент или размер скидки
     * @throws \Exception
     */
    public function checkProductDiscountInfo()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->checkProductDiscount();
    }

    /**
     * Проверка что выбран стандартный вид доставки по умолчанию.
     */
    public function checkDefaultKindOfDelivery()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkDefaultKindOfDelivery();
    }

    /**
     * Набор комплектующих для конфигурации.
     *
     * @throws \Exception
     */
    public function addAccessoriesForConfiguration()
    {
        $I = $this;

        $I->goToListing(2,2);
        $I->moveBack();
        $I->addToCartFromCatalog(2,2);

        for ($i = 4; $i <= 10; $i++) {
            $I->addToCartFromCatalog(1,$i);
        }
    }

    /**
     * Покупка конфигурации в сборке".
     *
     * @throws \Exception
     */
    public function buyConfigurationInAssembly()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->buyConfigurationInAssembly();
    }

    /**
     * Получение количества адресов магазинов и пунктов выдачи.
     *
     * @return array $addresses массив с количеством магазинов и пунктов выдачи
     * @throws \Exception
     */
    public function getShopAndPickPointCount()
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->getShopAndPickPointCount();
    }

    /**
     * Получение id  товара из листинга.
     *
     * @param int $sectionNum Порядковый номер секции в листинге
     * @param int $itemNum Порядковый номер товара в секции листинга
     * @return string $itemId id товара из листинга
     * @throws \Exception
     */
    public function getItemIdFromListing($sectionNum = null, $itemNum = null)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->getItemId($sectionNum, $itemNum);
    }

    /**
     * Переход на вкладку "сертификаты" в карточке товара.
     *
     * @throws \Exception
     * @return int $certificate наличие сертификата в КТ
     */
    public function goToCertificatesTab()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->goToCertificatesTab();
    }

    /**
     * Выбор вкладки "сопутствующие товара" на странице товара.
     *
     * @throws \Exception
     */
    public function selectRelatedProducts()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->selectRelatedProducts();
    }

    /**
     * Скачивание "сертификата".
     *
     * @return string $fileName имя скачиваемго файла
     * @throws \Exception
     */
    public function downloadCertificate()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->downloadCertificate();
    }

    /**
     * Проверка наличия загруженного файла.
     *
     * @throws \Exception
     */
    public function getDownloadedInfo()
    {
        $I = $this;

        $I->amOnUrl('chrome://downloads/');
        $I->waitForElementVisible('//downloads-manager');
        $downloads = $I->grabTextFrom('//downloads-manager');
        codecept_debug($downloads);

        if (stristr($downloads,'Повторить попытку') || !stristr($downloads, 'items-certificates')){
            Throw new \Exception('file was not download' );
        }
    }

    /**
     * Выбор услуги "экспресс сборка" на странице конфигурации.
     *
     * @return int $service наличие услуги
     * @throws \Exception
     */
    public function chooseServiceExpressInstall()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        return $configPage->chooseExpressInstall();
    }

    /**
     * Выбор услуги "купить без сборки" на странице конфигурации.
     *
     * @return int $service наличие услуги
     * @throws \Exception
     */
    public function chooseServiceWithoutInstall()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        return $configPage->chooseWithoutInstall();
    }

    /**
     * Проверка услуги сборки конфигурации в корзине.
     *
     * @param string $serviceName навзвание услуги сборки
     * @throws \Exception
     */
    public function checkConfigurationService($serviceName)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkConfigurationService($serviceName);
    }

    /**
     * Переход в корзину через поп ап (при выборе услуги "без сборки") на странице конфигурации.
     *
     * @throws \Exception
     */
    public function goToCartFromPopUp()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->goToCartFromPopUp();
    }

    /**
     * Получить id всех комплектующих сборки.
     *
     * @return array $allId список всех id комплектующих
     * @throws \Exception
     */
    public function getAllId()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        return $configPage->getAllId();
    }

    /**
     * Переход на страницу создания конфигурации.
     */
    public function goToConfigurationCreation()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->goToConfigurationCreation();
    }

    /**
     * Поиск комплектующих по id.
     *
     * @param string $id айди комплектующего
     * @throws \Exception
     */
    public function addConfigComponents($id)
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->addConfigComponents($id);
    }

    /**
     * Добавить конфигурацию в корзину.
     *
     * @throws \Exception
     */
    public function addConfigurationToCart()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->addConfigurationToCart();
    }

    /**
     * Переход на страницу сервисные центры.
     *
     * @throws \Exception
     */
    public function goToServiceCenterPage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToServiceCenterPage();
    }

    /**
     * Выбор города на странице сервисные центры.
     *
     * @return string $city выбранный город
     * @throws \Exception
     */
    public function selectCityOnServiceCenterPage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->selectCityOnServiceCenterPage();
    }

    /**
     * Выбор категории на странице сервисные центры.
     *
     * @return string $category выбранную категорию
     * @throws \Exception
     */
    public function selectCategoryOnServiceCenterPage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->selectCategoryOnServiceCenterPage();
    }

    /**
     * Выбор бренда на странице сервисные центры.
     *
     * @return string $brand выбранный бренд
     * @throws \Exception
     */
    public function selectBrandOnServiceCenterPage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->selectBrandOnServiceCenterPage();
    }

    /**
     * Получение количества сервисных центров.
     *
     * @return string $result количество сервисных центров
     * @throws \Exception
     */
    public function checkResultOnServiceCenterPage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->checkResultOnServiceCenterPage();
    }

    /**
     * Поиск сервисного центра по айди товара.
     *
     * @param string $itemId айди товара
     * @throws \Exception
     */
    public function searchServiceCenterAddressByItemId($itemId)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->searchServiceCenterAddressByItemId($itemId);
    }

    /**
     * Проверка наличия иконок на странице каталога уцененных товаров.
     *
     * @throws \Exception
     */
    public function checkIconsInDiscountedCatalogPage()
    {
        $I = $this;

        $catalogPage = new CatalogPage($I);
        $catalogPage->checkIconsInDiscountedCatalogPage();
    }

    /**
     * Переход на страницу товара из ротатора "популярные товары в категории".
     *
     * @return string $itemId id товара из листинга
     * @throws \Exception
     */
    public function goToProductCartFromListingRotator()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->goToProductCartFromListingRotator();
    }

    /**
     * Получить айди товаров из корзины.
     *
     * @return array $itemsId
     */
    public function getItemsIdFromCart()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->getItemsId();
    }

    /**
     * Переход на промо страницу акции.
     *
     * @return int $promo наличе промо страницы
     * @throws \Exception
     */
    public function goToPromoPage()
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        return $actionPage->goToPromoPage();
    }

    /**
     * Проверка наличия категорий на промо странице.
     *
     * @return int $categoriesCount
     */
    public function checkCategoriesOnPromoPage()
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        return $actionPage->checkCategoriesOnPromoPage();
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
        $I = $this;

        $actionPage = new ActionPage($I);
        return $actionPage->choosePromoCategory($categoriesCount);
    }

    /**
     * Получение номера случайного товара.
     *
     * @param string $info переход в КТ отзывом, обзором или рейтингом
     * @return array $itemData номер секции и порядковый номер товара
     * @throws \Exception
     */
    public function getRandomItemNumberInListing($info = null)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->getRandomItemNumber($info);
    }

    /**
     * Получение точек наличия товара в листинге.
     *
     * @param int $itemNum номер товара
     * @return array $itemStockStores точки наличия товара
     * @throws \Exception
     */
    public function getProductStockStores($itemNum)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->getProductStockStores($listingPage::XPATH_PRODUCT_ITEM.'['.$itemNum.']'.CartPage::XPATH_STOCKS_LIST);
    }

    /**
     * Получение точек наличия товара в КТ.
     *
     * @return array $itemStockStores точки наличия товара
     * @throws \Exception
     */
    public function getStockStoresOnProductPage()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->getProductStockStores(\Page\Product::XPATH_STOCKS_LIST);
    }

    /**
     * Получение точек наличия товара.
     *
     * @return array $itemStockStores точки наличия товара
     * @throws \Exception
     */
    public function getStockStoresOnCartPage()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->getProductStockStores(CartPage::XPATH_STOCKS_LIST);
    }

    /**
     * Выбор фильтра промокоды на странице акций.
     *
     * @throws \Exception
     */
    public function setPromoCodeFilter()
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        $actionPage->setPromoCodeFilter();
    }

    /**
     * Добавление комплекта товаров в корзину со страницы акции.
     *
     * @throws \Exception
     */
    public function addItemsSetToCartFromAction()
    {
        $I = $this;

        $actionPage = new ActionPage($I);
        return $actionPage->addItemsSetToCart();
    }

    /**
     * Проверка адреса доставки на страницы подтверждения заказа.
     *
     * @return string $address
     */
    public function checkShippingAddressOnConfirmation()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->checkShippingAddressOnConfirmation();
    }

    /**
     * Проверка наличия услуг сборки в корзине.
     *
     * @return bool $serviceCount наличие услуг сборки
     */
    public function checkCartForInstallService()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->checkCartForInstallService();
    }

    /**
     * Проверка наличия информации о доставке только по телефону или в пункте выдачи.
     *
     * @throws \Exception
     */
    public function checkDeliveryByPhoneOrPickPoint()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkDeliveryByPhoneOrPickPoint();
    }

    /**
     * Проверка результатов поиска на форуме.
     *
     * @param string $searchText текст поискового запроса
     * @throws \Exception
     */
    public function checkForumSearchResult($searchText)
    {
        $I = $this;

        $forumPage = new Forum($I);
        $forumPage->checkSearchResult($searchText);
    }

    /**
     * Переход на страницу сообщения в форуме.
     *
     * @throws \Exception
     */
    public function goToMessageOnForumPage()
    {
        $I = $this;

        $forumPage = new Forum($I);
        $forumPage->goToMessage();
    }

    /**
     * Отправка цитаты на сообщение в форуме.
     *
     * @throws \Exception
     */
    public function sendCitationOnForum()
    {
        $I = $this;

        $forumPage = new Forum($I);
        $forumPage->sendCitation();
    }

    /**
     * Удаление сообщения на форуме.
     *
     * @throws \Exception
     */
    public function deleteMessageOnForum()
    {
        $I = $this;

        $forumPage = new Forum($I);
        $forumPage->deleteMessage();
    }

    /**
     * Отправка сообщения на форуме.
     *
     * @throws \Exception
     */
    public function sendMessageOnForum()
    {
        $I = $this;

        $forumPage = new Forum($I);
        $forumPage->sendMessage();
    }

    /**
     * Отправка личного сообщения на форуме.
     *
     * @throws \Exception
     */
    public function sendPrivateMessageOnForum()
    {
        $I = $this;

        $forumPage = new Forum($I);
        $forumPage->sendPrivateMessage();
    }

    /**
     * Выбор сообщения админа на форуме.
     *
     * @return int $messagesCount
     * @throws \Exception
     */
    public function chooseForumAdminUserMessage()
    {
        $I = $this;

        $forumPage = new Forum($I);
        return $forumPage->chooseAdminUserMessage();
    }

    /**
     * Проверка закладок на странице товара.
     *
     * @throws \Exception
     */
    public function checkTabsVisibilityOnProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->checkTabsVisibility();
    }

    /**
     * Проверка корректного сохранения адресса.
     *
     * @throws \Exception
     */
    public function checkSaveAddress()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->checkSaveAddress();
    }

    /**
     * Проверка полей контактных данных вкорзине для неавторизованного пользователя.
     *
     * @throws \Exception
     */
    public function checkContactInputs()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->checkContactInputs();
    }

    /**
     * Получить размеры элемента.
     *
     * @param string $selector css селектор
     * @return array $size высота и ширина
     */
    public function getElementSize($selector)
    {
        $I = $this;

        $size = [];

        $height = $I->executeJS('return document.querySelector("'.$selector.'").clientHeight');
        $width = $I->executeJS('return document.querySelector("'.$selector.'").clientWidth');
        array_push($size, $height);
        array_push($size, $width);

        return $size;
    }

    /**
     * Проверка баннера с рекомендуемым товаром.
     *
     * @throws \Exception
     */
    public function checkRecommendBanner()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->checkRecommendBanner();
    }

    /**
     * Получение количества товара в корзине.
     *
     * @return int $productCountFromCart количество товара в корзине
     */
    public function getProductCountFromCart()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->getProductCount();
    }

    /**
     * Подтверждение использования бонусов по смс коду.
     *
     * @param string $phone номер телефона
     * @param bool $resend повторная отправка кода
     * @throws \Exception
     */
    public function confirmUseBonuses($phone, $resend = false)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->confirmUseBonuses($phone, $resend);
    }

    /**
     * Отмена использования бонусов.
     *
     * @throws \Exception
     */
    public function denyBonuses()
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->denyBonuses();
    }

    /**
     * Проверка наличия и корректного перехода по кнопке подтверждения телефона\почты.
     *
     * @throws \Exception
     */
    public function goToEmailConfirmation()
    {
        $I = $this;

        $loginPage = new LoginPage($I);
        $loginPage->goToEmailConfirmation();
    }

    /**
     * Проверка уязвимости open redirect.
     *
     * @param string $expectedUrl ожидаемый url
     * @throws \Exception
     */
    public function checkOpenRedirect($expectedUrl)
    {
        $I = $this;

        $currentUrl = $I->getFullUrl();
        if (stristr($currentUrl, '?') == true){
            $currentUrl = strstr($currentUrl, '?', true);
        }
        $I->seeThatUrlsAreEquals($expectedUrl, $currentUrl, 'expected url', 'actual url');
    }

    /**
     * Переход на форму регистрацию компании.
     *
     * @throws \Exception
     */
    public function goToCompanyRegistrationForm()
    {
        $I = $this;

        $b2bPage = new B2B($I);
        $b2bPage->goToCompanyRegistrationForm();
    }

    /**
     * Проверка формы регистрации компании на валидность полей.
     *
     * @param string $legalForm правовая форма (ip, legal, state, bank)
     * @throws \Exception
     */
    public function fillRegistrationForm($legalForm)
    {
        $I = $this;

        $b2bPage = new B2B($I);
        $b2bPage->fillRegistrationForm($legalForm);
    }

    /**
     * Проверка формы регистрации компании на сообщения об ошибках.
     *
     * @throws \Exception
     */
    public function checkRegistrationFormForErrorMessage()
    {
        $I = $this;

        $b2bPage = new B2B($I);
        $b2bPage->checkRegistrationFormForErrorMessage();
    }

    /**
     * Проверка отсутствия горизонтального скролла на странице.
     *
     * @throws \Exception
     */
    public function notSeeHorizontalScroll()
    {
        $I = $this;

        $scroll = $I->executeJS('return document.documentElement.scrollWidth');
        $client = $I->executeJS('return document.documentElement.clientWidth');
        $I->seeValuesAreEquals($client, $scroll);
    }

    /**
     * Проверка отсутствия горизонтального скролла на странице.
     *
     * @throws \Exception
     */
    public function checkHorizontalScrollNotVisible()
    {
        $I = $this;

        $I->notSeeHorizontalScroll();
        $I->openMenuCategory();
        $I->notSeeHorizontalScroll();
    }

    /**
     * Переход на страницу из футера.
     *
     * @param string $page страница для перехода
     * @throws \Exception
     */
    public function openPageFromFooter($page)
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->openPageFromFooter($page);
    }

    /**
     * Проверка названия страницы бренда.
     *
     * @param string $expectedName ожидаемое название бренда
     * @throws \Exception
     */
    public function checkBrandName($expectedName)
    {
        $I = $this;

        $brandsPage = new Brands($I);
        $brandsPage->checkBrandName($expectedName);
    }

    /**
     * Переход на вкладку "Электронные лицензии и подписки".
     */
    public function goToLicensesAndSubscribes()
    {
        $I = $this;

        $b2bPage = new B2B($I);
        $b2bPage->goToLicensesAndSubscribes();
    }

    /**
     * Проверка отображения Velvica Iframe.
     */
    public function checkVelvicaIframe()
    {
        $I = $this;

        $b2bPage = new B2B($I);
        $b2bPage->checkVelvicaIframe();
    }

    /**
     * Добавление услуги "защита от негарантийных случаев".
     *
     * @return array
     * @throws \Exception
     */
    public function addProtectionFromProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->addInsuranceServices(2);
    }

    /**
     * Удаление услуг из категории "дополнительные" в корзине.
     *
     * @param array $serviceId Id Услуги
     * @param string $productId айди товара
     * @throws \Exception
     */
    public function deleteExtraServiceFromCart($serviceId, $productId)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->deleteExtraService($serviceId, $productId);
    }

    /**
     * Удаление цифровой услуги из корзины.
     *
     * @param string $serviceId Id Услуги
     * @throws \Exception
     */
    public function deleteDigitalServiceFromCart($serviceId)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->deleteDigitalService($serviceId);
    }

    /**
     * Добавление услуги "продление гарантии".
     *
     * @return array
     * @throws \Exception
     */
    public function addWarrantyExtensionFromProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->addInsuranceServices(1);
    }

    /**
     * Добавление услуги "Комбо страховка".
     *
     * @return array
     * @throws \Exception
     */
    public function addComboInsuranceFromProductPage()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->addInsuranceServices(0);
    }

    /**
     * Проверка кода ответа сервера.
     *
     * @throws \Exception
     */
    public function checkResponseCode()
    {
        $I = $this;

        $options =
            [
                'http' => [
                    'protocol_version' => '1.1',
                    'method' => 'GET',
                ]
            ];

        $context = stream_context_create($options);
        $url = $I->getFullUrl();
        $headers = get_headers($url, null, $context);
        list($proto, $code, $descr) = explode(' ', $headers[0], 3);
        var_dump($proto);
        var_dump($code);
        var_dump($descr);
        $I->seeValuesAreEquals('200', $code);
    }

    /**
     * Изменение диапазона цен.
     *
     * @param string $minPrice минимальная цена
     * @param string $maxPrice максимальная цена
     * @throws \Exception
     */
    public function changePriceRangeInListing($minPrice = null, $maxPrice = null)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->changePriceRange($minPrice, $maxPrice);
    }

    /**
     * Отпарвка формы "сообщить о низкой цене".
     *
     * @param int $value цена товара меньшей стоимости
     * @param string $link ссылка на товар меньшей стоимости
     * @param bool $auth авторизованный пользователь
     * @throws \Exception
     */
    public function reportLowPrice($value, $link, $auth)
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->reportLowPrice($value, $link, $auth);
    }

    /**
     * Переход и проверка корректной ссылки на промо "Гарантия лучшей цены".
     *
     * @throws \Exception
     */
    public function checkGuaranteeBestPricePromo()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->checkGuaranteeBestPricePromo();
    }

    /**
     * Проверка что "Гарантия лучшей цены" не отображается.
     *
     * @throws \Exception
     */
    public function checkGuaranteeBestPriceNotVisible()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->checkGuaranteeBestPriceNotVisible();
    }

    /**
     * Получение списка ай ди товаров с главной страницы.
     *
     * @param int $length количество значений массива
     * @return array $itemsId список айди товаров
     * @throws \Exception
     */
    public function getItemsIdFromHomePage($length = null)
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->getItemsIdFromHomePage($length);
    }

    /**
     * Получение списка ай ди товаров.
     *
     * @return array $itemsId список айди товаров
     * @throws \Exception
     */
    public function getItemsIdFromSearch()
    {
        $I = $this;

        $searchPage = new SearchPage($I);
        return $searchPage->getItemsIdFromSearch();
    }

    /**
     * Проверка ссылок на картинку и страницу товара.
     *
     * @throws \Exception
     */
    public function checkItemsImageUrlFastSearch()
    {
        $I = $this;

        $searchPage = new SearchPage($I);
        $searchPage->checkItemsImageUrl();
    }

    /**
     * Изменение количества товара.
     *
     * @param int $value количество товара
     * @return int $quantity количество товара
     * @throws \Exception
     */
    public function changeQuantityAtProductPage($value)
    {
        $I = $this;

        $productPage = new ProductPage($I);
        return $productPage->changeQuantity($value);
    }

    /**
     * Проверка изменения цен при увеличении количества товара.
     *
     * @param int $value количество товара
     * @throws \Exception
     */
    public function checkChangeItemQuantity($value)
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->checkChangeItemQuantity($value);
    }

    /**
     * Добавить коплектующий в сборку по кнопке "выбрать для сборки".
     *
     * @param string $id айди комплектующего
     * @throws \Exception
     */
    public function addToConfigurationFromProductPage($id)
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->addToConfiguration($id);
    }

    /**
     * Добавление программы страхования в корзину.
     *
     * @return array $serviceData выбранная программа страхования
     * @throws \Exception
     */
    public function addProtectionServiceToCart()
    {
        $I = $this;

        $aboutPage = new About($I);
        return $aboutPage->addProtectionServiceToCart();
    }

    /**
     * Переход в корзину по кнопке "в корзине" со страницы страхования.
     *
     * @throws \Exception
     */
    public function goToCartByButtonFromPropertyProtection()
    {
        $I = $this;

        $aboutPage = new About($I);
        $aboutPage->goToCartByButton($aboutPage::XPATH_PROTECTION_SERVICE_BLOCK);
    }

    /**
     * Получение наименования добавленной цифровой услуги на стадиии сборки заказа.
     *
     * @param string $serviceId Id добавленной услуги
     * @return string Наименование услуги
     */
    public function getAddedDigitalServiceNameAtOrderAssembly($serviceId)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->getAddedDigitalServiceName($serviceId);
    }

    /**
     * Получение стоимости услуги на стадиии сборки заказа.
     *
     * @param string $serviceId Id услуги
     * @return string Стоимость услуги
     */
    public function getServicePriceAtOrderAssembly($serviceId)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        return $cartPage->getServicePriceAtOrderAssembly($serviceId);
    }

    /**
     * Сравнение данных добавленной услуги в корзине на стадиии сборки заказа.
     *
     * @param array $serviceData данные об услуге
     * @throws \Exception
     */
    public function compareServiceDataAtOrderAssembly($serviceData)
    {
        $I = $this;

        $cartPage = new CartPage($I);
        $cartPage->compareServiceDataAtOrderAssembly($serviceData);
    }

    /**
     * Переход на страницу брендов.
     *
     * @throws \Exception
     */
    public function goToBrands()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->goToBrands();
    }

    /**
     * Выбор категории товаров на странице цифровых услугах.
     *
     * @throws \Exception
     */
    public function chooseDigitalServiceItemsCategory()
    {
        $I = $this;

        $aboutPage = new About($I);
        $aboutPage->chooseDigitalServiceItemsCategory();
    }

    /**
     * Добавление цифровой услуги в корзину со страницы цифровых услуг.
     *
     * @return array $serviceData выбранная цифровая услуга
     * @throws \Exception
     */
    public function addDigitalServiceToCart()
    {
        $I = $this;

        $aboutPage = new About($I);
        return $aboutPage->addDigitalServiceToCart();
    }

    /**
     * Переход в корзину по кнопке "в корзине" со страницы цифровых услуг.
     *
     * @throws \Exception
     */
    public function goToCartByButtonFromDigitalServicePage()
    {
        $I = $this;

        $aboutPage = new About($I);
        $aboutPage->goToCartByButton(\Page\About::XPATH_DIGITAL_SERVICE_BLOCK);
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
        $I = $this;

        $loginPage = new LoginPage($I);
        $loginPage->doLoginFail($username, $password);
    }

    /**
     * Определение текущего времени - часы.
     *
     * @return string $hour Текущий час
     */
    public function currentHour()
    {
        if (function_exists('date_default_timezone_set')) date_default_timezone_set('Europe/Moscow');
        $hour = date("H");
        codecept_debug('current hour is ' . $hour);
        return $hour;
    }

    /**
     * Получить название текущего города.
     *
     * @return string $city текущий город
     * @throws \Exception
     */
    public function getCurrentCity()
    {
        $I = $this;

        $homePage = new HomePage($I);
        return $homePage->getCurrentCity();
    }

    /**
     * Полная очистка поля ввода.
     *
     * @param $field mixed поле ввода
     */
    public function clearFieldWithAttribute($field)
    {
        $I = $this;

        $repeat = 0;
        do {
            $string = $I->grabTextFrom($field);
            codecept_debug($string);
            $value = $I->grabAttributeFrom($field, 'value');
            codecept_debug($value);
            $I->pressKey($field, array('ctrl', 'a'), \WebDriverKeys::BACKSPACE);
            $I->clearField($field);
            $repeat++;
        }while ((!empty($string) || !empty($value)) && $repeat < 6);
    }

    /**
     * Добавление в корзину товара по суперцене.
     *
     * @throws \Exception
     */
    public function addSpecialPriceItemFromMainPage()
    {
        $I = $this;

        $homePage = new HomePage($I);
        $homePage->addSpecialPriceItem();
    }

    /**
     * Добавить обзор.
     *
     * @throws \Exception
     */
    public function addReviewForProduct()
    {
        $I = $this;

        $productPage = new ProductPage($I);
        $productPage->addReview();
    }

    /**
     * Получить идентификатор обзора.
     *
     * @return string $revId
     */
    public function getReviewId()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        return $profilePage->getReviewId();
    }

    /**
     * Переход на вкладку обзоры в профиле.
     *
     * @throws \Exception
     */
    public function goToReviewsTabAtProfile()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        $profilePage->goToReviewsTab();
    }

    /**
     * Проверка наличия обзоров в профиле.
     *
     * @return array $reviewLinks
     * @throws \Exception
     */
    public function checkActiveReviewsAtProfile()
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        return $profilePage->checkActiveReviews();
    }

    /**
     * Получить идентификатора обзора из ссылки со страницы листинга обзоров.
     *
     * @param string $link
     * @return string $revId
     */
    public function getReviewIdFromListing($link)
    {
        $I = $this;

        $profilePage = new ProfilePage($I);
        return $profilePage->getReviewIdFromListing($link);
    }

    /**
     * Удалить все обзоры со страницы листинга обзоров в профиле.
     *
     * @param array $reviewLinks
     * @throws \Exception
     */
    public function deleteAllReviewsInProfile($reviewLinks)
    {
        $I = $this;

        $userId = $I->getUserId();
        foreach ($reviewLinks as $reviewLink){
            $revId = $I->getReviewIdFromListing($reviewLink);
            $I->deleteReview($userId, $revId);
        }
    }

    /**
     * Проверка SEO фильтров.
     *
     * @throws \Exception Если выбранный фильтр не соответсвует бренду в хлебных крошках.
     */
    public function checkSeoFilter()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->checkSeoFilter();
    }

    /**
     * Нажать "использовать для новой сборки".
     *
     * @throws \Exception
     */
    public function useForNewConfig()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->useForNewConfig();
    }

    /**
     * Сохранить черновик сборки.
     *
     * @param string $configType черновик 'Draft'
     * @throws \Exception
     */
    public function saveConfig($configType = null)
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->saveConfig($configType);
    }

    /**
     * Получить id сборки.
     *
     * @return string $id
     * @throws \Exception
     */
    public function getConfigId()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        return $configPage->getConfigId();
    }

    /**
     * Переход в список моих конфигураций.
     *
     * @throws \Exception
     */
    public function goToMyConfigList()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->goToMyConfigList();
    }

    /**
     * Проверить наличие конфигурации в списке своих конфигураций.
     *
     * @param string $id номер сборки
     * @throws \Exception
     */
    public function checkConfigAvailabilityInMyList($id)
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->checkConfigAvailabilityInMyList($id);
    }

    /**
     * Удаление конфигураций из своего списка.
     *
     * @throws \Exception
     */
    public function deleteConfigFromMyList()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->deleteConfigFromMyList();
    }

    /**
     * Выбор группы компонентов.
     *
     * @param string $group
     * @throws \Exception
     */
    public function chooseComponentGroup($group)
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->chooseComponentGroup($group);
    }

    /**
     * Добавить коплектующий в сборку по кнопке "выбрать для сборки".
     *
     * @return string $id айди комплектующего
     * @throws \Exception
     */
    public function addToConfigurationFromListing()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->addToConfiguration();
    }

    /**
     * Сохранить конфигурацию в список своих из общего списка конфигураций.
     *
     * @throws \Exception
     */
    public function saveToMyConfigList()
    {
        $I = $this;

        $configPage = new ConfigPage($I);
        $configPage->saveToMyConfigList();
    }

    /**
     * Сделать первую букву в строке заглавной.
     *
     * @param string $word слово для преобразования
     * @return string $changedWord измененнное слово
     */
    public function upFirst($word)
    {
        $first =  mb_strtoupper(mb_substr($word,0,1, 'UTF-8'));//первая буква
        $last = mb_substr($word,1);//все кроме первой буквы

        return $changedWord = $first.$last;
    }

    /**
     * Получение заголовков реквестов.
     */
    public function getRequest()
    {
        $I = $this;

        $I->wait(MIDDLE_WAIT_TIME);
        $headers = get_headers('https://www.google-analytics.com/', 1);
        codecept_debug($headers);
    }

    /**
     * Получение размеров элемента страницы.
     *
     * @param string $path путь к элементу
     * @return mixed $size размер элемента страницы
     */
    public function findSizeByXpath($path)
    {
        $I = $this;
        $size = null;
        $I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) use (&$size,$path) {
            $size = $webdriver->findElement(WebDriverBy::xpath($path))->getSize();
        });

        return $size;
    }

    /**
     * Получение координат элемента страницы.
     *
     * @param string $path путь к элементу
     * @return mixed $point координаты элемента страницы
     */
    public function findLocationByXpath($path)
    {
        $I = $this;
        $point = null;
        $I->executeInSelenium(function(\Facebook\WebDriver\Remote\RemoteWebDriver $webdriver) use (&$point,$path) {
            $point = $webdriver->findElement(WebDriverBy::xpath($path))->getLocation();
        });

        return $point;
    }

    /**
     * Вычисление позиции элемента на скриншоте.
     *
     * @param array $location позиция элемента на странице
     * @param int $screenHeight высота скриншота
     * @return array $location позиция элемента на скриншоте
     */
    public function getNewLocation($location, $screenHeight)
    {
        $I = $this;

        $scrollHeight = $I->executeJS('return document.documentElement.scrollHeight');
        $clientHeight = $I->executeJS('return document.documentElement.clientHeight');
        if ($location[1] > $clientHeight) {
            $newLoc = $screenHeight - ($scrollHeight - $location[1]);
            codecept_debug($newLoc);
            $location = array_replace($location,
                array_fill_keys(
                    array_keys($location, $location[1]),
                    $newLoc
                )
            );
        }

        return $location;
    }

    /**
     * Скриншот и обрез капчи из скриншота.
     *
     * @param string $captchaXpath путь к капче
     * @param string $imgName название изображения
     * @return mixed $screen капча
     * @throws \Exception
     */
    public function screenAndCropImage($captchaXpath, $imgName)
    {
        $I = $this;

        $point = $I->findLocationByXpath($captchaXpath);
        $point = array_values((array) $point);
        $size = $I->findSizeByXpath($captchaXpath);
        $size = array_values((array) $size);
        $I->makeScreenshot($imgName);
        $screen = __DIR__.'/../_output/debug/'.$imgName.'.png';
        codecept_debug($screen);
        $screenHeight = getimagesize($screen);
        codecept_debug($screenHeight);
        $point = $I->getNewLocation($point, $screenHeight[1]);
        codecept_debug($point);
        codecept_debug($size);
        $img = imagecreatetruecolor($size[1], $size[0]);
        $img2 = imagecreatefrompng($screen);
        imagecopyresampled($img, $img2,0, 0, $point[0], $point[1], $size[1], $size[0], $size[1], $size[0]);

        imagepng($img, $screen);

        return $screen;
    }

    /**
     * Сравнение картинок капчи по хэшу.
     *
     * @param mixed $firstImage первая капча
     * @param mixed $secondImage вторая капча
     * @throws \Exception
     */
    public function compareCaptcha($firstImage, $secondImage)
    {
        $I = $this;

        $hash1 = $I->createHashFromFile($firstImage);
        $hash2 = $I->createHashFromFile($secondImage);
        $isEqual = ($hash1 == $hash2);
        $isNearEqual = $I->compareImageHashes($hash1, $hash2, 0.05);
        codecept_debug($isEqual);
        codecept_debug($isNearEqual);
    }

    /**
     * Получить количество товаров с информацией по типу рейтинг\отзыв\обзор.
     *
     * @param string $info переход в КТ с отзывом, обзором или рейтингом
     * @return int $itemsCount количество товаров
     * @throws \Exception
     */
    public function getProductsCountWithInfoFromListing($info)
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        return $listingPage->getProductsCountWithInfo($info);
    }

    /**
     * Ожидание начала и завершения загрузки BasketLoader.
     *
     * @throws \Exception
     */
    public function waitBasketLoader()
    {
        $I = $this;

        $listingPage = new ListingPage($I);
        $listingPage->waitBasketLoader();
    }
}
