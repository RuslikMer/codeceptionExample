<?php

$I = new AcceptanceTester($scenario);
$I->am("non-authorized user");
$I->wantTo('check used product page');
$I->openHomePage();
$I->openUsedPage();
$I->goToAdvCard();