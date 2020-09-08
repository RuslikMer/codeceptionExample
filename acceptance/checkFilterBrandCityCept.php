<?php

// @group set1
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('run check filter by Brand with switch City Space');
$I->openHomePage();
$I->changeCity();
$I->goToListing(2);
$I->checkFilterBrand();
