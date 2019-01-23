<?php
/**
 * Insert vendor product manager.
 */

namespace App\Support\Vendors\ProductManagers\Insert;


use App\Contracts\Shop\ProductBadgesInterface;
use App\Events\Vendor\VendorProductInserted;
use App\Models\Product;
use App\Models\VendorProduct;
use App\Support\ImageHandlers\VendorProductImageHandler;
use App\Support\ProductBadges\ProductBadges;
use App\Support\ImageHandlers\ProductImageHandler;
use App\Support\Repositories\ProductRepository;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Throwable;

abstract class InsertVendorProductManager
{
    /**
     * @var int
     */
    protected $vendorId;
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
     * VendorInsertProductManager constructor.
     * @param ProductRepository $productRepository
     * @param ProductBadges $productBadges
     * @param VendorProductImageHandler $productImageHandler
     */
    public function __construct(ProductRepository $productRepository, ProductBadges $productBadges, VendorProductImageHandler $productImageHandler)
    {
        $this->productRepository = $productRepository;
        $this->productBadges = $productBadges;
        $this->productImageHandler = $productImageHandler;
    }

    /**
     * Create product and vendor product with all received properties.
     * Attach it to categories.
     * Fire event.
     *
     * @param array $vendorCategoryIds
     * @param array $localCategoryIds
     * @param int $vendorProductId
     * @throws Exception
     */
    public function insertVendorProduct(array $vendorCategoryIds, array $localCategoryIds, int $vendorProductId)
    {
        // try to retrieve existing vendor product by vendor product
        $product = $this->productRepository->getProductByVendorProductId($this->vendorId, $vendorProductId);

        if ($product) {
            // get vendor product
            $vendorProduct = $product->vendorProduct;
        } else {
            // get and adapt all product data from vendor
            $this->createProductData($vendorProductId);

            DB::beginTransaction();

            try {
                // create product and it's related properties
                $product = $this->createProduct();

                // create vendor product and it's related properties
                $vendorProduct = $this->createVendorProduct($product);

                DB::commit();

            } catch (Throwable $exception) {
                DB::rollBack();

                throw new  Exception($exception->getMessage());
            }
        }

        // attach product to local categories
        $product->categories()->syncWithoutDetaching($localCategoryIds);

        // attach vendor product to vendor categories
        $vendorProduct->vendorCategories()->syncWithoutDetaching($vendorCategoryIds);

        // fire event
        event(new VendorProductInserted($product));
    }

    /**
     * Retrieve and prepare product data.
     *
     * @param int $vendorProductId
     */
    abstract protected function createProductData(int $vendorProductId);

    /**
     * Create product from vendor's data.
     *
     * @return Product
     * @throws Exception
     */
    private function createProduct(): Product
    {
        //retrieve product by model's attributes or create anew
        $product = $this->productRepository->findOrMakeProduct($this->productData);

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
