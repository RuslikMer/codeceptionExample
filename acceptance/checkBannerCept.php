<?php

// @group set1
// @group promo
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('run check Banner on Main Page');
$I->openHomePage();
if ( $I->isProduction($scenario) ) {
    $I->lookForwardTo("check for production");
    $I->checkBanner();
}