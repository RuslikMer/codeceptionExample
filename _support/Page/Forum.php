<?php
namespace Page;

class Forum
{
    // include url of current page
    public static $URL = '/';
    const PAGINATOR_TOPIC = '//div[@class="topic_top_block"]';
    const PAGINATOR_POST = '//div[@class="post_top_block"]';
    const FORUM_CATEGORY = '//tr[@class="forum_category"]'; //путь к блоку категории тем
    const LISTING_TOPIC = self::FORUM_CATEGORY.'/td[@class="topic_name"]/div/h2/a';
    const LISTING_POST = '//div[@class="forum-post__author-info"]/time';
    const SEARCH_RESULT = '//div[@class="forum_search_result"]';
    const SEARCH_INPUT = '//form[@class="forum_search"]';

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
     * Поиск по форуму.
     * 
     * @param string $text Поисковой запрос
     * @throws \Exception
     */
    public function searchString($text) 
    {
        $I = $this->tester;
        
        $I->lookForwardTo("enter string " . $text . " to search field and press Enter");
        $I->waitAndFill(self::SEARCH_INPUT.'/input[@type="search"]', 'search field', $text);
        $I->pressKey(['xpath' => self::SEARCH_INPUT.'/input[@type="search"]'], \WebDriverKeys::ENTER);
        $I->checkElementOnPage(self::SEARCH_RESULT, 'forum search result');
    }

    /**
     * Проверка инпута поиска города на emoji.
     *
     * @param string $text Emoji
     * @throws \Exception
     */
    public function emojiSearch($text)
    {
        $I = $this->tester;

        $I->lookForwardTo("enter string " . $text . " to search field and press Enter");
        $I->waitAndFill(self::SEARCH_INPUT.'/input[@type="search"]', 'search field', $text);
        $I->pressKey(['xpath' => self::SEARCH_INPUT.'/input[@type="search"]'], \WebDriverKeys::ENTER);
        $I->checkMandatoryElements();
    }

    /**
     * Переход в категорию на форуме с сообщениями от админа ситилинка.
     * 
     * @throws \Exception
     */
    public function goToForumCategory()
    {
        $I = $this->tester;
        
        $I->lookForwardTo('go to forum category');
        $categoriesCount = $I->getNumberOfElements(self::FORUM_CATEGORY.'[contains(.,"CitiAdmin")]/td[@class="forum_name"]/h2/a', 'forum category');
        if($categoriesCount == 0){
            Throw new \Exception('There no categories with CitiAdmins message');
        }

        $categoryNumber = mt_rand(1, $categoriesCount);
        $category = $I->grabTextFrom(['xpath' => self::FORUM_CATEGORY.'[contains(.,"CitiAdmin")]['. $categoryNumber .']/td[@class="forum_name"]/h2/a']);
        $categoryName = $I->translit($category);
        $I->waitAndClick(self::FORUM_CATEGORY.'[contains(.,"CitiAdmin")]['. $categoryNumber .']/td[@class="forum_name"]/h2/a', "forum category - . $categoryName");
        $I->see($category, ['class' => 'forum_header']);
    }

    /**
     * Переход в тему категории на форуме.
     * 
     * @param int $categoryNumber Номер темы в категории на форуме по порядку
     * @throws \Exception
     */
    public function goToTopic($categoryNumber) 
    {
        $I = $this->tester;
        
        $I->lookForwardTo('go to topic category');
        $number = $I->getNumberOfElements(self::FORUM_CATEGORY, 'topic in category');
        if($categoryNumber == NULL){
            $categoryNumber = mt_rand(1, $number);
        }
        $category = $I->grabTextFrom(['xpath' => self::FORUM_CATEGORY.'['. $categoryNumber .']/td[@class="topic_name"]/div/h2/a']);
        $categoryName = $I->translit($category);
        $I->waitAndClick(self::FORUM_CATEGORY.'['. $categoryNumber .']/td[@class="topic_name"]/div/h2/a', "topic in category - . $categoryName");
        $I->see($category, ['class' => 'forum_header']);
        
    }

    /**
     * Проверка блока пагинации на странице категории
     * 
     * @throws \Exception Проверка выбранной страницы
     */
    public function checkPaginationTopic() 
    {
        $I = $this->tester;
        
        $I->lookForwardTo('check that the first page is selected');
        $I->waitForElementVisible(self::PAGINATOR_TOPIC);
        $selected = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_TOPIC .'//div[@class="page_listing"]/section/ul/li[1]'], "class");
        codecept_debug("The first page is " . $selected . "");
        if($selected != "selected"){
           Throw new \Exception('The first page is not selected'); 
        }

        $arrayFirstPage = $I->grabMultiple(['xpath' => self::LISTING_TOPIC]);
        codecept_debug($arrayFirstPage);
        $I->lookForwardTo('check that the second page is selected');
        $I->waitAndClick(self::PAGINATOR_TOPIC .'//div[@class="page_listing"]/section/ul/li[@class="next"]', "second page");
        //$I->waitForElement(['xpath' => '//div[@class="helper_view helper_view_short"]/div[@style="display: none;"]']);
        $I->wait(SHORT_WAIT_TIME);
        $next = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_TOPIC .'//div[@class="page_listing"]/section/ul/li[2]'], "class");
        codecept_debug("The second page is " . $next . "");
        $previous = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_TOPIC .'//div[@class="page_listing"]/section/ul/li[1]'], "class");
        codecept_debug("The first page is " . $previous . "");
        if(($next != "selected") OR ($previous != "previous")){
           Throw new \Exception('The second page is not selected'); 
        }

        $arraySecondPage = $I->grabMultiple(['xpath' => self::LISTING_TOPIC]);
        codecept_debug($arraySecondPage);
        if($arrayFirstPage == $arraySecondPage){
           Throw new \Exception('List of products was not changed'); 
        }

        $I->lookForwardTo('check selected three dots page');
        $I->waitAndClick(self::PAGINATOR_TOPIC .'//div[@class="page_listing"]/section/ul/li[@class="more"]', "three dots page");
        $I->wait(SHORT_WAIT_TIME);
        $threeDots = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_TOPIC .'//div[@class="page_listing"]/section/ul/li[7]'], "class");
        codecept_debug("The right dots is " . $threeDots . "");
        $less = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_TOPIC .'//div[@class="page_listing"]/section/ul/li[2]'], "class");
        codecept_debug("The left dots is " . $less . "");
        if(($threeDots != "selected") OR ($less != "less")){
           Throw new \Exception('The three dots page is not selected'); 
        }

        $arrayDotsPage = $I->grabMultiple(['xpath' => self::LISTING_TOPIC]);
        codecept_debug($arrayDotsPage);
        if($arrayDotsPage == $arraySecondPage){
           Throw new \Exception('List of products was not changed'); 
        }

        $I->lookForwardTo('check that the left dots page is selected');
        $I->waitAndClick(self::PAGINATOR_TOPIC .'//div[@class="page_listing"]/section/ul/li[@class="less"]', "The left dots");
        $I->wait(SHORT_WAIT_TIME);
        $less = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_TOPIC .'//div[@class="page_listing"]/section/ul/li[2]'], "class");
        codecept_debug("The left dots is " . $less . "");
        if($less != "selected"){
           Throw new \Exception('The left dots page is not selected'); 
        }

        $arrayLeftDotsPage = $I->grabMultiple(['xpath' => self::LISTING_TOPIC]);
        codecept_debug($arrayLeftDotsPage);
        if($arrayDotsPage == $arrayLeftDotsPage){
           Throw new \Exception('List of products was not changed'); 
        }

        $I->lookForwardTo('check that the last page is selected');
        $I->waitAndClick(self::PAGINATOR_TOPIC .'//div[@class="page_listing"]/section/ul/li[@class="last"]', "The last page");
        $I->wait(SHORT_WAIT_TIME);
        $lastPage = $I->getNumberOfElements(self::PAGINATOR_TOPIC .'//div[@class="page_listing"]/section/ul/li', 'pagination block');
        codecept_debug($lastPage);
        $last = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_TOPIC .'//div[@class="page_listing"]/section/ul/li[' . $lastPage . ']'], "class");
        codecept_debug("The last page is " . $last . "");
        if($last != "selected"){
           Throw new \Exception('The last page is not selected'); 
        }

        $arrayLastPage = $I->grabMultiple(['xpath' => self::LISTING_TOPIC]);
        codecept_debug($arrayLastPage);
        if($arrayLastPage == $arrayLeftDotsPage){
           Throw new \Exception('List of products was not changed'); 
        }

        $I->lookForwardTo('check that the first page is selected');
        $I->waitAndClick(self::PAGINATOR_TOPIC .'//div[@class="page_listing"]/section/ul/li[@class="first"]', "The first page");
        $I->wait(SHORT_WAIT_TIME);
        $first = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_TOPIC .'//div[@class="page_listing"]/section/ul/li[1]'], "class");
        codecept_debug("The first page is " . $first . "");
        if($first != "selected"){
           Throw new \Exception('The first page is not selected'); 
        }

        $arrayFirstPage = $I->grabMultiple(['xpath' => self::LISTING_TOPIC]);
        codecept_debug($arrayFirstPage);
        if($arrayLastPage == $arrayFirstPage){
           Throw new \Exception('List of products was not changed'); 
        }
    }

    /**
     * Проверка блока пагинации на странице темы
     * 
     * @throws \Exception Проверка выбранной страницы
     */
    public function checkPaginationPost() 
    {
        $I = $this->tester;

        $pagination = $I->getNumberOfElements(self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[1]');
        if ($pagination !== 0) {
            $I->lookForwardTo('check that the first page is selected');
            $selected = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[1]'], "class");
            codecept_debug("The first page is " . $selected . "");
            if ($selected != "selected") {
                Throw new \Exception('The first page is not selected');
            }

            $arrayFirstPage = $I->grabMultiple(['xpath' => self::LISTING_POST]);
            codecept_debug($arrayFirstPage);
            $I->lookForwardTo('check that the second page is selected');
            $I->waitAndClick(self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[@class="next"]', "second page");
            $I->wait(SHORT_WAIT_TIME);
            $next = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[2]'], "class");
            codecept_debug("The second page is " . $next . "");
            $previous = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[1]'], "class");
            codecept_debug("The first page is " . $previous . "");
            if (($next != "selected") OR ($previous != "previous")) {
                Throw new \Exception('The second page is not selected');
            }

            $arraySecondPage = $I->grabMultiple(['xpath' => self::LISTING_POST]);
            codecept_debug($arraySecondPage);
            if ($arrayFirstPage == $arraySecondPage) {
                Throw new \Exception('List of products was not changed');
            }
        }

        $dotsCount = $I->getNumberOfElements(self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[@class="more"]');
        if($dotsCount !== 0) {
            $I->lookForwardTo('check selected three dots page');
            $I->waitAndClick(self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[@class="more"]', "three dots page");
            $I->wait(SHORT_WAIT_TIME);
            $threeDots = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[7]'], "class");
            codecept_debug("The right dots is " . $threeDots . "");
            $less = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[2]'], "class");
            codecept_debug("The left dots is " . $less . "");
            if (($threeDots != "selected") OR ($less != "less")) {
                Throw new \Exception('The three dots page is not selected');
            }

            $arrayDotsPage = $I->grabMultiple(['xpath' => self::LISTING_POST]);
            codecept_debug($arrayDotsPage);
            if ($arrayDotsPage == $arraySecondPage) {
                Throw new \Exception('List of products was not changed');
            }

            $I->lookForwardTo('check that the left dots page is selected');
            $I->waitAndClick(self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[@class="less"]', "The left dots");
            $I->wait(SHORT_WAIT_TIME);
            $less = $I->grabAttributeFrom(self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[2]', "class");
            codecept_debug("The left dots is " . $less . "");
            if ($less != "selected") {
                Throw new \Exception('The left dots page is not selected');
            }

            $arrayLeftDotsPage = $I->grabMultiple(self::LISTING_POST);
            codecept_debug($arrayLeftDotsPage);
            if ($arrayDotsPage == $arrayLeftDotsPage) {
                Throw new \Exception('List of products was not changed');
            }

            $I->lookForwardTo('check that the last page is selected');
            $I->waitAndClick(self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[@class="last"]', "The last page");
            $I->wait(SHORT_WAIT_TIME);
            $lastPage = $I->getNumberOfElements(self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li', 'pagination block');
            codecept_debug($lastPage);
            $last = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[' . $lastPage . ']'], "class");
            codecept_debug("The last page is " . $last . "");
            if ($last != "selected") {
                Throw new \Exception('The last page is not selected');
            }

            $arrayLastPage = $I->grabMultiple(['xpath' => self::LISTING_POST]);
            codecept_debug($arrayLastPage);
            if ($arrayLastPage == $arrayLeftDotsPage) {
                Throw new \Exception('List of products was not changed');
            }

            $I->lookForwardTo('check that the first page is selected');
            $I->waitAndClick(self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[@class="first"]', "The first page");
            $I->wait(SHORT_WAIT_TIME);
            $first = $I->grabAttributeFrom(['xpath' => self::PAGINATOR_POST . '//div[@class="page_listing"]/section/ul/li[1]'], "class");
            codecept_debug("The first page is " . $first . "");
            if ($first != "selected") {
                Throw new \Exception('The first page is not selected');
            }

            $arrayFirstPage = $I->grabMultiple(['xpath' => self::LISTING_POST]);
            codecept_debug($arrayFirstPage);
            if ($arrayLastPage == $arrayFirstPage) {
                Throw new \Exception('List of products was not changed');
            }
        }
    }

    /**
     * Проверка результатов поиска.
     *
     * @param string $searchText текст поискового запроса
     * @throws \Exception
     */
    public function checkSearchResult($searchText)
    {
        $I = $this->tester;

        $I->searchStringForum($searchText);
        $resultsCount = $I->getNumberOfElements(self::SEARCH_RESULT.'/div//span');
        $results = [];
        for ($i = 1; $i <= $resultsCount; $i++){
            $searchResult = $I->grabTextFrom(self::SEARCH_RESULT.'/div[not(contains(@class,"found_item_create_date"))]['.$i.']/a/span');
            array_push($results, $searchResult );
        }

        foreach ($results as $result){
            $res = mb_strtolower($result);
            $I->assertValuesAreEquals($res, $searchText);
        }
    }

    /**
     * Переход на страницу сообщения в форуме.
     *
     * @throws \Exception
     */
    public function goToMessage()
    {
        $I = $this->tester;

        $messagesCount = $I->getNumberOfElements(['class' => 'last_post_link']);
        $message = mt_rand(1,$messagesCount);
        $I->waitAndClick(self::FORUM_CATEGORY.'['.$message.']//a[@class="last_post_link"]', 'go to message in category');
    }

    /**
     * Отправка цитаты.
     *
     * @throws \Exception
     */
    public function sendCitation()
    {
        $I = $this->tester;

        $I->waitAndClick('//div[@class="forum-post__post-interaction"][contains(.,"СитиАдмин")]//button[@class="pretty_button type2 quote_post"]',
            'open citation form');
        $this->checkPreView();
        $I->waitAndClick(['id' => 'sendPost'], 'send citation');
        $I->checkMandatoryElements();
        $postsCount = $I->getNumberOfElements(['class' => 'forum-post']);
        $I->waitForElementVisible('//div[@class="forum-post"]//button[@class="pretty_button type2 edit_post"]');
        $citation = $I->grabTextFrom('//div[@class="forum-post"]['.$postsCount.']//div[@class="forum-post__post-text"]');
        if(empty($citation)){
            Throw new \Exception('citation does not work');
        }
    }

    /**
     * Отправка личного сообщения пользователю.
     *
     * @throws \Exception
     */
    public function sendPrivateMessage()
    {
        $I = $this->tester;

        $I->waitAndClick('//div[@class="forum-post__post-interaction"][contains(.,"СитиАдмин")]//button[@class="pretty_button type2 show_send_private_message_form"]',
            'open private message form');
        $I->waitAndFill(['id' => 'privateMessageText'], 'message field', 'test message');
        $I->waitAndClick(['id' => 'sendPrivateMessage'], 'send private message');
        $I->waitForElementNotVisible('//div[@id="privateMessage"]');
    }

    /**
     * Выбор сообщения админа на форуме.
     *
     * @return int $messagesCount
     * @throws \Exception
     */
    public function chooseAdminUserMessage()
    {
        $I = $this->tester;

        $messagesCount = $I->getNumberOfElements('//div[@class="user"][contains(.,"CitiAdmin")]');
        $I->waitAndClick(self::FORUM_CATEGORY.'[contains(.,"CitiAdmin") and not(contains(.,"Закрыто"))]//a[@class="last_post_link"]', 'select CitiAdmin message');

        return $messagesCount;
    }

    /**
     * Отправка сообщения.
     *
     * @throws \Exception
     */
    public function sendMessage()
    {
        $I = $this->tester;

        $I->waitAndFill(['name' => 'message'], 'message field', 'test message');
        $this->checkPreView();
        $I->waitAndClick(['id' => 'sendPost'], 'send message');
        $postsCount = $I->getNumberOfElements(['class' => 'forum-post']);
        $I->waitForElementVisible('//div[@class="forum-post"]//button[@class="pretty_button type2 edit_post"]');
        $message = $I->grabTextFrom('//div[@class="forum-post"]['.$postsCount.']//div[@class="forum-post__post-text"]');
        if(empty($message)){
            Throw new \Exception('sending message does not work');
        }
    }

    /**
     * Проверка предпросмотра.
     *
     * @throws \Exception
     */
    public function checkPreView()
    {
        $I = $this->tester;

        $I->waitAndClick(['id' => 'previewPost'], 'open preview');
        $citation = $I->grabTextFrom(['id' => 'post0']);
        if(empty($citation)){
            Throw new \Exception('citation does not work');
        }
    }

    /**
     * Удаление сообщения.
     *
     * @throws \Exception
     */
    public function deleteMessage()
    {
        $I = $this->tester;

        $prePostsCount = $I->getNumberOfElements(['class' => 'forum-post']);
        $I->waitAndClick('//button[@class="pretty_button type2 delete_post"]', 'delete post');
        $afterPostsCount = $I->getNumberOfElements(['class' => 'forum-post']);

        if($prePostsCount <= $afterPostsCount){
            Throw new \Exception('delete button does no work');
        }
    }
}
