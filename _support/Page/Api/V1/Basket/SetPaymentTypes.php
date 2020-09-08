<?php

namespace Page\Api\V1\Basket;

use Page\Api\V1\BasePage;

/**
 * Запрос задания типа оплаты для платежа
 *
 * @author donos.v
 */
class SetPaymentTypes extends BasePage
{
    /** @inheritdoc */
    public function getResponseType()
    {
        return parent::getResponseType();
    }

    /** @inheritdoc */
    public function getRoute()
    {
        return "/v1/basket/payment/set/";
    }

    /** @inheritdoc */
    public function getHttpMethod()
    {
        return 'POST';
    }
}
