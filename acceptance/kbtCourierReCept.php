<?php
// @testexec_skip true

$I = new AcceptanceTester($scenario);
$I->am("non-authorized user");
$I->wantTo('order KBT from listing page with delivery');
$I->openHomePage();
$I->searchStringByEnter(KBT_REQUEST);
$I->goToCategoryFromSearch(KBT_CATEGORY);
$I->wait(SHORT_WAIT_TIME);
$I->sortProductByDesc('price');
$I->addAndGoToCartFromListing();
$I->goFromCartToCheckout();
$I->chooseCourierDelivery();
$I->checkEnableLift();
$I->fillContactsForCourierDelivery(BUYER_NAME, BUYER_FAMILY_NAME, BUYER_PHONE, BUYER_STREET, BUYER_HOUSE);
$numberStage = mt_rand(2, 7);
$I->liftUpToStage($numberStage);
$I->wait(SHORT_WAIT_TIME);
$I->checkEnableLift($numberStage);
$I->goFromDeliveryToPayment('delivery');
$I->goFromPaymentToConfirmation();
$I->checkLiftUpToStage($numberStage);
$I->returnToDeliveryStage();
$I->checkEnableLift($numberStage);
$I->goFromDeliveryToPayment('delivery');
$I->goFromPaymentToConfirmation();
$I->checkLiftUpToStage($numberStage);
