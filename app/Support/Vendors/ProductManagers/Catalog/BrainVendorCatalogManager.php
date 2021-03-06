<?php
/**
 * Brain catalog manager.
 */

namespace App\Support\Vendors\ProductManagers\Catalog;


use App\Contracts\Vendor\VendorInterface;
use App\Support\Vendors\Providers\BrainCatalogProvider;
use Carbon\Carbon;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

class BrainVendorCatalogManager extends VendorCatalogManager
{
    /**
     * @var int
     */
    const PRODUCTS_PER_REQUEST_LIMIT = 100;

    /**
     * BrainVendorCatalogManager constructor.
     * @param BrainCatalogProvider $provider
     */
    public function __construct(BrainCatalogProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * Get vendor categories.
     *
     * @return array
     * @throws Exception
     */
    protected function prepareCategoriesData(): array
    {
        // get categories from vendor
        $categoriesData = $this->provider->getCategories();

        // prepare categories data
        $vendorCategories = [];

        foreach ($categoriesData as $category) {
            // get only real categories
            $vendorCategory = new stdClass();

            $vendorCategory->id = (int)$category->categoryID;
            $vendorCategory->parentId = (int)$category->parentID;
            $vendorCategory->name = $category->name;

            $vendorCategories[] = $vendorCategory;
        }

        return $vendorCategories;
    }

    /**
     * prepare category parameters for view.
     *
     * @param $vendorCategoryId
     * @return stdClass
     * @throws Exception
     */
    protected function prepareCategoryData($vendorCategoryId): stdClass
    {
        $categoryData = $this->provider->getCategoryData($vendorCategoryId);

        $preparedData = new stdClass();

        $preparedData->id = $categoryData->categoryID;
        $preparedData->name = $categoryData->name;

        return $preparedData;
    }

    /**
     * Retrieve and prepare vendor category data for insert.
     *
     * @param int $vendorCategoryId
     * @return array
     * @throws Exception
     */
    protected function prepareVendorCategoryModelData(int $vendorCategoryId): array
    {
        $categoryData = $this->provider->getCategoryMultiLanguageData($vendorCategoryId);

        $nameRu = $categoryData['category_data_ru']->name;
        $nameUa = $categoryData['category_data_uk']->name;

        if (!($nameRu && $nameUa)) {
            throw new Exception('Missing category name');
        }

        return [
            'vendors_id' => VendorInterface::BRAIN,
            'vendor_category_id' => $vendorCategoryId,
            'name_ru' => $nameRu,
            'name_uk' => $nameUa,
        ];
    }

    /**
     * Create paginator of category products.
     *
     * @param int $vendorCategoryId
     * @param int $page
     * @return LengthAwarePaginator
     * @throws Exception
     */
    protected function createCategoryProductsPaginator(int $vendorCategoryId, int $page): LengthAwarePaginator
    {
        // count of products per page
        $productsPerPage = config('admin.vendor_products_per_page');


        // current offset
        $offset = ($page - 1) * $productsPerPage;

        $categoryProductsData = $this->provider->getCategoryProductsData($vendorCategoryId, $productsPerPage, $offset);

        // products of current page
        $products = $categoryProductsData['products']->list;

        // total products count
        $productsTotal = $categoryProductsData['products']->count;

        // vendor's course
        $course = $this->getCashUsdCourse($categoryProductsData['course']);

        // prepare products for view
        $preparedProducts = $this->prepareCategoryProductsData($products, $course);

        return new LengthAwarePaginator($preparedProducts, $productsTotal, $productsPerPage, $page, [
            'path' => request()->url(),
        ]);
    }

    /**
     * Prepare vendor products data for view.
     *
     * @param array $vendorProducts
     * @param float $course
     * @return array
     */
    protected function prepareCategoryProductsData(array $vendorProducts, float $course): array
    {
        $preparedProducts = [];

        foreach ($vendorProducts as $vendorProduct) {

            //incoming product price
            $incomingProductPrice = $vendorProduct->price ? $vendorProduct->price : null;

            // retail product price
            $productRetailUahPrice = $vendorProduct->retail_price_uah ? $vendorProduct->retail_price_uah : $vendorProduct->recommendable_price;
            $productRetailPrice = $productRetailUahPrice && $course ? $productRetailUahPrice / $course : null;

            $preparedVendorProduct = new stdClass();

            $preparedVendorProduct->id = $vendorProduct->productID;
            $preparedVendorProduct->is_archive = $vendorProduct->is_archive;
            $preparedVendorProduct->image = $vendorProduct->small_image ? $vendorProduct->small_image : $vendorProduct->medium_image;
            $preparedVendorProduct->articul = $vendorProduct->articul;
            $preparedVendorProduct->name = $vendorProduct->name;
            $preparedVendorProduct->country = $vendorProduct->country;
            $preparedVendorProduct->warranty = $vendorProduct->warranty;
            $preparedVendorProduct->price = $this->formatPrice($incomingProductPrice);
            $preparedVendorProduct->profit = $this->formatPrice($this->calculateProfitSum($incomingProductPrice, $productRetailPrice));
            $preparedVendorProduct->profitPercents = $this->formatProfitPercents($this->calculateProfitPercents($incomingProductPrice, $productRetailPrice));
            $preparedVendorProduct->isAvailable = $this->getProductAvailability($vendorProduct);
            $preparedVendorProduct->expected = $this->getProductExpected($vendorProduct);

            $preparedProducts[] = $preparedVendorProduct;
        }

        return $preparedProducts;
    }

    /**
     * Get all products ids of given vendor category.
     *
     * @param int $vendorCategoryId
     * @return array
     * @throws Exception
     */
    protected function getCategoryProductsIds(int $vendorCategoryId): array
    {
        // collect vendor products ids
        $productsIds = [];

        $page = 1;

        do {
            // current offset
            $offset = ($page - 1) * self::PRODUCTS_PER_REQUEST_LIMIT;

            // get products data for given page
            $pageProductsData = $this->provider->getCategoryProductsIds($vendorCategoryId, self::PRODUCTS_PER_REQUEST_LIMIT, $offset);

            // set total products count
            $totalProductsCount = $pageProductsData->count;

            // retrieve current products ids
            $currentProductsIds = collect($pageProductsData->list)->pluck('productID')->toArray();

            // add current page's ids
            $productsIds = array_merge($productsIds, $currentProductsIds);

            $page++;
        } while ($totalProductsCount > self::PRODUCTS_PER_REQUEST_LIMIT * ($page - 1));

        return $productsIds;
    }

    /**
     * Get cash usd course.
     *
     * @param $vendorCourses
     * @return float
     */
    private function getCashUsdCourse($vendorCourses): float
    {
        return (float)collect($vendorCourses)->where('currencyID', '=', 2)->first()->value;
    }

    /**
     * Define product availability.
     *
     * @param stdClass $vendorProduct
     * @return bool
     */
    private function getProductAvailability(stdClass $vendorProduct): bool
    {
        $stocksAvailability = array_filter((array)$vendorProduct->available);

        $stocksPresents = array_filter((array)$vendorProduct->stocks);

        return $stocksAvailability || $stocksPresents;
    }

    /**
     * Define product availability.
     *
     * @param stdClass $vendorProduct
     * @return mixed|null
     */
    private function getProductExpected(stdClass $vendorProduct)
    {
        $stocksExpected = array_filter((array)$vendorProduct->stocks_expected);

        if ($stocksExpected) {
            return Carbon::createFromFormat('Y-m-d H:i:s', min($stocksExpected))->format('d-m-Y');
        } else {
            return null;
        }
    }

    /**
     * Calculate profit sum.
     *
     * @param $incomingProductPrice
     * @param $productRetailPrice
     * @return float|null
     */
    private function calculateProfitSum(float $incomingProductPrice = null, float $productRetailPrice = null)
    {
        if ($incomingProductPrice && $productRetailPrice) {
            return $productRetailPrice - $incomingProductPrice;
        } else {
            return null;
        }
    }

    /**
     * Calculate profit sum.
     *
     * @param $incomingProductPrice
     * @param $productRetailPrice
     * @return float|null
     */
    private function calculateProfitPercents(float $incomingProductPrice = null, float $productRetailPrice = null)
    {
        if ($incomingProductPrice && $productRetailPrice) {
            return ($productRetailPrice - $incomingProductPrice) / $incomingProductPrice * 100;
        } else {
            return null;
        }
    }
}
