<?php

$I = new AcceptanceTester($scenario);
$I->am("non authorized user");
$I->wantTo('check filter config by type');
$I->openHomePage();
$I->selectConfigMenu();
$I->checkFilterConfigByType();