<?php
// @testexec_usertype normal

$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('check login from forum after wrong pass');
$I->openHomePage();
$I->openForumPage();
$I->doLoginFail('sdfsdf','12345');
$I->checkFailEnterPage();
$I->doLoginAfterFailEnter();