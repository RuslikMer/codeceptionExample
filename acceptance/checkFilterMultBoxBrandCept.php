<?php

$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('run check multi filter by Brand');
$I->openHomePage();
$I->goToListing(2, 2);
$I->checkFilterBrand(2);
