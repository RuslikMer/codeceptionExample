<?php
$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('add and delete review');
$I->openHomePage();
$I->doLogin(USERNAME_REVIEW, PASSWORD_CLUB);
$I->goToProfile();
$I->goToProfileAction();
$userId = $I->getUserId();
//$I->goToReviewsTabAtProfile();
//$reviewLinks = $I->checkActiveReviewsAtProfile();
//if (!empty($reviewLinks)) {
//    $I->deleteAllReviewsInProfile($reviewLinks);
//}
$I->goToListing();
$I->goToProductFromListing();
$I->selectReviewsPage();
$I->startReview();
$I->addReviewForProduct();
$revId = $I->getReviewId();
$I->deleteReview($userId, $revId);
