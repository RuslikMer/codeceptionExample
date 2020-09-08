<?php
// @group set2
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('run check Pagination');
$I->openHomePage();
$I->goToListing(2,1);
$I->checkPagination(Page\Listing::LISTING_TITLE);
