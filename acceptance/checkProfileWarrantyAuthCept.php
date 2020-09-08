<?php
// @testexec_usertype normal

// @group set20
$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('check profile warranty');
$I->openHomePage();
$I->doLogin();

$I->goToProfile();
$I->checkProfileWarranty();
//$I->checkCaptcha();