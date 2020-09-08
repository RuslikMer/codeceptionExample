<?php

namespace Page\Api\V1\Basket;

use Page\Api\V1\BasePage;

/**
 * Запрос задания опций доставки заказу
 *
 * @author donos.v
 */
class SetDeliveryOptions extends BasePage
{
    /** @inheritdoc */
    public function getRoute()
    {
        return "/v1/basket/delivery/set/";
    }

    /** @inheritdoc */
    public function getHttpMethod()
    {
        return 'POST';
    }
}
