<?php
/**
 * Update vendor product.
 */

namespace App\Support\Vendors\ProductManagers\Update;


use App\Events\Vendor\VendorProductUpdated;
use App\Models\VendorProduct;
use App\Support\ImageHandlers\ProductImageHandler;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

abstract class UpdateVendorProductManager
{
    /**
     * @var array
     */
    protected $vendorProductData;
    /**
     * @var array
     */
    protected $vendorProductStocksData;
    /**
     * @var array
     */
    protected $imagesData;
    /**
     * @var VendorProduct
     */
    protected $vendorProduct;
    /**
     * @var ProductImageHandler
     */
    private $productImageHandler;

    /**
     * UpdateVendorProductManager constructor.
     * @param VendorProduct $vendorProduct
     * @param ProductImageHandler $productImageHandler
     */
    public function __construct(VendorProduct $vendorProduct, ProductImageHandler $productImageHandler)
    {
        $this->vendorProduct = $vendorProduct;
        $this->productImageHandler = $productImageHandler;
    }

    /**
     * Update vendor product price.
     * Update local product price.
     *
     * @param int $vendorProductId
     * @throws Exception
     */
    public function updateVendorProduct(int $vendorProductId)
    {
        $vendorProduct = $this->getVendorProduct($vendorProductId);

        // retrieve product data from vendor and prepare it for update
        $this->prepareVendorProductData($vendorProductId);

        if (!$vendorProduct->product->primaryImage){
            $this->prepareProductImagesData($vendorProductId);
        }

        try {
            DB::beginTransaction();

            // update product
            $this->updateProductProperties($vendorProduct);

            DB::commit();

        } catch (Throwable $exception) {
            DB::rollBack();
            throw new  Exception($exception->getMessage());
        }

        // fire event
        event(new VendorProductUpdated($vendorProduct->product));
    }

    /**
     * Get vendor product.
     *
     * @param int $vendorProductId
     * @return Builder|Model
     */
    abstract protected function getVendorProduct(int $vendorProductId);

    /**
     * Prepare product data for update.
     *
     * @param int $vendorProductId
     * @return void
     */
    abstract protected function prepareVendorProductData(int $vendorProductId);

    /**
     * Prepare product images for update.
     *
     * @param int $vendorProductId
     * @return void
     */
    abstract protected function prepareProductImagesData(int $vendorProductId);

    /**
     * Update vendor product and product data.
     *
     * @param VendorProduct|Model $vendorProduct
     * @throws Exception
     */
    private function updateProductProperties(VendorProduct $vendorProduct)
    {
        // update vendor product prices
        if (isset($this->vendorProductData)) {
            $this->updateProductData($vendorProduct);
        }

        // update vendor product stocks data
        if (isset($this->vendorProductStocksData)) {
            $this->updateVendorProductStocksData($vendorProduct);
        }

        if (!empty($this->imagesData)) {
            $this->productImageHandler->insertVendorProductImages($vendorProduct->product, $this->imagesData);
        }
    }

    /**
     * Update product prices.
     *
     * @param VendorProduct $vendorProduct
     * @throws Exception
     */
    private function updateProductData(VendorProduct $vendorProduct)
    {
        // update vendor product prices
        $vendorProduct->update($this->vendorProductData);
    }

    /**
     * Sync vendor product stocks.
     *
     * @param VendorProduct|Model $vendorProduct
     */
    private function updateVendorProductStocksData(VendorProduct $vendorProduct)
    {
        // collect stocks data
        $collectedStocksData = collect($this->vendorProductStocksData);

        // calculate availability status
        $availabilityStatus = (int)$collectedStocksData->max('available');

        // calculate expected time
        $expectedTime = $collectedStocksData->min('available_time');

        // update vendor product availability
        $vendorProduct->update([
            'available' => $availabilityStatus,
            'available_time' => $expectedTime,
        ]);

        // sync vendor product's stocks
        $vendorProduct->vendorStocks()->sync($this->vendorProductStocksData);
    }
}
