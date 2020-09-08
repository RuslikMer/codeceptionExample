<?php

$I = new AcceptanceTester($scenario);
$I->am("not-authorized user");
$I->wantTo('check Pagination Used Page');
$I->openHomePage();
$I->openUsedPage();
$I->checkPagination(Page\Used::XPATH_LISTING_TITLE);