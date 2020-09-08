<?php

namespace Page\Api\V1;

/**
 * Запрос получения списка городов
 *
 * @author donos.v
 */
class Search extends BasePage
{
    /** @var string Селектор поля list */
    private $listFieldPath = '$.data.list';
    /** @var string Селектор поля listCount */
    private $listCountFieldPath = '$.data.listCount';

    /** @inheritdoc */
    public function getResponseType(array $dataType = [])
    {
        return parent::getResponseType([
            'text' => 'string',
            'page' => 'integer',
            'totalPages' => 'integer',
            'totalItems' => 'integer',  
            'listCount' => 'integer',            
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
            'ads' => [
                [
                    'html' => 'string',
                    'name' => 'string',
                ],
            ],
            'categories' => [
                [
                    'name' => 'string',
                    'photo_file_name' => 'string',
                    'category_path' => 'string',
                ],
            ],
            'itemsCategories' => [
                [
                    'alias' => 'string',
                    'id' => 'string',
                    'categoryName' => 'string',
                    'categoryPath' => 'string',
                    'itemsTotal' => 'string',
                ],
            ],
            // @todo добавить проверку actions
        ]);
    }

    /** @inheritdoc */
    public function getRoute()
    {
        return '/v1/search/';
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
