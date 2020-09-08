<?php
namespace Page;

class Used
{
    // include url of current page
    public static $URL = '/used/';


    /**
     * Наименование объявления в списке.
     */
    const XPATH_LISTING_TITLE = '//p[@class="used-item-data__title"]/a';

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
     * Переход на страницу добавления объявления.
     *
     * @throws \Exception
     */
    public function addNewAdv()
    {
        $I = $this->tester;

        $I->lookForwardTo('button add new advert');
        $I->waitAndClick('//div[@class="used-container"]//a[@class="used-side__create-advert-link pretty_button pretty_button_link type6"]', 'button add new advert');
        $I->seeInCurrentUrl('create/');
    }

    /**
     * Заполнение обязательных полей при создании объявления.
     *
     * @throws \Exception
     */
    public function fillRequireAdv()
    {
        $I = $this->tester;

        $I->fillField(['id' => 'edit_title'], 'title');
        $I->fillField(['id' => 'edit_description'], 'description');
        $I->waitAndClick(['id' => 'edit_categoryId'], 'select category');
        $I->waitAndClick('//select[@id="edit_categoryId"]/option[@value="17"]', '');
        $I->fillField(['id' => 'edit_address'], 'address');
        $I->fillField(['id' => 'edit_price'], '999');
        $I->wait(SHORT_WAIT_TIME);
        $saveButton = $I->grabAttributeFrom(['id' => 'edit_save'], 'disabled');
        $I->seeButtonSaveEnable($saveButton);
    }

    /**
     * Переход в карточку объявления из листинга.
     *
     * @return mixed Наименование товара
     * @throws \Exception
     */
    public function goToAdvCard()
    {
        $I = $this->tester;

        $I->lookForwardTo('open adv product card');
        $advProducts = $I->grabMultipleDesc(self::XPATH_LISTING_TITLE, 'product listing');
        $I->seeProductsListingNotEmpty($advProducts);
        $advProduct = array_rand($advProducts) + 1;
        $I->waitAndClick('//div[@class="used-item"]['. $advProduct .']' . self::XPATH_LISTING_TITLE . '', "go to product " . $advProduct);
        $title = $I->grabTextFrom(['xpath' => '//div[@class="used-item-content used-item_js"]/h1']);
        $I->checkElementOnPage(['class' => 'used-item-side__info'], 'info block');

        return $title;
    }
}
