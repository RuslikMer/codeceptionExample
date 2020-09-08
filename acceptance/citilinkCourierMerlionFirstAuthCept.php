<?php
$I = new AcceptanceTester($scenario);
$checkOut = new \Page\CheckOut($I);
$I->am("authorized citilink user");
$I->wantTo('Auth order from Main page - Merlion user - courier delivery to first address');
$I->openHomePage();
$I->doLogin(USERNAME_MERLION, PASSWORD_MERLION);
$I->doCleanUp();
$I->openHomePage();
$I->addToCartFromTile(4);
$I->goToCartFromProductButton();
$I->goFromCartToCheckout();
$checkOut->fillContacts(BUYER_NAME, BUYER_FAMILY_NAME, BUYER_PHONE);
$checkOut->chooseCourierDelivery();
$checkOut->checkSelectedMerlionAddress(1);
$checkOut->orderConfirmOnly();
$I->orderCancel();
