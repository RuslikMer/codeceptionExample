<?php
// @testexec_skip true


$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('clean up data before test - for ALL users');
$I->amOnPage("/");
//всегда выставляем параметр для скрытия панели Symphony
$I->executeJS("javascript:localStorage.setItem('sf2/profiler/toolbar/displayState','none');");

for ($i = 1; $i <= 16; $i++) {
    $I->openHomePage();
    $I->doLogin('autotester-jenkins-slave-' . sprintf('%02d', $i) . '@citilink.ru', PASSWORD);
    $I->doCleanUp();
    $I->deleteDeliveryAddresses();
    $I->doLogout();
}

for ($i = 1; $i <= 10; $i++) {
    $I->openHomePage();
    $I->doLogin('selenoid-' . sprintf('%02d', $i) . '@citilink.ru', PASSWORD);
    $I->doCleanUp();
    $I->deleteDeliveryAddresses();
    $I->doLogout();
}


$I->openHomePage();
$I->doLoginB2B();
//очищаем корзину
if ($I->getCurrentCartAmountInitial() > 0) {
    $I->goToCartFromTopLink();
    $I->deleteItemFromCart(0);
}

