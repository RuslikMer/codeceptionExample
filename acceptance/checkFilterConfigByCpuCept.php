<?php

$I = new AcceptanceTester($scenario);
$I->am("non authorized user");
$I->wantTo('check filter config by cpu platform');
$I->openHomePage();
$I->selectConfigMenu();
$I->checkFilterConfigByCpu();