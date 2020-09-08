<?php

// @group set2
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('run check Listing Page');
$I->openHomePage();
$I->goToListing(2,7);
$I->checkFilterStatus();
