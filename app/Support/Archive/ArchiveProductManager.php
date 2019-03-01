<?php
/**
 * Archive product manager.
 */

namespace App\Support\Archive;


use App\Contracts\Shop\ProductBadgesInterface;
use App\Models\Product;
use App\Support\ProductBadges\ProductBadges;
use Illuminate\Database\Eloquent\Model;

class ArchiveProductManager
{
    /**
     * @var ProductBadges
     */
    private $productBadges;

    /**
     * ArchiveProductManager constructor.
     * @param ProductBadges $productBadges
     */
    public function __construct(ProductBadges $productBadges)
    {
        $this->productBadges = $productBadges;
    }

    /**
     * Make product archive.
     *
     * @param Product|Model $product
     */
    public function archiveProduct(Product $product)
    {
        // unlink from vendors
        foreach ($product->vendorProducts as $vendorProduct) {
            $vendorProduct->delete();
        }

        // unlink from categories
        foreach ($product->categories as $category) {
            $category->products()->detach($product->id);
        }

        // make archive
        $product->is_archive = 1;
        $product->price1 = null;
        $product->price2 = null;
        $product->price3 = null;
        $product->save();

        // add badge
        $this->productBadges->insertProductBadge($product, ProductBadgesInterface::ARCHIVE);
    }
}
