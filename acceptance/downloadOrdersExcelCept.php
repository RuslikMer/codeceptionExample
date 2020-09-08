<?php

$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('check duplicate delivery in orders');
$I->openHomePage();
$I->doLogin(USERNAME_ORDERS, PASSWORD_ORDERS);
$I->goToProfile();
$I->goToOrders();
$link = $I->grabAttributeFrom('//div[@class="orders-list-control"]/a', 'href');
$I->amOnUrl( $link );
$I->wait(SHORT_WAIT_TIME);
$I->waitAndClick('//div[@class="orders-list-control"]/a', 'download orders list');
$I->wait(SHORT_WAIT_TIME);
$I->goToOrders();
