<?php


namespace App\Support\Vendors\Import\Apacer;

use App\Contracts\Vendor\VendorInterface;
use App\Events\Vendor\VendorProductInserted;
use App\Models\Product;
use ErrorException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use stdClass;
use Throwable;


class ProductCreator
{
    /**
     * @var Xls
     */
    private $xlsReader;
    /**
     * @var WorkSheetsMapping
     */
    private $sheetsMapping;
    /**
     * @var Product
     */
    private $product;
    /**
     * @var ProductDataCreator
     */
    private $productDataCreator;
    /**
     * @var SpecificationResolver
     */
    private $specificationResolver;
    /**
     * @var ProductAttributesCreator
     */
    private $attributesCreator;
    /**
     * @var ProductPriceCreator
     */
    private $priceCreator;

    /**
     * ApacerImportController constructor.
     * @param Xls $xlsReader
     * @param WorkSheetsMapping $sheetsMapping
     * @param Product $product
     * @param ProductDataCreator $productDataCreator
     * @param SpecificationResolver $specificationResolver
     * @param ProductAttributesCreator $attributesCreator
     * @param ProductPriceCreator $priceCreator
     */
    public function __construct(Xls $xlsReader, WorkSheetsMapping $sheetsMapping, Product $product, ProductDataCreator $productDataCreator, SpecificationResolver $specificationResolver, ProductAttributesCreator $attributesCreator, ProductPriceCreator $priceCreator)
    {
        $this->xlsReader = $xlsReader;
        $this->sheetsMapping = $sheetsMapping;
        $this->product = $product;
        $this->productDataCreator = $productDataCreator;
        $this->specificationResolver = $specificationResolver;
        $this->attributesCreator = $attributesCreator;
        $this->priceCreator = $priceCreator;
    }

    /**
     * @param stdClass $productRawData
     * @throws ErrorException
     * @throws Exception
     */
    public function createProductFromRawData(stdClass $productRawData)
    {
        $productData = $this->productDataCreator->createProductData($productRawData);

        $product = $this->retrieveExistingProduct($productData);

        if (!$product) {
            $product = $this->createProduct($productData);

            $this->attachProductCategory($product, $productRawData);

            $this->attributesCreator->createProductAttributes($product, $productRawData);

            try {
                $this->attributesCreator->loadProductImage($product, $productRawData);

                $this->attributesCreator->loadProductSpecification($product, $productRawData);
            } catch (Throwable $exception) {
                Log::info($exception->getMessage());
            }
        }

        $this->priceCreator->setProductPrices($product, $productRawData);

        event(new VendorProductInserted($product));
    }

    /**
     * Retrieve existing product.
     *
     * @param $productData
     * @return Model|null
     */
    private function retrieveExistingProduct($productData)
    {
        return $this->product->newQuery()
            ->where('url', $productData['url'])
            ->with(['vendorProducts' => function ($query) {
                $query->where('vendors_id', VendorInterface::APACER);
            }])
            ->first();
    }

    /**
     * Create product and vendor product.
     *
     * @param array $productData
     * @return Product|Model
     */
    private function createProduct(array $productData)
    {
        $product = $this->product->newQuery()->create($productData);

        $vendorProduct = $product->vendorProducts()->create([
            'vendors_id' => VendorInterface::APACER,
            'is_bookable' => 1,
        ]);

        $product->setRelation('vendorProducts', collect()->push($vendorProduct));

        return $product;
    }

    /**
     * @param Product $product
     * @param stdClass $productRawData
     * @throws Exception
     */
    private function attachProductCategory(Product $product, stdClass $productRawData)
    {
        $productCategory = $this->productDataCreator->getProductCategoryId($productRawData);

        $product->categories()->attach($productCategory);
    }
}
