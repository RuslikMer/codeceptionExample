<?php
$I = new AcceptanceTester($scenario);
$checkOut = new \Page\CheckOut($I);
$I->am("authorized citilink user");
$I->wantTo('Auth order from Main page - for Merlion user - courier delivery');
$I->openHomePage();
$I->doLogin('autotester-citik@citilink.ru', '21452145');
$I->doCleanUp();
$I->addToCartFromTile(4);
$I->goToCartFromProductButton();
$I->goFromCartToCheckout();
$checkOut->fillContacts(BUYER_NAME, BUYER_FAMILY_NAME, BUYER_PHONE);
$checkOut->chooseCourierDelivery();
$checkOut->fillContactsForCourierDelivery(BUYER_STREET, BUYER_HOUSE);
$checkOut->chooseStandardDelivery(true);
$checkOut->orderConfirmOnly();
$I->orderCancel();
