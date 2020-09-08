<?php

// @group set1
$I = new AcceptanceTester($scenario);
$I->am("non-authorized user");
$I->wantTo('Image Search');
$I->openHomePage();
$I->searchString('смартфон');
$I->checkItemsImageUrlFastSearch();
