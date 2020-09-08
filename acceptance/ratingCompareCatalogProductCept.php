<?php
// @testexec_skip true
// @skip true
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('compare rating from Catalog and Product page');
$I->openHomePage();

$number = mt_rand(1, 2);
$I->clickMainMenuCategory(1);
$starCatalog = $I->getRateFromCatalogPage($number);
$I->goToProductFromCatalogPage($number);
$starProduct = $I->getRateFromProductPage();
if (($starCatalog) != ($starProduct)) {
    Throw new \Exception('Rating Catalog Page - ' . $starCatalog .' NOT equal Product Page - ' . $starProduct);
}