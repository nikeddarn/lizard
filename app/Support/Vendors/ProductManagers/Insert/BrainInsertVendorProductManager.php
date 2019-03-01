<?php
/**
 * Brain product manager.
 */

namespace App\Support\Vendors\ProductManagers\Insert;


use App\Contracts\Vendor\VendorInterface;
use App\Models\VendorCategory;
use App\Models\VendorProduct;
use App\Support\ImageHandlers\VendorProductImageHandler;
use App\Support\ProductBadges\ProductBadges;
use App\Support\Repositories\ProductRepository;
use App\Support\Settings\SettingsRepository;
use App\Support\Vendors\Adapters\Brain\BrainDataAdapter;
use App\Support\Vendors\Providers\BrainInsertProductProvider;
use Exception;
use Illuminate\Database\Eloquent\Model;

class BrainInsertVendorProductManager extends InsertVendorProductManager
{
    /**
     * @var string
     */
    const VENDOR_ID = VendorInterface::BRAIN;

    /**
     * @var BrainInsertProductProvider
     */
    private $productProvider;
    /**
     * @var BrainDataAdapter
     */
    private $dataAdapter;
    /**
     * @var VendorProduct
     */
    private $vendorProduct;

    /**
     * BrainInsertInsertProductManager constructor.
     * @param ProductRepository $productRepository
     * @param ProductBadges $productBadges
     * @param VendorProductImageHandler $productImageHandler
     * @param BrainInsertProductProvider $productProvider
     * @param BrainDataAdapter $dataAdapter
     * @param VendorProduct $vendorProduct
     * @param VendorCategory $vendorCategory
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(ProductRepository $productRepository, ProductBadges $productBadges, VendorProductImageHandler $productImageHandler, BrainInsertProductProvider $productProvider, BrainDataAdapter $dataAdapter, VendorProduct $vendorProduct, VendorCategory $vendorCategory, SettingsRepository $settingsRepository)
    {
        parent::__construct($productRepository, $productBadges, $productImageHandler, $vendorCategory, $settingsRepository);
        $this->productProvider = $productProvider;
        $this->dataAdapter = $dataAdapter;
        $this->vendorProduct = $vendorProduct;
    }

    /**
     * Get already existing product.
     *
     * @param int $vendorProductId
     * @return Model|null
     */
    protected function getExistingVendorProduct(int $vendorProductId)
    {
        return $this->vendorProduct->newQuery()->where([
            ['vendors_id', '=', self::VENDOR_ID],
            ['vendor_product_id', '=', $vendorProductId],
        ])
            ->with(['product' => function($query){
                $query->with('vendorProducts', 'stockStorages');
            }])
            ->first();
    }

    /**
     * Retrieve and prepare product data.
     *
     * @param int $vendorProductId
     * @throws Exception
     */
    protected function prepareVendorProductData(int $vendorProductId){
        // receive whole product data
        $productData = $this->productProvider->getProductData($vendorProductId);

        // get product data entities
        $productDataRu = $productData['product_data_ru'];
        $productContentDataRu = $productData['product_content_data_ru']->list[0];
        $productContentDataUa = $productData['product_content_data_uk']->list[0];
        $comments = $productData['comments']->list;

        $vendorUsdCourse = $this->getCashUsdCourse($productData['courses']);

        // set product model data
        $this->productData = $this->dataAdapter->prepareProductData($productDataRu, $productContentDataRu, $productContentDataUa);

        // set vendor product model data
        $this->vendorProductData = $this->dataAdapter->prepareVendorProductData($productDataRu, $vendorUsdCourse);

        // set product attributes
        $this->attributesData = $this->dataAdapter->prepareProductAttributesData($productContentDataRu->options, $productContentDataUa->options, (int)$productDataRu->vendorID);

        // set product stocks
        $this->stocksData = $this->dataAdapter->prepareVendorProductStocksData($productDataRu);

        // set product images
        $this->imagesData = $this->dataAdapter->prepareVendorProductImagesData($productContentDataRu->images);

        // set product comments
        $this->commentsData = $this->dataAdapter->prepareVendorProductCommentsData($comments);
    }

    /**
     * Get cash usd course.
     *
     * @param array $vendorCoursesData
     * @return float
     * @throws Exception
     */
    private function getCashUsdCourse(array $vendorCoursesData): float
    {
        if (empty($vendorCoursesData)){
            throw new Exception('Can not insert vendor product. Exchange rate is missing');
        }

        $cashCourseItem = collect($vendorCoursesData)->where('currencyID', '=', 2)->first();

        if ($cashCourseItem){
            return $cashCourseItem->value;
        }else{
            return null;
        }
    }
}
