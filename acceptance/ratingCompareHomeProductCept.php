<?php
// @testexec_skip true
// @skip true
// @group set19
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('compare rating from Home and Product page');
$I->openHomePage();
$productsArray = array(1,2,4,5);

$productNumber = $productsArray[array_rand($productsArray)];
$starMain = $I->getRateFromHomePage($productNumber);
$I->goToProductFromMainPage($productNumber);
$starProduct = $I->getRateFromProductPage();
if ($starMain != $starProduct) {
    Throw new \Exception('Rating Main Page - '. $starMain .' NOT equal Product Page - '.$starProduct);
}
