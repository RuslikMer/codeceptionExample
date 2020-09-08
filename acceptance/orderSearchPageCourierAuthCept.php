<?php
// @testexec_usertype normal

$I = new AcceptanceTester($scenario);
$checkOut = new \Page\CheckOut($I);
$I->am("authorized user");
$I->wantTo('use search with courier delivery by authorized user');
$I->openHomePage();
$I->doLogin();
$I->doCleanUp();
$I->searchStringByEnter('asus');
$I->selectSearchCategory();
$I->addToCartFromListing();
$I->continueShopping();
$I->searchStringByEnter('lenovo');
$I->selectSearchCategory();
$I->addToCartFromListing();
$I->goToCartFromListingPopup();
$I->goFromCartToCheckout();
$checkOut->fillContacts(BUYER_NAME, BUYER_FAMILY_NAME, BUYER_PHONE);
$checkOut->chooseCourierDelivery();
$checkOut->fillContactsForCourierDelivery(BUYER_STREET, BUYER_HOUSE);
$checkOut->chooseStandardDelivery();
$checkOut->orderConfirmOnly();
$I->orderCancel();
