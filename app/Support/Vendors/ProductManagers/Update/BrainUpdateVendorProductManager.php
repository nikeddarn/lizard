<?php
/**
 * Update vendor product price.
 */

namespace App\Support\Vendors\ProductManagers\Update;


use App\Contracts\Vendor\VendorInterface;
use App\Models\VendorProduct;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\ProductBadges\ProductBadges;
use App\Support\ProductPrices\VendorProductPrice;
use App\Support\Vendors\Adapters\BrainProductPriceAdapter;
use App\Support\Vendors\Adapters\BrainProductStocksDataAdapter;
use App\Support\Vendors\Providers\BrainUpdateProductProvider;
use Exception;
use Illuminate\Support\Facades\Cache;

class BrainUpdateVendorProductManager extends UpdateVendorProductManager
{
    /**
     * @var string
     */
    const USD_COURSE_CACHE_KEY = 'vendor_' . VendorInterface::BRAIN . '_session_id';

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
     * BrainUpdateVendorProductPriceManager constructor.
     * @param VendorProduct $vendorProduct
     * @param BrainUpdateProductProvider $provider
     * @param VendorProductPrice $productPrice
     * @param BrainProductPriceAdapter $productPriceAdapter
     * @param BrainProductStocksDataAdapter $stocksDataAdapter
     * @param ProductAvailability $productAvailability
     * @param ProductBadges $productBadges
     */
    public function __construct(VendorProduct $vendorProduct, BrainUpdateProductProvider $provider, VendorProductPrice $productPrice, BrainProductPriceAdapter $productPriceAdapter, BrainProductStocksDataAdapter $stocksDataAdapter, ProductAvailability $productAvailability, ProductBadges $productBadges)
    {
        parent::__construct($vendorProduct, $productPrice, $productAvailability, $productBadges);

        $this->provider = $provider;
        $this->productPriceAdapter = $productPriceAdapter;
        $this->stocksDataAdapter = $stocksDataAdapter;
    }

    /**
     * Get vendor product.
     *
     * @param int $vendorProductId
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     */
    protected function getVendorProduct(int $vendorProductId)
    {
        return $this->vendorProduct->newQuery()
            ->where([
                ['vendors_id', '=', VendorInterface::BRAIN],
                ['vendor_product_id', '=', $vendorProductId],
            ])
            ->with('product')
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

        // get usd course
        if (Cache::has(self::USD_COURSE_CACHE_KEY)){
            $vendorUsdCourse = (float)Cache::get(self::USD_COURSE_CACHE_KEY);
        }else{
            $vendorUsdCourse = $this->getCashUsdCourse($this->provider->getCoursesData());
            if ($vendorUsdCourse > 0) {
                Cache::put(self::USD_COURSE_CACHE_KEY, $vendorUsdCourse, config('vendor.vendor_exchange_rate_ttl.' . VendorInterface::BRAIN));
            }else{
                throw new Exception('Can not get exchange rate from vendor');
            }
        }

        // prepare vendor product prices
        $this->vendorProductPricesData = $this->productPriceAdapter->prepareVendorProductPrices($vendorProductData, $vendorUsdCourse);

        // prepare vendor product stocks data
        $this->vendorProductStocksData = $this->stocksDataAdapter->prepareVendorProductStocksData($vendorProductData);
    }

    /**
     * Get cash usd course.
     *
     * @param $vendorCourses
     * @return float
     * @throws Exception
     */
    private function getCashUsdCourse(array $vendorCourses): float
    {
        $cashCourse = collect($vendorCourses)->where('currencyID', '=', 2)->first();

        if ($cashCourse) {
            return $cashCourse->value;
        } else {
            throw new Exception('Currency course is missing');
        }
    }
}
