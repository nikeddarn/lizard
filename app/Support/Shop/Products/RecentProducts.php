<?php
/**
 * User recent products.
 */

namespace App\Support\Shop\Products;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RecentProducts extends AbstractProduct
{
    /**
     * Get users' recent products.
     *
     * @return LengthAwarePaginator
     */
    public function getProducts()
    {
        $user = $this->getUser();

        if ($user) {
            $products = $user->timeLimitedRecentProducts()
                ->with('primaryImage', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts')
                ->with(['favouriteProducts' => function ($query) use ($user) {
                    $query->where('users_id', $user->id);
                }])
                ->paginate($this->productsPerPage)
                ->appends(request()->query());

            $this->addProductsProperties($products, $user);

            return $products;
        } else {
            return null;
        }
    }
}
