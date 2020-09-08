<?php

namespace Page\Api\V1\Basket;

use Page\Api\V1\BasePage;

/**
 * Запрос указания кол-ва бонус для оплаты заказа 
 *
 * @author donos.v
 */
class SetBonus extends BasePage
{
    /** Указан не верный пароль или email */
    const BAD_REQUEST_INCORRECT_PASSWORD = 4007;
    
    /** Не переданы необходимые для подтверждения действия логин и пароль  */
    const UNAUTHORIZED_PASSWORD_REQUIRED = 4011;    
    
    /** @inheritdoc */
    public function getRoute()
    {
        return '/v1/basket/bonus/set/';
    }

    /** @inheritdoc */
    public function getHttpMethod()
    {
        return 'POST';
    }
}
