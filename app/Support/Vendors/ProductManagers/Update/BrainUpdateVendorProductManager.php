<?php
/**
 * Update vendor product price.
 */

namespace App\Support\Vendors\ProductManagers\Update;


use App\Contracts\Vendor\VendorInterface;
use App\Models\VendorProduct;
use App\Support\ImageHandlers\ProductImageHandler;
use App\Support\Vendors\Adapters\Brain\BrainProductImagesDataAdapter;
use App\Support\Vendors\Adapters\Brain\BrainProductPriceAdapter;
use App\Support\Vendors\Adapters\Brain\BrainProductStocksDataAdapter;
use App\Support\Vendors\Providers\BrainUpdateProductProvider;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use stdClass;

class BrainUpdateVendorProductManager extends UpdateVendorProductManager
{

    /**
     * @var BrainUpdateProductProvider
     */
    private $provider;
    /**
     * @var BrainProductPriceAdapter
     */
    private $productPriceAdapter;
    /**
     * @var BrainProductStocksDataAdapter
     */
    private $stocksDataAdapter;
    /**
     * @var BrainProductImagesDataAdapter
     */
    private $imagesDataAdapter;

    /**
     * BrainUpdateVendorProductPriceManager constructor.
     * @param BrainUpdateProductProvider $provider
     * @param BrainProductPriceAdapter $productPriceAdapter
     * @param BrainProductStocksDataAdapter $stocksDataAdapter
     * @param VendorProduct $vendorProduct
     * @param BrainProductImagesDataAdapter $imagesDataAdapter
     * @param ProductImageHandler $productImageHandler
     */
    public function __construct(BrainUpdateProductProvider $provider, BrainProductPriceAdapter $productPriceAdapter, BrainProductStocksDataAdapter $stocksDataAdapter, VendorProduct $vendorProduct, BrainProductImagesDataAdapter $imagesDataAdapter, ProductImageHandler $productImageHandler)
    {
        parent::__construct($vendorProduct, $productImageHandler);

        $this->provider = $provider;
        $this->productPriceAdapter = $productPriceAdapter;
        $this->stocksDataAdapter = $stocksDataAdapter;
        $this->vendorProduct = $vendorProduct;
        $this->imagesDataAdapter = $imagesDataAdapter;
    }

    /**
     * Get vendor product.
     *
     * @param int $vendorProductId
     * @return Builder|Model
     */
    protected function getVendorProduct(int $vendorProductId)
    {
        return $this->vendorProduct->newQuery()
            ->where([
                ['vendors_id', '=', VendorInterface::BRAIN],
                ['vendor_product_id', '=', $vendorProductId],
            ])
            ->with(['product' => function($query){
                $query->with('primaryImage', 'vendorProducts', 'availableStorageProducts', 'availableVendorProducts', 'expectingStorageProducts', 'expectingVendorProducts');
            }])
            ->firstOrFail();
    }

    /**
     * Create product data for update.
     *
     * @param int $vendorProductId
     * @throws Exception
     */
    protected function prepareVendorProductData(int $vendorProductId)
    {
        // retrieve product data from vendor
        $vendorProductData = $this->provider->getProductData($vendorProductId);

        $vendorUsdCourse = $this->getCashUsdCourse($this->provider->getCoursesData());

        // prepare vendor product data
        $this->vendorProductData = array_merge($this->prepareProductData($vendorProductData), $this->productPriceAdapter->prepareVendorProductPrices($vendorProductData, $vendorUsdCourse));

        // prepare vendor product stocks data
        $this->vendorProductStocksData = $this->stocksDataAdapter->prepareVendorProductStocksData($vendorProductData);
    }

    /**
     * Prepare product images for update.
     *
     * @param int $vendorProductId
     * @throws Exception
     */
    protected function prepareProductImagesData(int $vendorProductId)
    {
        // get content data
        $productContentData = $this->provider->getContentData($vendorProductId);

        // set product images
        $this->imagesData = $this->imagesDataAdapter->prepareVendorProductImagesData($productContentData->list[0]->images);
    }

    /**
     * Get cash usd course.
     *
     * @param array $vendorCoursesData
     * @return float
     * @throws Exception
     */
    private function getCashUsdCourse(array $vendorCoursesData): float
    {
        if (empty($vendorCoursesData)){
            throw new Exception('Can not update vendor product. Exchange rate is missing');
        }

        $cashCourseItem = collect($vendorCoursesData)->where('currencyID', '=', 2)->first();

        if ($cashCourseItem){
            return $cashCourseItem->value;
        }else{
            return null;
        }
    }

    /**
     * Prepare product data
     *
     * @param stdClass $vendorProductData
     * @return array
     */
    private function prepareProductData(stdClass $vendorProductData):array
    {
        return [
            'is_archive' => (int)$vendorProductData->is_archive,
            'warranty' => (int)$vendorProductData->warranty,
        ];
    }
}
