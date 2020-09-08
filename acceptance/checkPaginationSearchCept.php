<?php

// @group set2
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('run check Pagination in Search mode');
$I->openHomePage();
$I->searchStringByEnter('холодильник');
$I->checkPagination(Page\Listing::LISTING_TITLE);