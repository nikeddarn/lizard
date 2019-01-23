<?php
/**
 * Product breadcrumbs.
 */

namespace App\Support\Breadcrumbs;


use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class ProductBreadcrumbs extends CategoryBreadcrumbs
{
    /**
     * Get breadcrumbs for product.
     *
     * @param Product|Model $product
     * @return array
     */
    public function getProductBreadcrumbs(Product $product): array
    {
        if (session()->has('product_category_id')) {
            // get category id from session
            $categoryId = session()->get('product_category_id');
        } else {
            // get first product category's id
            $categoryId = $product->categories()->first()->id;
        }

        // create categories' breadcrumbs
        $breadcrumbs = $this->createCategoryBreadcrumbs($categoryId);

        // append product breadcrumb
        $breadcrumbs[$product->name] = null;

        return $breadcrumbs;
    }
}
