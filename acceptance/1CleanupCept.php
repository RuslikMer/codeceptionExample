<?php
// @testexec_skip true
// @skip true
$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('clean up data before test');
$I->amOnPage("/");
//всегда выставляем параметр для скрытия панели Symphony
$I->executeJS("javascript:localStorage.setItem('sf2/profiler/toolbar/displayState','none');");
$I->openHomePage();
$I->doLogin();
$I->doCleanUp();
$I->deleteDeliveryAddresses();