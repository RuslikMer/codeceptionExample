<?php

namespace Page\Api\V1\Basket;

use Page\Api\V1\BasePage;

/**
 * Запрос для получения опций подтверждения заказа
 *
 * @author donos.v
 */
class GetConfirmOptions extends BasePage
{
    /** @inheritdoc */
    public function getResponseType()
    {
        return parent::getResponseType([
            'minDateToApplyInstallService' => 'integer',
            'orderHasInstallServicesInBasket' => 'boolean',
            'isCardIfExists' => 'boolean',
            'isUserB2b' => 'boolean',
            'whereApplyInstallService' => 'string',
            'isDeliveryMini' => 'boolean',
        ]);
    }

    /** @inheritdoc */
    public function getRoute()
    {
        return "/v1/basket/confirm/options/";
    }

    /** @inheritdoc */
    public function getHttpMethod()
    {
        return 'GET';
    }
}
