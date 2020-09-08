<?php

namespace Page\Api\V1;

/**
 * Запрос авторизации
 *
 * @author donos.v
 */
class Login extends BasePage
{
    /** @inheritdoc */
    public function getResponseType(array $dataType = [])
    {
        return [];
    }

    /** @inheritdoc */
    public function getRoute()
    {
        return '/mauth/login/';
    }

    /** @inheritdoc */
    public function getHttpMethod()
    {
        return 'POST';
    }
}
