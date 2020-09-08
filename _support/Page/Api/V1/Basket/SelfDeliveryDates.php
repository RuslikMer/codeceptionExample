<?php

namespace Page\Api\V1\Basket;

use Page\Api\V1\BasePage;

/**
 * Запрос получения дат самовывоза
 *
 * @author donos.v
 */
class SelfDeliveryDates extends BasePage
{
    public $spaceKey = 'msk_cl:cmamur';

    /** @inheritdoc */
    public function getRoute()
    {
        return "/v1/basket/selfdelivery/dates/{$this->spaceKey}/";
    }

    /** @inheritdoc */
    public function getHttpMethod()
    {
        return 'GET';
    }
}
