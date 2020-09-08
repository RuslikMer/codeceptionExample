<?php
// @testexec_usertype normal
$I = new AcceptanceTester($scenario);
$checkOut = new \Page\CheckOut($I);
$I->am("authorized user");
$I->wantTo('choose saved address for delivery');
$I->openHomePage();
$I->doLogin();
$I->doCleanUp();
$I->goToProductFromMainPage();
$I->addToCartFromProductPage();
$I->goToCartFromProductBigButton();
$I->goFromCartToCheckout();
$checkOut->fillContacts(BUYER_NAME, BUYER_FAMILY_NAME, BUYER_PHONE);
$checkOut->chooseCourierDelivery();
$checkOut->chooseSavedAddressOfDelivery();
