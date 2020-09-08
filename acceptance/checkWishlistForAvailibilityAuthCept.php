<?php
// @testexec_usertype normal

$I = new AcceptanceTester($scenario);
$I->am("authorized user");
$I->wantTo('check wishList for availibility');
$I->openHomePage();
$I->doLogin();
$I->wishListCleanUp();
$I->goToProductFromMainPage();
$I->addToWishList();
$I->goToWishListTopRight();
$I->activateOrDisableWishListVisibility('active');
$wishListLink = $I->getWishListLink();
$wishListItems = $I->getItemsFromWishList(\Page\Profile::OWN_WISHLIST);
$I->checkWishListForVisibility($wishListLink,'active', $wishListItems);
$I->switchToPreviousTab();
$I->activateOrDisableWishListVisibility('disable');
$I->checkWishListForVisibility($wishListLink,'disable', '',true);
