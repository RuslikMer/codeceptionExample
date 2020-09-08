<?php
// @testexec_usertype normal

$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('check add new used page');
$I->openHomePage();
$I->doLogin();
$I->openUsedPage();
$I->addNewAdv();
$I->fillRequireAdv();
