<?php
$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('check search in orders');
$I->openHomePage();
$I->doLogin(USERNAME_ORDERS, PASSWORD_ORDERS);
$I->goToProfile();
$I->goToOrders();
$I->searchInOrdersList('samsung');
