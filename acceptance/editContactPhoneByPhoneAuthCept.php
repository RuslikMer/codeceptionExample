<?php

$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('edit contact phone');
$I->openHomePage();
$I->doLoginFail(PHONE_EDIT_CONTACT_FIRST, PASSWORD_EDIT_CONTACT);
$loginError = $I->getNumberOfElements(['xpath' => '//div[@class="message-main middle error"]']);
codecept_debug('error message ' . $loginError);
if($loginError == 0){
    $I->goToProfile();
    $I->editContactPhoneNumber(PHONE_EDIT_CONTACT_SECOND, PHONE_EDIT_CONTACT_FIRST, USERNAME_EDIT_CONTACT_PHONE_BY_PHONE);
}

$editPhone = $I->haveFriend('editPhone');
$editPhone->does(function (AcceptanceTester $I) {
    $I->openHomePage();
    $I->doLogin(PHONE_EDIT_CONTACT_SECOND, PASSWORD_REGISTRATION);
    $I->goToProfile();
    $I->editContactPhoneNumber(PHONE_EDIT_CONTACT_FIRST, PHONE_EDIT_CONTACT_SECOND, USERNAME_EDIT_CONTACT_PHONE_BY_PHONE);
});
$editPhone->leave();