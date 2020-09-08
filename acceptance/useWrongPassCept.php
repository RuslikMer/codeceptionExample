<?php

$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('check wrong pass');
$I->openHomePage();
$I->doLoginFail('sdfsdf','12345');
$I->checkFailEnterPage();