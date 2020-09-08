<?php

namespace Page\Api\V1\Basket;

use Page\Api\V1\BasePage;

/**
 * Запрос для получения опций подтверждения заказа
 *
 * @author donos.v
 */
class SetConfirmOptions extends BasePage
{
    /** Код перенаправления при успешном завершении заказа */
    const SEE_OTHER_ORDER_COMPLETE = 3033;

    /** @inheritdoc */
    public function getResponseType()
    {
        return parent::getResponseType([
            'hash' => 'string:regex(/[0-9a-f]{40}/)',
        ]);
    }

    /** @inheritdoc */
    public function getRoute()
    {
        return "/v1/basket/confirm/set/";
    }

    /** @inheritdoc */
    public function getHttpMethod()
    {
        return 'POST';
    }
}
