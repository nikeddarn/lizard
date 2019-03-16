<?php
/**
 * Insert vendor product manager.
 */

namespace App\Support\Vendors\ProductManagers\Insert;


use App\Contracts\Shop\ProductBadgesInterface;
use App\Events\Vendor\VendorProductInserted;
use App\Exceptions\Vendor\DisallowInsertProductException;
use App\Models\Product;
use App\Models\VendorCategory;
use App\Models\VendorProduct;
use App\Support\ImageHandlers\VendorProductImageHandler;
use App\Support\ProductBadges\ProductBadges;
use App\Support\ImageHandlers\ProductImageHandler;
use App\Support\Repositories\ProductRepository;
use App\Support\Settings\SettingsRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

abstract class InsertVendorProductManager
{
    /**
     * @var array
     */
    protected $productData;
    /**
     * @var array
     */
    protected $vendorProductData;
    /**
     * @var array
     */
    protected $attributesData;
    /**
     * @var array
     */
    protected $imagesData;
    /**
     * @var array
     */
    protected $commentsData;
    /**
     * @var array
     */
    protected $stocksData;
    /**
     * @var ProductBadges
     */
    private $productBadges;
    /**
     * @var ProductImageHandler
     */
    private $productImageHandler;
    /**
     * @var VendorCategory
     */
    private $vendorCategory;
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;

    /**
     * VendorInsertProductManager constructor.
     * @param ProductRepository $productRepository
     * @param ProductBadges $productBadges
     * @param VendorProductImageHandler $productImageHandler
     * @param VendorCategory $vendorCategory
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(ProductRepository $productRepository, ProductBadges $productBadges, VendorProductImageHandler $productImageHandler, VendorCategory $vendorCategory, SettingsRepository $settingsRepository)
    {
        $this->productRepository = $productRepository;
        $this->productBadges = $productBadges;
        $this->productImageHandler = $productImageHandler;
        $this->vendorCategory = $vendorCategory;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Create product and vendor product with all received properties.
     * Attach it to categories.
     * Fire event.
     *
     * @param array $vendorCategoryIds
     * @param array $localCategoryIds
     * @param int $vendorProductId
     * @param bool $checkInsertAllow
     * @throws DisallowInsertProductException
     * @throws Throwable
     */
    public function insertVendorProduct(array $vendorCategoryIds, array $localCategoryIds, int $vendorProductId, $checkInsertAllow = false)
    {
        // try to retrieve existing vendor product by vendor product
        $vendorProduct = $this->getExistingVendorProduct($vendorProductId);

        if ($vendorProduct) {
            // get vendor product
            $product = $vendorProduct->product;

            // restore from archive
            if ($product->is_archive) {
                $product->is_archive = 0;
                $product->save;
            }
        } else {
            // get and adapt all product data from vendor
            $this->prepareVendorProductData($vendorProductId);

            if ($checkInsertAllow && !$this->isProductInsertAble($vendorCategoryIds)) {
                throw new DisallowInsertProductException();
            }


            try {
                DB::beginTransaction();

                // create product and it's related properties
                $product = $this->createProduct();

                // create vendor product and it's related properties
                $vendorProduct = $this->createVendorProduct($product);

                DB::commit();

            } catch (Throwable $exception) {
                DB::rollBack();
                throw $exception;
            }
        }

        // attach products to categories
        $this->attachProductCategories($product, $vendorProduct, $localCategoryIds, $vendorCategoryIds);

        // fire event
        event(new VendorProductInserted($product));
    }

    /**
     * Attach products to categories.
     *
     * @param Product $product
     * @param VendorProduct $vendorProduct
     * @param array $localCategoryIds
     * @param array $vendorCategoryIds
     */
    protected function attachProductCategories(Product $product, VendorProduct $vendorProduct, array $localCategoryIds, array $vendorCategoryIds)
    {
        // attach product to local categories
        $product->categories()->syncWithoutDetaching($localCategoryIds);

        // attach vendor product to vendor categories
        $vendorProduct->vendorCategories()->syncWithoutDetaching($vendorCategoryIds);
    }

    /**
     * Get already existing product.
     *
     * @param int $vendorProductId
     * @return Model|null
     */
    abstract protected function getExistingVendorProduct(int $vendorProductId);

    /**
     * Retrieve and prepare product data.
     *
     * @param int $vendorProductId
     */
    abstract protected function prepareVendorProductData(int $vendorProductId);

    /**
     * Are product data pass inserting conditions ?
     *
     * @param array $vendorCategoryIds
     * @return bool
     */
    private function isProductInsertAble(array $vendorCategoryIds): bool
    {
        $insertProductSettings = $this->settingsRepository->getProperty('vendor.insert_product');

        if (!$insertProductSettings['download_archive_product'] && $this->vendorProductData['is_archive']) {
            return false;
        }

        $vendorCategories = $this->vendorCategory->newQuery()->whereIn('id', $vendorCategoryIds)->get();

        $maxVendorProductAge = $vendorCategories->max('download_product_max_age');

        if ($this->vendorProductData['vendor_created_at'] && $maxVendorProductAge) {
            if (Carbon::createFromFormat('Y-m-d H:i:s', $this->vendorProductData['vendor_created_at'])->addMonths($maxVendorProductAge) <= Carbon::now()) {
                return false;
            }
        }

        $incomingPrice = $this->vendorProductData['price'];
        $retailPrices = array_filter([$this->vendorProductData['recommendable_price'], $this->vendorProductData['retail_price']]);

        if ($incomingPrice && $retailPrices) {
            $minProfitSum = (float)$vendorCategories->min('download_product_min_profit_sum');
            $minProfitPercent = (float)$vendorCategories->min('download_product_min_profit_percent');

            $profitSum = max($retailPrices) - $incomingPrice;
            $profitPercent = $profitSum / $incomingPrice * 100;

            return ($profitSum >= $minProfitSum) || ($profitPercent >= $minProfitPercent);
        } else {
            // download product with empty prices
            return true;
        }
    }

    /**
     * Create product from vendor's data.
     *
     * @return Product
     * @throws Exception
     */
    private function createProduct(): Product
    {
        //retrieve product by model's attributes or create anew
        $product = $this->productRepository->getProductByModelData($this->productData);

        // create product
        if (!$product) {
            $product = $this->productRepository->createProduct($this->productData);
        }

        // insert product 'new' badge
        $this->productBadges->insertProductBadge($product, ProductBadgesInterface::NEW);

        // insert product attributes
        $this->attachProductAttributes($product);

        // insert product images
        $this->insertProductImages($product);

        // insert product comments
        $this->insertProductComments($product);

        return $product;
    }

    /**
     * Create vendor product from vendor's data.
     *
     * @param Product $product
     * @return VendorProduct|Model
     */
    private function createVendorProduct(Product $product): VendorProduct
    {
        $vendorProduct = $product->vendorProducts()->create($this->vendorProductData);

        // insert vendor product stocks
        $this->attachVendorProductStocks($vendorProduct);

        return $vendorProduct;
    }

    /**
     * Attach product attribute values.
     *
     * @param Product $product
     */
    private function attachProductAttributes(Product $product)
    {
        // attach attribute value to product
        if (!empty($this->attributesData)) {
            $product->attributeValues()->syncWithoutDetaching($this->attributesData);
        }
    }

    /**
     * Attach vendor product stocks.
     *
     * @param VendorProduct|Model $vendorProduct
     */
    private function attachVendorProductStocks(VendorProduct $vendorProduct)
    {
        if (!empty($this->stocksData)) {
            $vendorProduct->vendorStocks()->sync($this->stocksData);
        }
    }

    /**
     * Insert product images.
     *
     * @param Product $product
     * @throws Exception
     */
    private function insertProductImages(Product $product)
    {
        if (!empty($this->imagesData)) {
            $this->productImageHandler->insertVendorProductImages($product, $this->imagesData);
        }
    }

    /**
     * Insert product comments.
     *
     * @param Product $product
     */
    private function insertProductComments(Product $product)
    {
        if (!empty($this->commentsData)) {
            // max uploaded comments
            $maxCommentsCount = config('vendor.insert_vendor_product.max_comments');

            foreach ($this->commentsData as $index => $comment) {
                // restrict max comments count
                if ($index > $maxCommentsCount) {
                    break;
                }

                // insert comment
                $product->productComments()->create($comment);
            }
        }
    }
}
