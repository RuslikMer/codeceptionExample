<?php

// @group sort
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('run check Listing Page');
$I->openHomePage();
$I->goToListing(2,1);
$I->checkSortByReview();
