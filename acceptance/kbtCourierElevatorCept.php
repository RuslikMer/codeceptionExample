<?php

$I = new AcceptanceTester($scenario);
$checkOut = new \Page\CheckOut($I);
$I->am("non-authorized user");
$I->wantTo('order KBT from listing page with delivery include lift to stage by elevator');
$I->openHomePage();
$I->searchStringByEnter(KBT_REQUEST);
$I->goToCategoryFromSearch(KBT_CATEGORY);
$I->wait(SHORT_WAIT_TIME);
$I->sortProductByDesc('price');
$I->addAndGoToCartFromLIsting();
$I->goFromCartToCheckout();
$checkOut->fillContacts(BUYER_NAME, BUYER_FAMILY_NAME, BUYER_PHONE);
$checkOut->chooseCourierDelivery();
$checkOut->fillContactsForCourierDelivery(BUYER_STREET, BUYER_HOUSE);
$numberStage = mt_rand(2, 9);
$checkOut->liftUptoStageElevator($numberStage);
