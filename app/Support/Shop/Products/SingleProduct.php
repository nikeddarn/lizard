<?php
/**
 * Create single product data.
 */

namespace App\Support\Shop\Products;


use App\Contracts\Shop\AttributesInterface;
use App\Models\AttributeValue;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class SingleProduct extends AbstractProduct
{
    /**
     * Get product with relations.
     *
     * @param string $url
     * @param null $user
     * @return mixed
     */
    public function getProduct(string $url, $user = null)
    {
        $query = $this->makeRetrieveProductsQuery($url);

        $products = $this->addRelations($query, $user)->with('categories')->get();

        $this->addProductsProperties($products, $user);

        return $products->first();
    }

    /**
     * @param Product $product
     * @param Category $category
     * @param null $user
     * @return Collection
     */
    public function getLinkedProducts(Product $product, Category $category = null, $user = null)
    {
        if (!$category) {
            return null;
        }

        $categoryId = $category->id;
        $productId = $product->id;

        $query = $this->getRetrieveProductQuery()
            ->whereHas('categoryProducts', function ($query) use ($categoryId) {
                $query->where('categories_id', $categoryId);
            })
            ->where('id', '>', $productId)
            ->orderBy('id')
            ->union($this->getRetrieveProductQuery()
                ->whereHas('categoryProducts', function ($query) use ($categoryId) {
                    $query->where('categories_id', $categoryId);
                })
                ->where('id', '<', $productId)
                ->orderBy('id'))
            ->distinct()
            ->limit(8);

        $products = $this->addRelations($query, $user)->get();

        $this->addProductsProperties($products, $user);

        return $products;
    }

    /**
     * @param AttributeValue|null $brandAttributeValue
     * @return Collection|null
     */
    public function getLinkedByBrandCategories(AttributeValue $brandAttributeValue = null)
    {
        if (!$brandAttributeValue) {
            return null;
        }

        $brandAttributeValueId = $brandAttributeValue->id;
        $brandAttributeId = $brandAttributeValue->attributes_id;

        $categories = $this->getRetrieveCategoryQuery()
            ->whereHas('products', function ($query) use ($brandAttributeValueId, $brandAttributeId) {
                $query->where('published', 1);
                $query->whereHas('productAttributes', function ($query) use ($brandAttributeValueId, $brandAttributeId) {
                    $query->where('attribute_values_id', $brandAttributeValueId);
                    $query->where('attributes_id', $brandAttributeId);
                });
            })
            ->get();

        $locale = app()->getLocale();
        $urlLocale = $locale === config('app.canonical_locale') ? null : $locale;

        foreach ($categories as $category) {
            $category->brandFilteredName = $category->name . ' ' . $brandAttributeValue->value;
            $category->href = url(route('shop.category.filter.single', [
                'url' => $category->url,
                'attribute' => AttributesInterface::BRAND,
                'filter' => $brandAttributeValue->url,
                'locale' => $urlLocale,
            ]));
        }

        return $categories;
    }

    /**
     * Make query.
     *
     * @param string $url
     * @return Builder
     */
    protected function makeRetrieveProductsQuery(string $url): Builder
    {
        return $this->getRetrieveProductQuery()->where('url', $url);
    }

    /**
     * Add relations to query.
     *
     * @param Builder $query
     * @param $user
     * @return Builder
     */
    protected function addRelations(Builder $query, $user = null): Builder
    {
        $query->with('productImages', 'productVideos', 'productFiles', 'availableStorageProducts', 'expectingStorageProducts', 'availableVendorProducts', 'expectingVendorProducts', 'availableProductStorages.city', 'attributeValues.attribute');

        if ($user) {
            $query->with(['favouriteProducts' => function ($query) use ($user) {
                $query->where('users_id', $user->id);
            }]);
        }

        return $query;
    }
}
