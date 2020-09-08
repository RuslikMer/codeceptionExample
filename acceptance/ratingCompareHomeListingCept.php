<?php
// @testexec_skip true
// @skip true
// @group set19
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('compare rating from Home and Listing page');
$I->openHomePage();

$number = mt_rand(4, 7);
$idNum = $I->getItemIdFromHomePage($number);
$numberFake = $number + 1;
$idNumFake = $I->getItemIdFromHomePage($numberFake);
$starMain = $I->getRateFromHomePage($number);
$I->searchStringByEnter("$idNum $idNumFake");
$starListing = $I->getFirstRateFromListingPage();
if (($starMain) != ($starListing)) {
    Throw new \Exception('Rating Main Page - '. $starMain .' NOT equal Listing Page - '.$starListing);
}
