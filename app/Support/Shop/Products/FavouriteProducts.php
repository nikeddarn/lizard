<?php
/**
 * User favourite products.
 */

namespace App\Support\Shop\Products;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class FavouriteProducts extends AbstractProduct
{
    /**
     * Get users' favourite products.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getProducts()
    {
        if (auth('web')->check()) {
            $favouriteProductsQuery = $this->makeAuthenticatedUserProductsQuery();
        } else {
            $favouriteProductsQuery = $this->makeGuestUserProductsQuery();
        }

        $products = $favouriteProductsQuery->with('primaryImage', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts')
            ->paginate($this->productsPerPage)->appends(request()->query());

        $this->addProductsProperties($products);

        return $products;
    }

    /**
     * Create retrieve authenticated user favourite products query.
     *
     * @return Builder
     */
    private function makeAuthenticatedUserProductsQuery(): Builder
    {
        $usersId = auth('web')->id();

        return $this->getRetrieveProductQuery()
            ->whereHas('favouriteProducts', function ($query) use ($usersId) {
                $query->where('users_id', $usersId);
            });
    }

    /**
     * Create retrieve guest user favourite products query.
     *
     * @return Builder
     */
    private function makeGuestUserProductsQuery(): Builder
    {
        $uuid = Cookie::get('uuid', Str::uuid());

        return $this->getRetrieveProductQuery()
            ->whereHas('favouriteProducts', function ($query) use ($uuid) {
                $query->where('uuid', $uuid);
            });
    }
}
