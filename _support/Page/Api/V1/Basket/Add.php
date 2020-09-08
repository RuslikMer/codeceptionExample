<?php

namespace Page\Api\V1\Basket;

use Page\Api\V1\BasePage;

/**
 * Запрос добавления товара в корзину
 *
 * @author donos.v
 */
class Add extends BasePage
{
    /** @inheritdoc */
    public function getRoute()
    {
        return '/v1/basket/add/';
    }

    /** @inheritdoc */
    public function getHttpMethod()
    {
        return 'POST';
    }
}
