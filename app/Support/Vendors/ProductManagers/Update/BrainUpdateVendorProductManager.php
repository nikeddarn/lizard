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
    public function __construct(VendorProduct $vendorProduct, BrainUpdateProductProvider $provider, VendorProductPrice $productPrice, BrainProductPriceAdapter $productPriceAdapter, BrainProductStocksDataAdapter $stocksDataAdapter,  ProductAvailability $productAvailability, ProductBadges $productBadges)
    {
        parent::__construct($vendorProduct, $productPrice, $productAvailability, $productBadges);

        $this->vendorId = VendorInterface::BRAIN;
        $this->provider = $provider;
        $this->productPriceAdapter = $productPriceAdapter;
        $this->stocksDataAdapter = $stocksDataAdapter;
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
        $vendorData = $this->provider->getProductData($vendorProductId);

        // get product data
        $vendorProductData = $vendorData['product'];

        // get course
        $course = $this->getCashUsdCourse($vendorData['course']);

        // prepare vendor product prices
        $this->vendorProductPricesData = $this->productPriceAdapter->prepareVendorProductPrices($vendorProductData, $course);

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

        if ($cashCourse){
            return $cashCourse->value;
        }else{
            throw new Exception('Currency course is missing');
        }
    }
}
