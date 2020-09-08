<?php
$I = new AcceptanceTester($scenario);
$I->am("not authorized user");
$I->wantTo('check search by items id');
$I->openHomePage();
$itemsId = $I->getItemsIdFromHomePage(20);
$I->searchStringByEnter(implode(' ', $itemsId));
$resultItemsId = $I->getItemsIdFromSearch();
$I->seeValuesAreEquals(arsort($itemsId), arsort($resultItemsId));
