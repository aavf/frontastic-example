<?php

namespace Customer\CustomBundle\Domain;

use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\LifecycleEventDecorator\BaseImplementation;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result;

class ExtendedProduct extends Product
{
    /**
     * @var string
     */
    public $fancyProperty;
    //public fancyVariantProperty;
    public $simpleProperty;
}


class ProductWithFancyProperty extends BaseImplementation
{
    public function beforeGetProduct(
        ProductApi $productApi,
        $query,
        string $mode = ProductApi::QUERY_SYNC
    ): ?array {
        $query->loadDangerousInnerData = true;
        return null;
    }

    public function beforeQuery(
        $productApi,
        ProductQuery $query,
        string $mode = ProductApi::QUERY_SYNC
    ): ?array {
        $query->loadDangerousInnerData = true;
        return null;
    }

    public function afterGetProduct(
	ProductApi $productApi,
	?Product $product
    ): ?Product {
        return $this->extendProduct($product);
    }

    public function afterQuery($productApi,
	?Result $result
    ): ?Result {
        //var_dump($result);
        if ($result === null) {
            return null;
        }

        $result->items = array_map(
            function (Product $product) {
                return $this->extendProduct($product);
            },
            $result->items
        );

        return $result;
    }

    public function extendProduct(?Product $product)
    {
        if ($product === null) {
            return null;
        }

        $myExtendedProduct = new ExtendedProduct(get_object_vars($product));
        $myExtendedProduct->simpleProperty = 'TEST';
        $myExtendedProduct->fancyProperty = $product->dangerousInnerProduct->metafields;

        //$myExtendedProduct->fancyVariantProperty = $product->variants[0]
        //         ->dangerousInnerVariant['attributes']['fancyProperty'];

        return $myExtendedProduct;
    }
}
