<?php
namespace Page;

class NotFound
{
    // include url of current page
    public static $URL = '/404/';

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
     * Переход на карточку товара из ротатора на странице 404.
     *
     * @throws \Exception
     */
    public function goToProductFrom404()
    {
        $I = $this->tester;
        
        $I->lookForwardTo('go to product from rotator');
        $array = $I->grabMultipleDesc('//div[@class="retailrocket-items"]/div', 'products in rotator 404');
        $array = array_filter($array);
        codecept_debug($array);
        $number = array_rand($array) + 1;
        codecept_debug($number);
        $I->waitAndClick('//div[@class="retailrocket-items"]/div[' . $number . ']//div[@class="rr-action-btn"]/a[@class="retailrocket-actions-buy"]',
            'add to cart from rotator 404');
    }
}
