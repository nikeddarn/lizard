<?php
/**
 * Brain vendor adapter.
 */

namespace App\Support\Vendors\Adapters;

use App\Contracts\Vendor\VendorAdapterInterface;
use App\Contracts\Vendor\VendorInterface;
use App\Support\ProductPrices\ProductPrice;
use App\Support\Vendors\Providers\BrainVendorProvider;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use stdClass;

class BrainVendorAdapter implements VendorAdapterInterface
{
    /**
     * @var BrainVendorProvider
     */
    private $vendorProvider;
    /**
     * @var ProductPrice
     */
    private $productPrice;

    /**
     * BrainVendorAdapter constructor.
     * @param BrainVendorProvider $vendorProvider
     * @param ProductPrice $productPrice
     */
    public function __construct(BrainVendorProvider $vendorProvider, ProductPrice $productPrice)
    {
        $this->vendorProvider = $vendorProvider;
        $this->productPrice = $productPrice;
    }

    /**
     * Get vendor id.
     *
     * @return int
     */
    public function getVendorId()
    {
        return VendorInterface::BRAIN;
    }


    /**
     * Get vendor categories tree.
     *
     * @return Collection
     * @throws \Exception
     */
    public function getVendorCategoriesTree(): Collection
    {
        $categories = [];

        foreach ($this->vendorProvider->getCategories() as $category) {
            if ($category->realcat == 0) {
                $vendorCategory = new stdClass();
                $vendorCategory->id = $category->categoryID;
                $vendorCategory->parentId = $category->parentID;
                $vendorCategory->name = $category->name;

                $categories[] = $vendorCategory;
            }
        }

        return $this->buildTree($categories);
    }

    /**
     * @param int $categoryId
     * @return array
     * @throws \Exception
     */
    public function getVendorCategoryData(int $categoryId): array
    {
        $vendorCategoryRu = $this->vendorProvider->getCategory($categoryId, 'ru');
        $vendorCategoryUa = $this->vendorProvider->getCategory($categoryId, 'ua');

        return [
            'vendors_id' => VendorInterface::BRAIN,
            'vendor_category_id' => $categoryId,
            'name_ru' => $vendorCategoryRu->name,
            'name_ua' => $vendorCategoryUa->name,
        ];
    }

    /**
     * Get page of category products.
     *
     * @param int $categoryId
     * @param int $page
     * @return LengthAwarePaginator
     * @throws \Exception
     */
    public function getCategoryProducts(int $categoryId, int $page): LengthAwarePaginator
    {
        $productsPerPage = config('admin.vendor_products_per_page');
        $offset = ($page - 1) * $productsPerPage;

        $productsData = $this->vendorProvider->getCategoryProducts($categoryId, $productsPerPage, $offset);

        $productsTotal = $productsData->count;

        $products = [];
        $syncArchiveProduct = config('admin.archive_products.sync');

        foreach ($productsData->list as $product) {
            if (!$product->is_archive || $syncArchiveProduct) {
                $vendorProduct = new stdClass();
                $vendorProduct->id = $product->productID;
                $vendorProduct->articul = $product->articul;
                $vendorProduct->code = $product->product_code;
                $vendorProduct->name = $product->name;
                $vendorProduct->country = $product->country;
                $vendorProduct->warranty = $product->warranty;
                $vendorProduct->price = $product->price;

                $products[] = $vendorProduct;
            }
        }

        return new LengthAwarePaginator(collect($products), $productsTotal, $productsPerPage, $page, [
            'path' => url()->current(),
        ]);
    }

    /**
     * @param int $vendorProductId
     * @return array
     * @throws Exception
     */
    public function getProductData(int $vendorProductId)
    {
        $vendorUsdCourse = $this->getCashUsdCourse();

        $productDataRu = $this->vendorProvider->getProductData($vendorProductId, 'ru');

        $productContentDataRu = $this->vendorProvider->getProductContentData($vendorProductId, 'ru');
        $productContentDataUa = $this->vendorProvider->getProductContentData($vendorProductId, 'ua');

        $recommendableUsdPrice = $productDataRu->recommendable_price / $vendorUsdCourse;
        $retailUsdPrice = $productDataRu->retail_price_uah / $vendorUsdCourse;

        // prices for calculate sale prices columns
        $usingWholesalePrice = $productDataRu->price;
        $usingRetailPrice = $recommendableUsdPrice ? $recommendableUsdPrice : $retailUsdPrice;

        $preparedProductsData = [
            'product' => [
                // from 'product' method
                'code' => $productDataRu->product_code,
                'articul' => $productDataRu->articul,
                'min_order_quantity' => $productDataRu->min_order_amount,
                'warranty' => $productDataRu->warranty,
                'is_archive' => $productDataRu->is_archive,
                'url' => Str::slug($productDataRu->name),

                // from 'content' method
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

                // calculated
                'price1' => $this->productPrice->getVendorProductPrice1(VendorInterface::BRAIN, $usingWholesalePrice, $usingRetailPrice),
                'price2' => $this->productPrice->getVendorProductPrice2(VendorInterface::BRAIN, $usingWholesalePrice, $usingRetailPrice),
                'price3' => $this->productPrice->getVendorProductPrice3(VendorInterface::BRAIN, $usingWholesalePrice, $usingRetailPrice),
            ],

            'vendor_product' => [
                'code' => $productDataRu->product_code,
                'articul' => $productDataRu->articul,
                'availability' => !empty($productDataRu->stocks),
                'min_order_quantity' => $productDataRu->min_order_amount,
                'price' => $productDataRu->price,
                'recommendable_price' => $recommendableUsdPrice,
                'retail_price' => $retailUsdPrice,
                'self_delivery' => $productDataRu->self_delivery,
            ],

            'vendor_brand_id' => (int)$productDataRu->vendorID,

            'product_stocks_data' => $this->createProductStocksData((array)$productDataRu->available, (array)$productDataRu->stocks_expected),

            'attributes' => $this->createAttributesData($productContentDataRu->options, $productContentDataUa->options),

            'images' => $this->createImagesData($productContentDataRu->images)
        ];

        return $preparedProductsData;
    }

    /**
     * @param int $vendorProductId
     * @return array
     * @throws Exception
     */
    public function getProductAttributesData(int $vendorProductId): array
    {
        $productAttributesDataRu = collect($this->vendorProvider->getProductAttributes($vendorProductId, 'ru'))->keyBy('OptionID');
        $productAttributesDataUa = collect($this->vendorProvider->getProductAttributes($vendorProductId, 'ua'))->keyBy('OptionID');

        $attributesData = [];

        foreach ($productAttributesDataRu as $key => $attributeData) {
            $attributesData[] = [
                'attribute' => [
                    'vendor_attribute_id' => $attributeData->OptionID,
                    'data' => [
                        'name_ru' => $attributeData->OptionName,
                        'name_ua' => $productAttributesDataUa->get($key)->OptionName,
                        'multiply_product_values' => 1,
                    ],
                ],
                'attribute_value' => [
                    'vendor_attribute_value_id' => $attributeData->ValueID,
                    'data' => [
                        'value_ru' => $attributeData->ValueName,
                        'value_ua' => $productAttributesDataUa->get($key)->ValueName,
                        'url' => Str::slug($attributeData->ValueName),
                    ],
                ],
            ];
        }

        return $attributesData;
    }


    /**
     * Get brand data by vendor's brand id.
     *
     * @param int $vendorBrandId
     * @return array
     * @throws Exception
     */
    public function getBrandDataByVendorBrandId(int $vendorBrandId):array
    {
        $vendorBrand = collect($this->vendorProvider->getBrands())->keyBy('vendorID')->get($vendorBrandId);

        return [
            'value_ru' => $vendorBrand->name,
            'value_ua' => $vendorBrand->name,
            'url' => Str::slug($vendorBrand->name),
        ];
    }

    /**
     * Get brand data by vendor's brand id.
     *
     * @param int $vendorStockId
     * @return array
     * @throws Exception
     */
    public function getStockDataByVendorStockId(int $vendorStockId):array
    {
        $vendorBrand = collect($this->vendorProvider->getStocks())->keyBy('stockID')->get($vendorStockId);

        return [
            'vendor_stock_id' => $vendorStockId,
            'name_ru' => $vendorBrand->name,
            'name_ua' => $vendorBrand->name,
        ];
    }

    /**
     * Get cash usd course.
     *
     * @return float
     * @throws Exception
     */
    private function getCashUsdCourse(): float
    {
        return (float)collect($this->vendorProvider->getUsdCourses())->where('currencyID', '=', 2)->first()->value;
    }

    /**
     * Build tree from categories.
     *
     * @param array $elements
     * @param int $parentId
     * @return Collection
     */
    private function buildTree(array &$elements, $parentId = 1): Collection
    {
        $branch = collect();

        foreach ($elements as $element) {
            if ($element->parentId == $parentId) {
                $children = $this->buildTree($elements, $element->id);
                if ($children) {
                    $element->children = $children;
                }

                $branch->push($element);
            }
        }

        return $branch;
    }

    /**
     * Create product stocks data.
     *
     * @param array $stockAvailable
     * @param array $stockAvailableTime
     * @return array
     */
    private function createProductStocksData(array $stockAvailable, array $stockAvailableTime): array
    {
        $productStockData = [];

        foreach ($stockAvailable as $stockId => $availabilityStatus) {
            $productStockData[$stockId]['available'] = $availabilityStatus;
        }

        foreach ($stockAvailableTime as $stockId => $availableTime) {
            $productStockData[$stockId]['available_time'] = $availableTime;
        }

        return $productStockData;
    }

    /**
     * Create data of product attributes and its values.
     *
     * @param array $productAttributesDataRu
     * @param array $productAttributesDataUa
     * @return array
     */
    private function createAttributesData(array $productAttributesDataRu, array $productAttributesDataUa)
    {
        $attributesData = [];

        foreach ($productAttributesDataRu as $key => $attributeData) {
            $attributesData[] = [
                'attribute' => [
                    'vendor_attribute_id' => $attributeData->OptionID,
                    'data' => [
                        'name_ru' => $attributeData->OptionName,
                        'name_ua' => $productAttributesDataUa[$key]->OptionName,
                        'multiply_product_values' => 1,
                    ],
                ],
                'attribute_value' => [
                    'vendor_attribute_value_id' => $attributeData->ValueID,
                    'data' => [
                        'value_ru' => $attributeData->ValueName,
                        'value_ua' => $productAttributesDataUa[$key]->ValueName,
                        'url' => Str::slug($attributeData->ValueName),
                    ],
                ],
            ];
        }

        return $attributesData;
    }

    /**
     * Create images data.
     *
     * @param array $imagesData
     * @return array
     */
    private function createImagesData(array $imagesData):array
    {
        $productImages = [];

        foreach ($imagesData as $imageData){
            $productImages[] = [
                'image' => $imageData->full_image,
                'small' => $imageData->small_image,
                'medium' => $imageData->medium_image,
                'large' => $imageData->large_image,
                'priority' => (int)!$imageData->priority,
            ];
        }

        return $productImages;
    }
}