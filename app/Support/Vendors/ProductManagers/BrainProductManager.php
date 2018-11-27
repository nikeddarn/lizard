<?php
/**
 * Brain product manager.
 */

namespace App\Support\Vendors\ProductManagers;


use App\Contracts\Shop\ProductBadgesInterface;
use App\Contracts\Vendor\VendorInterface;
use App\Models\VendorProduct;
use App\Support\Repositories\AttributeRepository;
use App\Support\Repositories\BrandRepository;
use App\Support\Repositories\ProductImageUploader;
use App\Support\Repositories\ProductRepository;
use App\Support\Vendors\Adapters\BrainVendorAdapter;
use Exception;

class BrainProductManager extends VendorProductManager
{
    /**
     * @var BrainVendorAdapter
     */
    private $vendorAdapter;

    /**
     * BrainProductManager constructor.
     * @param ProductRepository $productRepository
     * @param BrandRepository $brandRepository
     * @param AttributeRepository $attributeRepository
     * @param ProductImageUploader $imageUploader
     * @param BrainVendorAdapter $vendorAdapter
     */
    public function __construct(ProductRepository $productRepository, BrandRepository $brandRepository, AttributeRepository $attributeRepository, ProductImageUploader $imageUploader, BrainVendorAdapter $vendorAdapter)
    {
        parent::__construct($productRepository, $brandRepository, $attributeRepository, $imageUploader);
        $this->vendorAdapter = $vendorAdapter;
    }

    /**
     * Insert vendor products data in db.
     *
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @param VendorProduct $vendorProduct
     * @throws Exception
     * @throws \Throwable
     */
    public function insertVendorProduct(int $vendorCategoryId, int $localCategoryId, VendorProduct $vendorProduct)
    {
        // get product data
        $productData = $this->vendorAdapter->getProductData($vendorProduct->vendor_product_id);

        // retrieve existing or create product model and attach it to local category
        $product = $this->insertProductData($vendorProduct, $localCategoryId, $productData['product']);

        // update vendor product model and attach it to vendor category
        $this->insertVendorProductData($vendorProduct, $product->id, $vendorCategoryId, $productData['vendor_product']);

        //attach brand attribute to product
        $this->insertProductBrand($this->vendorAdapter, $product->id, $productData['vendor_brand_id']);

        //insert product's badges
        $this->insertProductBadges($product, $this->defineProductBadges($productData['product_stocks_data']));

        // insert vendor stocks product data
        $this->insertVendorStocksProductData($this->vendorAdapter, $vendorProduct->id, $productData['product_stocks_data']);

        // insert product attributes and values
        $this->insertProductAttributes($product, VendorInterface::BRAIN, $productData['attributes']);

        // insert product pictures
        $this->insertProductImages($product, $productData['images']);
    }

    /**
     * Define product badges.
     *
     * @param array $productStocksData
     * @return array
     */
    private function defineProductBadges(array $productStocksData): array
    {
        // add 'new' badge
        $badgesIds = [ProductBadgesInterface::NEW];

        // add 'ending' badge
        $stocksAvailability = array_column($productStocksData, 'available');
        if (!empty($stocksAvailability) && max($stocksAvailability) === 1) {
            $badgesIds[] = ProductBadgesInterface::ENDING;
        }

        return $badgesIds;
    }
}