<?php
/**
 * Product repository.
 */

namespace App\Support\Repositories;


use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductRepository
{
    /**
     * @var Product
     */
    private $product;

    /**
     * ProductRepository constructor.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get an existing product with fields equal to the inserted.
     *
     * @param array $productData
     * @return Product|Model|null
     */
    public function getProductByModelData(array $productData)
    {
        /**
         * ToDo Enhance parser. Store product in table to merge attributes by manager.
         */

        $searchDoubleKeys = config('vendor.search_double_by.product');

        return $this->product->newQuery()
            ->where(function ($query) use ($searchDoubleKeys, $productData) {
                foreach ($searchDoubleKeys as $field) {
                    $query->orWhere($field, $productData[$field]);
                }
            })
            ->first();
    }

    /**
     * Create new product.
     *
     * @param array $productData
     * @return Product|Model
     */
    public function createProduct(array $productData)
    {
        return $this->product->newQuery()->create($productData);
    }
}
