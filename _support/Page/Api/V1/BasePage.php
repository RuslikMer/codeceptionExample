<?php

namespace Page\Api\V1;

/**
 * Балоовый класс
 *
 * @author donos.v
 */
abstract class BasePage
{
    /**
     * Возвращает описание структуры ответа запроса
     *
     * @param array $dataType Вложенные поля для data
     * @return array
     */
    public function getResponseType(array $dataType = [])
    {
        return [
            'code' => 'integer',
            'msg' => 'string',
            'data' => $dataType ?: 'array',
            'isAuth' => 'boolean',
        ];
    }

    /**
     * Возвращает требуется ли авторизация для вызова метода
     * 
     * @return bool
     */
    public function isAuthRequired()
    {
        return false;
    }
    
    /**
     * Возвращает uri методода АПИ
     *
     * @return string
     */
    abstract public function getRoute();

    /**
     * Возвращает HTTP-метод выполнения запроса
     *
     * @return string
     */
    abstract public function getHttpMethod();
}
