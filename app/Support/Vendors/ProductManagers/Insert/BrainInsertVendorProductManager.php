<?php
/**
 * Brain product manager.
 */

namespace App\Support\Vendors\ProductManagers\Insert;


use App\Contracts\Vendor\VendorInterface;
use App\Support\ImageHandlers\VendorProductImageHandler;
use App\Support\ProductBadges\ProductBadges;
use App\Support\Repositories\ProductRepository;
use App\Support\Vendors\Adapters\BrainProductAttributesDataAdapter;
use App\Support\Vendors\Adapters\BrainProductBrandDataAdapter;
use App\Support\Vendors\Adapters\BrainProductCommentsDataAdapter;
use App\Support\Vendors\Adapters\BrainProductDataAdapter;
use App\Support\Vendors\Adapters\BrainProductImagesDataAdapter;
use App\Support\Vendors\Adapters\BrainProductStocksDataAdapter;
use App\Support\Vendors\Providers\BrainInsertProductProvider;
use Exception;

class BrainInsertVendorProductManager extends InsertVendorProductManager
{
    /**
     * @var BrainInsertProductProvider
     */
    private $productProvider;
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
     * BrainInsertInsertProductManager constructor.
     * @param ProductRepository $productRepository
     * @param BrainInsertProductProvider $productProvider
     * @param BrainProductDataAdapter $productDataAdapter
     * @param BrainProductAttributesDataAdapter $attributesDataAdapter
     * @param BrainProductStocksDataAdapter $stocksDataAdapter
     * @param BrainProductImagesDataAdapter $imagesDataAdapter
     * @param BrainProductCommentsDataAdapter $commentsDataAdapter
     * @param BrainProductBrandDataAdapter $brandDataAdapter
     * @param ProductBadges $productBadges
     * @param VendorProductImageHandler $productImageHandler
     */
    public function __construct(ProductRepository $productRepository, BrainInsertProductProvider $productProvider, BrainProductDataAdapter $productDataAdapter, BrainProductAttributesDataAdapter $attributesDataAdapter, BrainProductStocksDataAdapter $stocksDataAdapter, BrainProductImagesDataAdapter $imagesDataAdapter, BrainProductCommentsDataAdapter $commentsDataAdapter, BrainProductBrandDataAdapter $brandDataAdapter, ProductBadges $productBadges, VendorProductImageHandler $productImageHandler)
    {
        parent::__construct($productRepository, $productBadges, $productImageHandler);
        $this->productProvider = $productProvider;
        $this->productDataAdapter = $productDataAdapter;
        $this->attributesDataAdapter = $attributesDataAdapter;
        $this->stocksDataAdapter = $stocksDataAdapter;
        $this->imagesDataAdapter = $imagesDataAdapter;
        $this->commentsDataAdapter = $commentsDataAdapter;
        $this->brandDataAdapter = $brandDataAdapter;

        $this->vendorId = VendorInterface::BRAIN;
    }

    /**
     * Retrieve and prepare product data.
     *
     * @param int $vendorProductId
     * @throws Exception
     */
    protected function createProductData(int $vendorProductId){
        // receive whole product data
        $productData = $this->productProvider->getProductData($vendorProductId);

        // get get product data entities
        $vendorUsdCourse = $this->getCashUsdCourse($productData['course']);
        $productDataRu = $productData['product_data_ru'];
        $productContentDataRu = $productData['product_content_data_ru']->list[0];
        $productContentDataUa = $productData['product_content_data_ua']->list[0];
        $comments = $productData['comments']->list;

        // set product model data
        $this->productData = $this->productDataAdapter->prepareProductData($productDataRu, $productContentDataRu, $productContentDataUa);

        // set vendor product model data
        $this->vendorProductData = $this->productDataAdapter->prepareVendorProductData($productDataRu, $vendorUsdCourse);

        // set product attributes
        $this->attributesData = $this->attributesDataAdapter->prepareProductAttributesData($productContentDataRu->options, $productContentDataUa->options);

        // set product stocks
        $this->stocksData = $this->stocksDataAdapter->prepareVendorProductStocksData($productDataRu);

        // set product images
        $this->imagesData = $this->imagesDataAdapter->prepareVendorProductImagesData($productContentDataRu->images);

        // set product comments
        $this->commentsData = $this->commentsDataAdapter->prepareVendorProductCommentsData($comments);

        // add product brand
        $productBrandData = $this->brandDataAdapter->prepareVendorProductBrandData((int)$productDataRu->vendorID);

        $this->attributesData = $this->attributesData + $productBrandData;
    }

    /**
     * Get cash usd course.
     *
     * @param $vendorCourses
     * @return float
     * @throws Exception
     */
    private function getCashUsdCourse($vendorCourses): float
    {
        $cashCourse = collect($vendorCourses)->where('currencyID', '=', 2)->first();

        if ($cashCourse){
            return $cashCourse->value;
        }else{
            throw new Exception('Currency course is missing');
        }
    }
}
