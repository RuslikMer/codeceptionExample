<?php
// @testexec_skip true
// @skip true
$I = new AcceptanceTester($scenario);
$checkOut = new \Page\CheckOut($I);
$I->am("non-authorized user");
$I->wantTo('order from listing page with added digital service from product card');
$I->openHomePage();
$I->goToListing(2,2);
$I->goToProductFromListing();
$I->selectServicesProductPage();
$service = $I->addToCartDigitalService(\Page\Product::XPATH_DIGITAL_SERVICE, \Page\Product::XPATH_DIGITAL_SERVICE_PRICE, \Page\Product::XPATH_DIGITAL_SERVICE_NAME);
codecept_debug('Service name and price');
codecept_debug($service);
$I->addToCartFromProductPage();
$I->goToCartFromProductBigButton();
$I->goFromCartToCheckout();
$checkOut->fillContacts(BUYER_NAME, BUYER_FAMILY_NAME, BUYER_PHONE);
$checkOut->chooseSelfDelivery();
