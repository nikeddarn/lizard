<?php


namespace App\Support\Vendors\Import\Apacer;


use ErrorException;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Exception;
use stdClass;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ProductDataCreator
{
    /**
     * @var PriceListMapping
     */
    private $priceListMapping;
    /**
     * @var SpecificationMapping
     */
    private $specificationMapping;
    /**
     * @var GoogleTranslate
     */
    private $googleTranslate;

    /**
     * ProductDataCreator constructor.
     * @param PriceListMapping $priceListMapping
     * @param SpecificationMapping $specificationMapping
     * @param GoogleTranslate $googleTranslate
     */
    public function __construct(PriceListMapping $priceListMapping, SpecificationMapping $specificationMapping, GoogleTranslate $googleTranslate)
    {
        $this->priceListMapping = $priceListMapping;
        $this->specificationMapping = $specificationMapping;
        $this->googleTranslate = $googleTranslate;
    }

    /**
     * Create product data
     *
     * @param stdClass $productRawData
     * @return array
     * @throws ErrorException
     */
    public function createProductData(stdClass $productRawData): array
    {
        $productData = [];

        $productData['name_ru'] = $this->getProductName($productRawData, 'ru');
        $productData['name_uk'] = $this->getProductName($productRawData, 'uk');
        $productData['url'] = Str::slug($productData['name_ru']);
        $productData['model_ru'] = $this->getProductModel($productRawData, 'ru');
        $productData['model_uk'] = $this->getProductModel($productRawData, 'uk');
        $productData['manufacturer_ru'] = $this->getProductManufacturer($productRawData, 'ru');
        $productData['manufacturer_uk'] = $this->getProductManufacturer($productRawData, 'uk');
        $productData['brief_content_ru'] = $this->getBriefContent($productRawData, 'ru');
        $productData['brief_content_uk'] = $this->getBriefContent($productRawData, 'uk');
        $productData['content_ru'] = $this->getContent($productRawData, 'ru');
        $productData['content_uk'] = $this->getContent($productRawData, 'uk');

        return $productData;
    }

    /**
     * Define product category.
     *
     * @param stdClass $productRawData
     * @return int
     * @throws Exception
     */
    public function getProductCategoryId(stdClass $productRawData): int
    {
        switch ($productRawData->solution) {
            case 'SATA SSD Series':
                return 70;
            case 'Flash Card Series':
                return 60;
            case 'PATA SSD Series':
                return 61;
            case 'USB Flash Series':
                return 71;
            default:
                throw new Exception('Can not define product category for product solution: ' . $productRawData->solution);
        }
    }

    /**
     * Get product name.
     *
     * @param stdClass $productRawData
     * @param string $locale
     * @return string
     */
    private function getProductName(stdClass $productRawData, string $locale)
    {
        $nameParts = [
            $productRawData->solution,
            $productRawData->capacity,
            $productRawData->chipType,
            $productRawData->modelNumber,
        ];

        return implode(' ', array_filter($nameParts));
    }

    /**
     * Get product model.
     *
     * @param stdClass $rawProductData
     * @param string $locale
     * @return string
     */
    private function getProductModel(stdClass $rawProductData, string $locale)
    {
        return $rawProductData->modelNumber;
    }

    /**
     * Get product manufacturer.
     *
     * @param stdClass $rawProductData
     * @param string $locale
     * @return string
     */
    private function getProductManufacturer(stdClass $rawProductData, string $locale)
    {
        switch ($locale) {
            case 'ru':
                return 'Тайвань';
            case 'uk':
                return 'Тайвань';
            default:
                return 'Тайвань';
        }
    }

    /**
     * Get brief content.
     *
     * @param stdClass $rawProductData
     * @param string $locale
     * @return string
     */
    private function getBriefContent(stdClass $rawProductData, string $locale)
    {
        $briefContentParts = [
            $rawProductData->solution,
            $rawProductData->capacity,
            $rawProductData->chipType,
            $rawProductData->modelNumber,
            $rawProductData->specification,
            '(' . $rawProductData->temperature . ')',
        ];

        return implode(' ', array_filter($briefContentParts));
    }

    /**
     * Get content.
     *
     * @param stdClass $rawProductData
     * @param string $locale
     * @return string|null
     * @throws ErrorException
     */
    private function getContent(stdClass $rawProductData, string $locale)
    {
        if (empty($rawProductData->introduction)) {
            return null;
        }

        return $this->googleTranslate->setSource('en')->setTarget($locale)->translate($rawProductData->introduction);
    }
}
