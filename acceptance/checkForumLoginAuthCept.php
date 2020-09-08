<?php
// @testexec_usertype normal

$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('check login from forum');
$I->openHomePage();
$I->openForumPage();
$I->doLogin();
$I->goToForumCategory();
