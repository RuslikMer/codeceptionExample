<?php

$I = new AcceptanceTester($scenario);
$checkOut = new \Page\CheckOut($I);
$I->am("non-authorized user");
$I->wantTo('order KBT from listing page with delivery');
$I->openHomePage();
$I->searchStringByEnter(KBT_REQUEST);
$I->goToCategoryFromSearch(KBT_CATEGORY);
$I->sortProductByDesc('price');
$I->addAndGoToCartFromListing();
$I->goFromCartToCheckout();
$checkOut->fillContacts(BUYER_NAME, BUYER_FAMILY_NAME, BUYER_PHONE);
$checkOut->chooseCourierDelivery();
$checkOut->fillContactsForCourierDelivery(BUYER_STREET, BUYER_HOUSE);
$numberStage = mt_rand(1, 7);
$checkOut->liftUpToStage($numberStage);
