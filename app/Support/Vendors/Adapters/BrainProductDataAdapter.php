<?php
/**
 * Adapt Brain product data for insert.
 */

namespace App\Support\Vendors\Adapters;


use App\Contracts\Vendor\VendorInterface;
use Exception;
use Illuminate\Support\Str;
use stdClass;

class BrainProductDataAdapter extends BrainProductPriceAdapter
{
    /**
     * @var int
     */
    const VENDOR_ID = VendorInterface::BRAIN;

    /**
     * Prepare product data.
     *
     * @param stdClass $productDataRu
     * @param stdClass $productContentDataRu
     * @param stdClass $productContentDataUa
     * @return array
     * @throws Exception
     */
    public function prepareProductData(stdClass $productDataRu, stdClass $productContentDataRu, stdClass $productContentDataUa)
    {
        if (!($productDataRu->name && $productContentDataUa->name)){
            throw new Exception('Missing necessary product names');
        }

        return [
            'code' => $productDataRu->product_code,
            'articul' => $productDataRu->articul,
            'min_order_quantity' => (int)$productDataRu->min_order_amount,
            'warranty' => (int)$productDataRu->warranty,
            'is_archive' => (int)$productDataRu->is_archive,
            'url' => Str::slug($productDataRu->name),

            'name_ru' => $productContentDataRu->name,
            'name_ua' => $productContentDataUa->name,

            'brief_content_ru' => $productContentDataRu->brief_description,
            'brief_content_ua' => $productContentDataUa->brief_description,

            'content_ru' => $productContentDataRu->description,
            'content_ua' => $productContentDataUa->description,

            'manufacturer_ru' => $productContentDataRu->country,
            'manufacturer_ua' => $productContentDataUa->country,

            'model_ru' => $productContentDataRu->model,
            'model_ua' => $productContentDataUa->model,

            'volume' => (float)$productContentDataRu->volume,
            'weight' => (float)$productContentDataRu->weight,
            'length' => (float)$productContentDataRu->depth,
            'width' => (float)$productContentDataRu->width,
            'height' => (float)$productContentDataRu->height,
        ];
    }

    /**
     * Prepare vendor product data.
     *
     * @param stdClass $productDataRu
     * @param float $vendorUsdCourse
     * @return array
     * @throws Exception
     */
    public function prepareVendorProductData(stdClass $productDataRu, float $vendorUsdCourse)
    {
        if (!$productDataRu->productID){
            throw new Exception('Missing vendor product id');
        }

        // prepare vendor product prices
        $vendorProductPrices = $this->prepareVendorProductPrices($productDataRu, $vendorUsdCourse);

        //get available data
        $availableData = (array)$productDataRu->available;

        // calculate available
        $available = empty($availableData) ? 0 : max($availableData);

        // get expected data
        $expectedData = (array)$productDataRu->stocks_expected;

        // calculate expected
        $expected = empty($expectedData) ? null : min($expectedData);

        // prepare vendor product data
        $vendorProductData = [
            'vendors_id' => self::VENDOR_ID,
            'vendor_product_id' => (int)$productDataRu->productID,
            'code' => $productDataRu->product_code,
            'articul' => $productDataRu->articul,
            'available' => $available,
            'available_time' => $expected,
            'min_order_quantity' => (int)$productDataRu->min_order_amount,
            'self_delivery' => (int)(bool)$productDataRu->self_delivery,
        ];

        return array_merge($vendorProductPrices, $vendorProductData);
    }
}
