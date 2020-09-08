<?php
namespace Page;

class Reviews
{
    // include url of current page
    public static $URL = '/reviews/';
    // выбор раздела сайта с обзорами
    const XPATH_CATALOG_REVIEW = '//div[@class="subnavigation important_block alt2_links no_visited"]';

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

    const XPATH_REVIEW_ITEM = '//tbody[@class="product_data__gtm-js product_data__pageevents-js"]';
    const LISTING_TITLE_REVIEW = '//td[@class="product_name"]/h3/a';


    /**
     * Проверка фильтра обзоров.
     * 
     * @throws \Exception Если заголовок не равен ожидаемому
     */
    public function checkFilterReview() 
    {
        $I = $this->tester;
        
        $I->lookForwardTo('check default filter');
        $I->checkElementOnPage('//li[@class="for_reviews_new selected"]', 'button New selected');
        $newFilter = $I->grabTextFrom(['xpath' => '//div[@class="reviews_new product_view block_data__gtm-js block_data__pageevents-js"]/h2']);
        if($newFilter != 'Новые обзоры'){
            Throw new \Exception('header " New reviews " does not appear');
        }
        
        $newFilterProduct = $I->grabMultipleDesc(self::XPATH_REVIEW_ITEM . '//td[@class="product_name"]/p/span', 'new review item');
        $I->lookForwardTo('check last month filter');
        $I->waitAndClick(['class' => 'for_reviews_best_month'], 'best of last month');
        $I->checkElementOnPage('//li[@class="for_reviews_best_month selected"]', 'button Best of last month selected');
        $bestMonth = $I->grabTextFrom(['xpath' => '//div[@class="reviews_best_month product_view block_data__gtm-js block_data__pageevents-js"]/h2']);
        if($bestMonth != 'Лучшие за месяц'){
            Throw new \Exception('header " Last month reviews " does not appear');
        }
        
        $bestMonthProduct = $I->grabMultipleDesc(self::XPATH_REVIEW_ITEM . '//td[@class="product_name"]/p/span', 'best month review item');
        if($newFilterProduct == $bestMonthProduct){
            Throw new \Exception("best month review item not displayed -  $bestMonthProduct");
        }        
        
        $I->lookForwardTo('check last year filter');
        $I->waitAndClick(['class' => 'for_reviews_best_year'], 'best of last year');
        $I->checkElementOnPage('//li[@class="for_reviews_best_year selected"]', 'button Best of last year selected');
        $bestYear = $I->grabTextFrom(['xpath' => '//div[@class="reviews_best_year product_view  block_data__gtm-js block_data__pageevents-js"]/h2']);
        if($bestYear != 'Лучшие за год'){
            Throw new \Exception('header " Last year reviews " does not appear');
        }
        
        $bestYearProduct = $I->grabMultipleDesc(self::XPATH_REVIEW_ITEM . '//td[@class="product_name"]/p/span', 'best year review item');
        if($bestMonthProduct == $bestYearProduct){
            Throw new \Exception("best year review item not displayed -  $bestYearProduct");
        }
        
        $I->lookForwardTo('check TOP filter');
        $I->waitAndClick(['class' => 'for_reviews_top'], 'TOP review');
        //$I->checkElementOnPage(['xpath' => '//li[@class="for_reviews_best_top selected"]'], 'button TOP filter selected');
        $bestTop = $I->grabTextFrom(['xpath' => '//div[@class="main_content_inner"]/h1']);
        if($bestTop != 'Лучшие обзоры за всё время'){
            Throw new \Exception('header " Best ever reviews " does not appear');
        }
        
        $bestTopProduct = $I->grabMultipleDesc(self::XPATH_REVIEW_ITEM . '//td[@class="product_name"]/p/span', 'top review item');
        if($bestTopProduct == $bestYearProduct){
            Throw new \Exception("best year review item not displayed -  $bestTopProduct");
        }
    }
    
    /**
     * Переход в конечную категорию для обзоров.
     *
     * @throws \Exception
     */
    public function checkCategoryReview() 
    {
        $I = $this->tester;
        do {
        $categoryNumber = $I->getNumberOfElements(self::XPATH_CATALOG_REVIEW . '/ul', 'category review');
        $categoryRnd = mt_rand(1, $categoryNumber);
        $subCategoryNumber = $I->getNumberOfElements(self::XPATH_CATALOG_REVIEW . '/ul['. $categoryRnd .']/li', 'category review');
        $subCategoryRnd = mt_rand(1, $subCategoryNumber);
        $I->waitAndClick(self::XPATH_CATALOG_REVIEW . '/ul['. $categoryRnd .']/li['. $subCategoryRnd .']', 'category');
        $number = $I->getNumberOfElements(self::XPATH_CATALOG_REVIEW . '/ul', 'check listing review');
        } while ($number != 0);        
    }
    
    /**
     * Переход на страницу обзора - карточка товара, вкладка обзоры.
     *
     * @throws \Exception
     */
    public function readReview() 
    {
        $I = $this->tester;
        
        $I->lookForwardTo('read review');
        $number = $I->getNumberOfElements(self::XPATH_REVIEW_ITEM . '//a[@class="add_to_cart pretty_button pretty_button_link type4 link_gtm-js link_pageevents-js"]',
            'read button');
        $numberRnd = mt_rand(1, $number);
        $I->waitAndClick(self::XPATH_REVIEW_ITEM . '['. $numberRnd .']//a[@class="add_to_cart pretty_button pretty_button_link type4 link_gtm-js link_pageevents-js"]',
            'read button');
        $I->checkElementOnPage(Product::XPATH_PRODUCT_TABS . '//li[contains(@class,"active") and @data-tabname="reviews"]', 'review page in product cart');
    }
}
