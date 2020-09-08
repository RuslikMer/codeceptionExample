<?php
// @testexec_skip true

$I = new AcceptanceTester($scenario);
$I->am("non-authorized user");
$I->wantTo('order KBT from listing page with delivery include lift to stage by elevator');
$I->openHomePage();
$I->searchStringByEnter(KBT_REQUEST);
$I->goToCategoryFromSearch(KBT_CATEGORY);
$I->wait(SHORT_WAIT_TIME);
$I->sortProductByDesc('price');
$I->addAndGoToCartFromLIsting();
$I->goFromCartToCheckout();
$I->chooseCourierDelivery();
$I->checkEnableLift();
$I->fillContactsForCourierDelivery(BUYER_NAME, BUYER_FAMILY_NAME, BUYER_PHONE, BUYER_STREET, BUYER_HOUSE);
$numberStage = mt_rand(2, 9);
$I->liftUptoStageElevator($numberStage);
$I->wait(SHORT_WAIT_TIME);
$I->checkEnableLift($numberStage, 'lift');
$I->goFromDeliveryToPayment('delivery');
$I->goFromPaymentToConfirmation();
$I->checkLiftUpToStageElevator();
$I->returnToDeliveryStage();
$I->checkEnableLift($numberStage, 'lift');
$I->goFromDeliveryToPayment('delivery');
$I->goFromPaymentToConfirmation();
$I->checkLiftUpToStageElevator();
