<?php
/**
 * User favourite products.
 */

namespace App\Support\Shop\Products;

class FavouriteProducts extends AbstractProduct
{
    /**
     * Get users' favourite products.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|null
     */
    public function getProducts()
    {
        $user = $this->getUser();

        if ($user) {
            $products = $user->favouriteProducts()
                ->with('primaryImage', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts')
                ->paginate($this->productsPerPage)
                ->appends(request()->query());

            $this->addProductsProperties($products, $user);

            return $products;
        } else {
            return null;
        }
    }
}
