<?php
// @testexec_usertype normal

// @group set2
$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('check profile');
$I->openHomePage();
$I->doLogin();

$I->goToProfile();
$I->checkProfile();