<?php
/**
 * Adapt Brain product data for insert.
 */

namespace App\Support\Vendors\Adapters\Brain;


use App\Contracts\Vendor\VendorInterface;
use App\Models\Product;
use DateTime;
use Exception;
use Illuminate\Support\Str;
use stdClass;

class BrainProductDataAdapter
{
    /**
     * @var int
     */
    const VENDOR_ID = VendorInterface::BRAIN;
    /**
     * @var BrainProductPriceAdapter
     */
    private $productPriceAdapter;
    /**
     * @var Product
     */
    private $product;

    /**
     * BrainProductDataAdapter constructor.
     * @param BrainProductPriceAdapter $productPriceAdapter
     * @param Product $product
     */
    public function __construct(BrainProductPriceAdapter $productPriceAdapter, Product $product)
    {
        $this->productPriceAdapter = $productPriceAdapter;
        $this->product = $product;
    }

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
        if (!($productDataRu->name && $productContentDataUa->name)) {
            throw new Exception('Can not insert vendor product. Missing necessary product data');
        }

        $productData = [
            'code' => $productDataRu->product_code,
            'articul' => $productDataRu->articul,
            'min_order_quantity' => (int)$productDataRu->min_order_amount,
            'warranty' => $productDataRu->warranty ? (int)$productDataRu->warranty : null,
            'is_archive' => (int)$productDataRu->is_archive,

            'name_ru' => $productContentDataRu->name,
            'name_uk' => $productContentDataUa->name,

            'brief_content_ru' => $productContentDataRu->brief_description,
            'brief_content_uk' => $productContentDataUa->brief_description,

            'content_ru' => $productContentDataRu->description,
            'content_uk' => $productContentDataUa->description,

            'manufacturer_ru' => $productContentDataRu->country,
            'manufacturer_uk' => $productContentDataUa->country,

            'model_ru' => $productContentDataRu->model,
            'model_uk' => $productContentDataUa->model,

            'volume' => $productContentDataRu->volume ? (float)$productContentDataRu->volume : null,
            'weight' => $productContentDataRu->weight ? (float)$productContentDataRu->weight : null,
            'length' => $productContentDataRu->depth ? (float)$productContentDataRu->depth : null,
            'width' => $productContentDataRu->width ? (float)$productContentDataRu->width : null,
            'height' => $productContentDataRu->height ? (float)$productContentDataRu->height : null,
        ];

        $productData['url'] = $this->createProductUrl($productData);

        return $productData;
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
        if (!($productDataRu->productID && $vendorUsdCourse > 0)) {
            throw new Exception('Can not insert vendor product. Missing necessary vendor product data');
        }

        // prepare vendor product prices
        $vendorProductPrices = $this->productPriceAdapter->prepareVendorProductPrices($productDataRu, $vendorUsdCourse);

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
            'warranty' => $productDataRu->warranty ? (int)$productDataRu->warranty : null,
            'is_archive' => (int)$productDataRu->is_archive,
            'available' => $available,
            'available_time' => $expected,
            'min_order_quantity' => (int)$productDataRu->min_order_amount,
            'self_delivery' => (int)(bool)$productDataRu->self_delivery,
            'vendor_created_at' => DateTime::createFromFormat('Y-m-d H:i:s', $productDataRu->date_added) ? $productDataRu->date_added : null,
        ];

        return array_merge($vendorProductPrices, $vendorProductData);
    }

    /**
     * Generate slug for product
     *
     * @param array $productData
     * @return string
     */
    private function createProductUrl(array $productData): string
    {
        $searchDoubleKeys = config('vendor.search_double_by.product');

        $url = '';

        // try to create unique url with each set of product unique fields
        foreach ($searchDoubleKeys as $keysSet) {
            // define new url parts
            $slugParts = [];
            // add product fields to url string until url is unique
            foreach ($keysSet as $key) {
                // add value of product ley to url
                $slugParts[] = Str::slug($productData[$key]);
                // create url
                $url = implode('_', $slugParts);
                // check is url unique
                if (!$this->product->newQuery()->where('url', $url)->count()) {
                    break 2;
                }
            }
        }

        return $url;
    }
}
