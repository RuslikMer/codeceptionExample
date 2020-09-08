<?php
// @testexec_usertype normal

$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('check login after wrong pass');
$I->openHomePage();
$I->doLoginFail('sdfsdf','12345');
$I->checkFailEnterPage();
$I->doLoginAfterFailEnter();
$I->checkMandatoryElements();
