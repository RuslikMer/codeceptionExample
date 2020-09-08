<?php

namespace Page\Api\V1;

/**
 * Запрос получения списка городов
 *
 * @author donos.v
 */
class GetCities extends BasePage
{
    /** @var string Селектор поля list */
    private $listFieldPath = '$.data.list';
    /** @var string Селектор поля listCount */
    private $listCountFieldPath = '$.data.listCount';

    /** @inheritdoc */
    public function getResponseType(array $dataType = [])
    {
        return parent::getResponseType([
            'list' => [
                'msk_cl:' => [
                    'isMainInRegion' => 'boolean',
                    'isSpecial' => 'boolean',
                    'spaceId' => 'string',
                    'space' => [],
                    'pupSpaceId' => 'string',
                    'isNew' => 'boolean',
                    'cityName' => 'string',
                    'mainCityNameInDeclination' => 'string|null',
                    'isCity' => 'boolean|null',
                    'cityNameInDeclination' => 'string|null',
                    'localityId' => 'string|null',
                    'utcDelta' => 'integer',
                    'storeName' => 'string',
                    'storeAddress' => 'string',
                    'contactPhone' => 'string',
                ] 
            ],
            'listCount' => 'integer',
        ]);
    }

    /** @inheritdoc */
    public function getRoute()
    {
        return '/v1/cities/';
    }

    /** @inheritdoc */
    public function getHttpMethod()
    {
        return 'GET';
    }

    /**
     * Возращает селектор поля list
     *
     * @return string Селектор поля list
     */
    public function getListFieldPath()
    {
        return $this->listFieldPath;
    }

    /**
     * Возращает селектор поля listCount
     *
     * @return string Селектор поля listCount
     */
    public function getListCountFieldPath()
    {
        return $this->listCountFieldPath;
    }
}
