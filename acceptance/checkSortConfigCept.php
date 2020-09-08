<?php
$I = new AcceptanceTester($scenario);
$I->am("non authorized user");
$I->wantTo('check sorting config');
$I->openHomePage();
$I->selectConfigMenu();
$I->checkSortConfig();
