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

        $vendorUsdCourse = $this->getCashUsdCourse($this->provider->getCoursesData());

        // prepare vendor product prices
        $this->vendorProductPricesData = $this->productPriceAdapter->prepareVendorProductPrices($vendorProductData, $vendorUsdCourse);

        // prepare vendor product stocks data
        $this->vendorProductStocksData = $this->stocksDataAdapter->prepareVendorProductStocksData($vendorProductData);
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
}
