<?php
$I = new AcceptanceTester($scenario);
$checkOut = new \Page\CheckOut($I);
$I->am("non authorized user");
$I->wantTo('check adding insurer in checkout');
$I->openHomePage();
$I->goToListing(2, 1);
$I->goToProductFromListing();
$I->addToCartFromProductPage(false);
$service = $I->addWarrantyExtensionFromProductPage();
$I->continueShopping();
$I->checkResponseCode();
$I->goToCartFromProductBigButton();
$I->goFromCartToCheckout();
$checkOut->fillContacts(BUYER_NAME, BUYER_FAMILY_NAME, BUYER_PHONE);
$checkOut->fillInsurerContacts(BUYER_NAME, BUYER_FAMILY_NAME, BUYER_PHONE, USERNAME_ORDERS);
$checkOut->chooseSelfDelivery();
