<?php
/**
 * Update vendor product.
 */

namespace App\Support\Vendors\ProductManagers\Update;


use App\Events\Vendor\VendorProductUpdated;
use App\Models\VendorProduct;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductBadges\ProductBadges;
use App\Support\ProductPrices\VendorProductPrice;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

abstract class UpdateVendorProductManager
{
    /**
     * @var array
     */
    protected $vendorProductPricesData;
    /**
     * @var array
     */
    protected $vendorProductStocksData;
    /**
     * @var VendorProduct
     */
    protected $vendorProduct;
    /**
     * @var VendorProductPrice
     */
    private $productPrice;
    /**
     * @var ProductAvailability
     */
    private $productAvailability;
    /**
     * @var ProductBadges
     */
    private $productBadges;

    /**
     * UpdateVendorProductPriceManager constructor.
     * @param VendorProduct $vendorProduct
     * @param VendorProductPrice $productPrice
     * @param ProductAvailability $productAvailability
     * @param ProductBadges $productBadges
     */
    public function __construct(VendorProduct $vendorProduct, VendorProductPrice $productPrice, ProductAvailability $productAvailability, ProductBadges $productBadges)
    {
        $this->vendorProduct = $vendorProduct;
        $this->productPrice = $productPrice;
        $this->productAvailability = $productAvailability;
        $this->productBadges = $productBadges;
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

        DB::beginTransaction();

        try {
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
     * Prepare product data for update.
     *
     * @param int $vendorProductId
     * @return void
     */
    abstract protected function prepareVendorProductData(int $vendorProductId);

    /**
     * Update vendor product and product data.
     *
     * @param VendorProduct $vendorProduct
     * @throws Exception
     */
    private function updateProductProperties(VendorProduct $vendorProduct)
    {
        // update vendor product prices
        if (isset($this->vendorProductPricesData)) {
            $this->updateProductPrices($vendorProduct);
        }

        // update vendor product stocks data
        if (isset($this->vendorProductStocksData)) {
            $this->updateVendorProductStocksData($vendorProduct);
        }
    }

    /**
     * Update product prices.
     *
     * @param VendorProduct $vendorProduct
     * @throws Exception
     */
    private function updateProductPrices(VendorProduct $vendorProduct)
    {
        // update vendor product prices
        $vendorProduct->update($this->vendorProductPricesData);
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
