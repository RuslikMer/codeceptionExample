<?php

namespace Page\Api\V1;

/**
 * Запрос получения карточки товара
 *
 * @author donos.v
 */
class GetProduct extends BasePage
{
    /** @var string Идентификатор пространства */
    public $spaceKey = 'msk_cl:';

    /** @inheritdoc */
    public function getResponseType(array $dataType = [])
    {
        return parent::getResponseType([
            'card' => [
                'fBrandId' => 'string',
                'isHasDiscountedItems' => 'boolean',
                'fBrandParentId' => 'string',
                'isConfItemStatus' => 'string',
                'countAccs' => 'string',
                'countDisc' => 'string',
                'countConf' => 'integer|null',
                'userPhotoQty' => 'string',
                'itemPhotoQty' => 'string',
                'middlePhotoQty' => 'string',
                'lastAvailDate' => 'string:date_format(d.m.Y)|null',
                'typeId' => 'string',
                'seoDescription' => 'string|null',
                'isCardifAvail' => 'string',
                'creditPricePerMonth' => 'integer',
                'creditPricePerMonthStr' => 'string',
                'photo' => [
                    'baseUrl' => 'string:url',
                    's' => 'string',
                    'b' => 'string',
                ],
                'searchDescription' => 'string',
                'virtualCategoryId' => 'integer',
                'gtdCountry' => 'string',
                'priceColumnOne' => 'string',
                'cashcarryStatus' => 'string',
                'realRating' => 'string',
                'isCashCarry' => 'boolean',
                'isAction' => 'string',
                'isActive' => 'string',
                'isDiscounted' => 'string',
                'countReviews' => 'string',
                'lastRevId' => 'string',
                'softStatus' => 'string',
                'storeOneStatus' => 'string',
                'storeTwoStatus' => 'string',
                'storeThreeStatus' => 'string',
                'storeFourStatus' => 'string',
                'storeFiveStatus' => 'string',
                'cashcarryOneStatus' => 'string',
                'cashcarryTwoStatus' => 'string',
                'cashcarryThreeStatus' => 'string',
                'cashcarryFourStatus' => 'string',
                'cashcarryFiveStatus' => 'string',
                'discountPercent' => 'integer|null',
                'oldPrice' => 'integer|null',
                'isAvailable' => 'boolean',
                'multiplicity' => 'integer',
                'stockStatusReg' => 'string',
                'id' => 'string',
                'clubPrice' => 'string',
                'name' => 'string',
                'shortName' => 'string',
                'shortCard' => 'string',
                'brandName' => 'string',
                'price' => 'string',
                'imageName' => 'string',
                'stockStatus' => 'string',
                'storeStatus' => 'string',
                'marketingStatus' => 'integer|null',
                'rating' => 'string',
                'totalOpinion' => 'string',
                'bonus' => 'string',
                'categoryId' => 'string',
                'categoryPath' => 'string',
                'categoryName' => 'string',
                'hideClubPrice' => 'integer|null',
                'photoPath' => 'string',
                'isHidden' => 'boolean',
                'transit' => 'string',
                'mainItemId' => 'integer|null',
            ],
            'properties' => [
                [
                    'groupName' => 'string',
                    'items' => [
                        [
                            'name' => 'string',
                            'value' => 'string',
                            'desc' => 'string|null',
                        ],
                    ],
                ],
            ],
            'photos' => [
                'baseUrl' => 'string:url',
                's' => [
                    [
                        'path' => 'string',
                        'pathBig' => 'string',
                        'pathSmall' => 'string',
                    ],
                ],
                'b' => [
                    [
                        'path' => 'string',
                        'pathBig' => 'string',
                        'pathSmall' => 'string',
                    ],
                ],
            ],
            'userPhotos' => [
                'baseUrl' => 'string:url',
                's' => [
                    [
                        'path' => 'string',
                        'pathBig' => 'string',
                        'pathSmall' => 'string',
                    ],
                ],
                'b' => [
                    [
                        'path' => 'string',
                        'pathBig' => 'string',
                        'pathSmall' => 'string',
                    ],
                ],
            ],
            'stockList' => [], // @todo описать внутренности
            'actions' => [], // @todo описать внутренности
            'inWishlist' => 'boolean',
            'servicesQuantity' => 'integer',
            'deliveryInfo' => [], // @todo описать внутренности
            'inCompareList' => 'boolean',
        ]);
    }

    /** @inheritdoc */
    public function getRoute()
    {
        return '/v1/product/{spaceKey}/{productId}/';
    }

    /** @inheritdoc */
    public function getHttpMethod()
    {
        return 'GET';
    }
}
