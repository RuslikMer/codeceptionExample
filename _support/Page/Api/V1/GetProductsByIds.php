<?php

namespace Page\Api\V1;

/**
 * Запрос получения списка городов
 *
 * @author donos.v
 */
class GetProductsByIds extends BasePage
{
    /** @var string Идентификатор пространства */
    public $spaceKey = 'msk_cl:';

    /** @inheritdoc */
    public function getResponseType(array $dataType = [])
    {
        return parent::getResponseType([
            'list' => [
                [
                    'transit' => 'boolean',
                    'isAvailable' => 'boolean',
                    'mainItemId' => 'string|null',
                    'priceColumnOne' => 'float|null',
                    'cashcarryStatus' => 'string|integer',
                    'realRating' => 'string|float',
                    'isCashCarry' => 'boolean',
                    'isAction' => 'boolean',
                    'isActive' => 'boolean',
                    'isDiscounted' => 'boolean',
                    'countReviews' => 'integer',
                    'lastRevId' => 'integer',
                    'softStatus' => 'string|integer',
                    'storeOneStatus' => 'integer',
                    'storeTwoStatus' => 'integer',
                    'storeThreeStatus' => 'integer',
                    'storeFourStatus' => 'integer',
                    'storeFiveStatus' => 'integer',
                    'cashcarryOneStatus' => 'integer',
                    'cashcarryTwoStatus' => 'integer',
                    'cashcarryThreeStatus' => 'integer',
                    'cashcarryFourStatus' => 'integer',
                    'cashcarryFiveStatus' => 'integer',
                    'discountPercent' => 'integer',
                    'oldPrice' => 'integer',
                    'multiplicity' => 'integer',
                    'stockStatusReg' => 'integer',
                    'id' => 'string',
                    'clubPrice' => 'integer',
                    'name' => 'string',
                    'shortName' => 'string',
                    'shortCard' => 'string',
                    'brandName' => 'string:regex(/^[A-Z][A-Z0-9]*$/)',
                    'price' => 'integer',
                    'imageName' => 'string',
                    'stockStatus' => 'string|integer',
                    'storeStatus' => 'string|integer',
                    'marketingStatus' => 'integer|null',
                    'rating' => 'string|float',
                    'totalOpinion' => 'integer',
                    'bonus' => 'integer',
                    'categoryId' => 'integer',
                    'categoryPath' => 'string',
                    'categoryName' => 'string',
                    'hideClubPrice' => 'boolean',
                    'photoPath' => 'string',
                    'isHidden' => 'boolean',
                ],
            ],
        ]);
    }

    /** @inheritdoc */
    public function getRoute()
    {
        return "/v1/products/ids/{$this->spaceKey}/";
    }

    /** @inheritdoc */
    public function getHttpMethod()
    {
        return 'GET';
    }
}
