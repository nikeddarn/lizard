<?php
/**
 * User recent products.
 */

namespace App\Support\Shop\Products;


use App\Support\User\RetrieveUser;

class RecentProducts extends AbstractProduct
{
    use RetrieveUser;

    /**
     * Get users' recent products.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getProducts()
    {
        $user = $this->getUser();

        if ($user) {
            $userId = $user->id;

            $products = $user->timeLimitedRecentProducts()
                ->with('primaryImage', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts')
                ->with(['favouriteProducts' => function ($query) use ($userId) {
                    $query->where('users_id', $userId);
                }])
                ->paginate($this->productsPerPage)
                ->appends(request()->query());

            $this->addProductsProperties($products);

            return $products;
        } else {
            return null;
        }
    }
}
