<?php
/**
 * Brain product data adapter.
 */

namespace App\Support\Vendors\Adapters\Brain;

use stdClass;

class BrainDataAdapter
{
    /**
     * @var BrainProductDataAdapter
     */
    private $productDataAdapter;
    /**
     * @var BrainProductAttributesDataAdapter
     */
    private $attributesDataAdapter;
    /**
     * @var BrainProductStocksDataAdapter
     */
    private $stocksDataAdapter;
    /**
     * @var BrainProductImagesDataAdapter
     */
    private $imagesDataAdapter;
    /**
     * @var BrainProductCommentsDataAdapter
     */
    private $commentsDataAdapter;
    /**
     * @var BrainProductBrandDataAdapter
     */
    private $brandDataAdapter;

    /**
     * BrainDataAdapter constructor.
     * @param BrainProductDataAdapter $productDataAdapter
     * @param BrainProductAttributesDataAdapter $attributesDataAdapter
     * @param BrainProductStocksDataAdapter $stocksDataAdapter
     * @param BrainProductImagesDataAdapter $imagesDataAdapter
     * @param BrainProductCommentsDataAdapter $commentsDataAdapter
     * @param BrainProductBrandDataAdapter $brandDataAdapter
     */
    public function __construct(BrainProductDataAdapter $productDataAdapter, BrainProductAttributesDataAdapter $attributesDataAdapter, BrainProductStocksDataAdapter $stocksDataAdapter, BrainProductImagesDataAdapter $imagesDataAdapter, BrainProductCommentsDataAdapter $commentsDataAdapter, BrainProductBrandDataAdapter $brandDataAdapter)
    {

        $this->productDataAdapter = $productDataAdapter;
        $this->attributesDataAdapter = $attributesDataAdapter;
        $this->stocksDataAdapter = $stocksDataAdapter;
        $this->imagesDataAdapter = $imagesDataAdapter;
        $this->commentsDataAdapter = $commentsDataAdapter;
        $this->brandDataAdapter = $brandDataAdapter;
    }

    /**
     * Prepare product data.
     *
     * @param $productDataRu
     * @param $productContentDataRu
     * @param $productContentDataUa
     * @return array
     * @throws \Exception
     */
    public function prepareProductData(stdClass $productDataRu, stdClass $productContentDataRu, stdClass $productContentDataUa)
    {
        return $this->productDataAdapter->prepareProductData($productDataRu, $productContentDataRu, $productContentDataUa);
    }

    /**
     * Prepare vendor product data.
     *
     * @param $productDataRu
     * @param $vendorUsdCourse
     * @return array
     * @throws \Exception
     */
    public function prepareVendorProductData(stdClass $productDataRu, float $vendorUsdCourse)
    {
        return $this->productDataAdapter->prepareVendorProductData($productDataRu, $vendorUsdCourse);
    }

    /**
     * Prepare attributes data.
     *
     * @param array $productAttributesDataRu
     * @param array $productAttributesDataUa
     * @param int $productVendorID
     * @return array
     */
    public function prepareProductAttributesData(array $productAttributesDataRu, array $productAttributesDataUa, int $productVendorID)
    {
        $productAttributes = $this->attributesDataAdapter->prepareProductAttributesData($productAttributesDataRu, $productAttributesDataUa);

        $productBrandAttribute = $this->brandDataAdapter->prepareVendorProductBrandData($productVendorID);

        return $productAttributes + $productBrandAttribute;
    }

    /**
     * Prepare product stocks data.
     *
     * @param $productDataRu
     * @return array
     */
    public function prepareVendorProductStocksData(stdClass $productDataRu)
    {
        return $this->stocksDataAdapter->prepareVendorProductStocksData($productDataRu);
    }

    /**
     * Prepare product images data.
     *
     * @param array $imagesData
     * @return array
     */
    public function prepareVendorProductImagesData(array $imagesData)
    {
        return $this->imagesDataAdapter->prepareVendorProductImagesData($imagesData);
    }

    /**
     * Prepare product comments data.
     *
     * @param array $productComments
     * @return array
     */
    public function prepareVendorProductCommentsData(array $productComments)
    {
        return $this->commentsDataAdapter->prepareVendorProductCommentsData($productComments);
    }
}
