<?php
/**
 * User favourite products.
 */

namespace App\Support\Shop\Products;

use App\Support\User\RetrieveUser;

class FavouriteProducts extends AbstractProduct
{
    use RetrieveUser;

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

            $this->addProductsProperties($products);

            return $products;
        } else {
            return null;
        }
    }
}
