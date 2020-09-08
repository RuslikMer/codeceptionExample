<?php

namespace Page\Api\V1\Basket;

use Page\Api\V1\BasePage;

/**
 * Запрос получения методов оплаты, доступных для заказа
 *
 * @author donos.v
 */
class GetPaymentTypes extends BasePage
{
    /** Виртуальный тип оплаты - безналичная */
    const LOCAL_TYPE_CASHLESS = 1;

    /** Виртуальный тип оплаты - наличными */
    const LOCAL_TYPE_CASH = 2;

    /** Виртуальный тип оплаты - картами VISA/MASTER CARD */
    const LOCAL_TYPE_CARDS = 3;

    /** Виртуальный тип оплаты - кредит */
    const LOCAL_TYPE_CREDIT = 4;

    /** Виртуальный тип оплаты - Yandex money */
    const LOCAL_TYPE_YANDEX = 6;

    /** Виртуальный тип оплаты - Терминал оплаты */
    const LOCAL_TYPE_TERMINAL = 7;

    /** Виртуальный тип оплаты - Webmoney */
    const LOCAL_TYPE_WEBMONEY = 8;

    /** @inheritdoc */
    public function getResponseType()
    {
        return parent::getResponseType([
            'list' => [
                [
                    'type' => 'integer',
                    'img' => 'string',
                    'title' => 'string',
                    'localPaymentType' => 'integer',
                ]
            ],
            'listCount' => 'integer',
        ]);
    }

    /** @inheritdoc */
    public function getRoute()
    {
        return "/v1/basket/payment/methods/";
    }

    /** @inheritdoc */
    public function getHttpMethod()
    {
        return 'GET';
    }
}
