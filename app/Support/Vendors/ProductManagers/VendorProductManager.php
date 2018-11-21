<?php
/**
 * Vendor product manager.
 */

namespace App\Support\Vendors\ProductManagers;


use App\Models\Product;
use App\Models\VendorProduct;
use App\Support\Repositories\ProductRepository;

abstract class VendorProductManager
{
    /**
     * @var ProductRepository
     */
    private $productRepository;

    /**
     * VendorProductManager constructor.
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Retrieve existing or create new Product model with VendorProduct model.
     *
     * @param int $vendorId
     * @param int $vendorCategoryId
     * @param int $localCategoryId
     * @param int $vendorProductId
     * @param array $productModelData
     * @param array $vendorProductModelData
     * @return Product
     */
    protected function getOrCreateProduct(int $vendorId, int $vendorCategoryId, int $localCategoryId, int $vendorProductId, array $productModelData, array $vendorProductModelData):Product
    {
        $product = $this->productRepository->getProductByVendorId($vendorId, $vendorProductId);

        if (!$product){
            $product = $this->productRepository->getProductByModelData($productModelData);
        }

        if (!$product){
            $product = Product::create($productModelData);
        }

        return $product;
    }

    /**
     *
     *
     * @param int $productId
     * @param int $vendorCategoryId
     * @param array $vendorProductModelData
     * @return VendorProduct
     */
    protected function getOrCreateVendorProduct(int $productId, int $vendorCategoryId, array $vendorProductModelData):VendorProduct
    {

    }
}