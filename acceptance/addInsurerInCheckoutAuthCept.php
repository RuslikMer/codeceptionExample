<?php
// @testexec_usertype normal

$I = new AcceptanceTester($scenario);
$checkOut = new \Page\CheckOut($I);
$I->am("authorized user");
$I->wantTo('check auto adding insurer in checkout');
$I->openHomePage();
$I->doLogin();
$I->doCleanUp();
$I->goToListing(2, 1);
$I->goToProductFromListing();
$I->addToCartFromProductPage(false);
$service = $I->addWarrantyExtensionFromProductPage();
$I->checkResponseCode();
$I->continueShopping();
$I->goToCartFromProductBigButton();
$I->goFromCartToCheckout();
$checkOut->fillContacts(BUYER_NAME, BUYER_FAMILY_NAME, BUYER_PHONE);
$checkOut->autoFillInsurerContacts(USERNAME_ORDERS);
$checkOut->chooseSelfDelivery();
$checkOut->orderConfirmOnly();
$I->orderCancel();
