<?php


namespace App\Support\Vendors\Import\Apacer;


use App\Contracts\Shop\AttributesInterface;
use App\Models\Attribute;
use App\Models\Product;
use App\Support\ImageHandlers\VendorProductImageHandler;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use stdClass;

class ProductAttributesCreator
{
    /**
     * @var Attribute
     */
    private $attribute;
    /**
     * @var SpecificationMapping
     */
    private $specificationMapping;
    /**
     * @var VendorProductImageHandler
     */
    private $vendorProductImageHandler;
    /**
     * @var PriceListMapping
     */
    private $priceListMapping;

    /**
     * ProductAttributesCreator constructor.
     * @param Attribute $attribute
     * @param SpecificationMapping $specificationMapping
     * @param VendorProductImageHandler $vendorProductImageHandler
     * @param PriceListMapping $priceListMapping
     */
    public function __construct(Attribute $attribute, SpecificationMapping $specificationMapping, VendorProductImageHandler $vendorProductImageHandler, PriceListMapping $priceListMapping)
    {
        $this->attribute = $attribute;
        $this->specificationMapping = $specificationMapping;
        $this->vendorProductImageHandler = $vendorProductImageHandler;
        $this->priceListMapping = $priceListMapping;
    }

    /**
     * Create and attach product attributes.
     *
     * @param Product|Model $product
     * @param stdClass $productRawData
     */
    public function createProductAttributes(Product $product, stdClass $productRawData)
    {
        $this->createBrandAttribute($product);

        if ($productRawData->formFactor) {
            $this->createFormFactorAttribute($product, $productRawData->formFactor);
        }

        if ($productRawData->model) {
            $this->createModelAttribute($product, $productRawData->model);
        }

        if ($productRawData->specification) {
            $this->createSpecificationAttribute($product, $productRawData->specification);
        }

        if ($productRawData->temperature) {
            $this->createTemperatureAttribute($product, $productRawData->temperature);
        }

        if ($productRawData->capacity) {
            $this->createCapacityAttribute($product, $productRawData->capacity);
        }

        if ($productRawData->chipType) {
            $this->createChipTypeAttribute($product, $productRawData->chipType);
        }
    }

    /**
     * Load and attach product image.
     *
     * @param Product|Model $product
     * @param stdClass $productRawData
     * @throws Exception
     */
    public function loadProductImage(Product $product, stdClass $productRawData)
    {
        if (!empty($productRawData->imageUrl)) {
            $imageData = [
                [
                    'image' => $productRawData->imageUrl,
                    'priority' => 1,
                ]
            ];

            $this->vendorProductImageHandler->insertVendorProductImages($product, $imageData);
        }
    }

    /**
     * Load and attach product's specification.
     *
     * @param Product|Model $product
     * @param stdClass $productRawData
     */
    public function loadProductSpecification(Product $product, stdClass $productRawData)
    {
        if (!empty($productRawData->specificationUrl)) {
            $fileContent = (new Client())->get($productRawData->specificationUrl)->getBody()->getContents();

            $productFilesFolder = 'files/products/' . $product->id . '/';

            $fileName = $product->url . '.' . pathinfo($productRawData->specificationUrl, PATHINFO_EXTENSION);

            $destinationPath = $productFilesFolder . $fileName;

            Storage::disk('public')->put($destinationPath, $fileContent);

            $productFileData = [
                'name_ru' => $fileName,
                'name_uk' => $fileName,
                'url' => $destinationPath,
            ];

            $product->productFiles()->create($productFileData);
        }
    }

    /**
     * @param Product $product
     */
    private function createBrandAttribute(Product $product)
    {
        $attribute = $this->attribute->newQuery()->where('defined_attribute_id', AttributesInterface::BRAND)->first();

        $attributeValue = $attribute->attributeValues()->where('value_ru', 'Apacer')->first();

        if (!$attributeValue) {
            $attributeValue = $attribute->attributeValues()->create([
                'value_ru' => 'Apacer',
                'value_uk' => 'Apacer',
                'url' => Str::slug('apacer'),
            ]);
        }

        $product->attributeValues()->attach($attributeValue->id, [
            'attributes_id' => $attribute->id,
        ]);
    }

    /**
     * @param Product $product
     * @param string $formFactor
     */
    private function createFormFactorAttribute(Product $product, string $formFactor)
    {
        $attribute = $this->attribute->newQuery()->where('name_ru', 'Форм фактор')->first();

        if (!$attribute) {
            $attribute = $this->attribute->newQuery()->create([
                'name_ru' => 'Форм фактор',
                'name_uk' => 'Форм фактор',
                'indexable' => 0,
            ]);
        }

        $attributeValue = $attribute->attributeValues()->where('value_ru', $formFactor)->first();

        if (!$attributeValue) {
            $attributeValue = $attribute->attributeValues()->create([
                'value_ru' => $formFactor,
                'value_uk' => $formFactor,
                'url' => Str::slug($formFactor),
            ]);
        }

        $product->attributeValues()->attach($attributeValue->id, [
            'attributes_id' => $attribute->id,
        ]);
    }

    /**
     * @param Product $product
     * @param string $model
     */
    private function createModelAttribute(Product $product, string $model)
    {
        $attribute = $this->attribute->newQuery()->where('name_ru', 'Серия')->first();

        if (!$attribute) {
            $attribute = $this->attribute->newQuery()->create([
                'name_ru' => 'Серия',
                'name_uk' => 'Серія',
                'indexable' => 0,
            ]);
        }

        $attributeValue = $attribute->attributeValues()->where('value_ru', $model)->first();

        if (!$attributeValue) {
            $attributeValue = $attribute->attributeValues()->create([
                'value_ru' => $model,
                'value_uk' => $model,
                'url' => Str::slug($model),
            ]);
        }

        $product->attributeValues()->attach($attributeValue->id, [
            'attributes_id' => $attribute->id,
        ]);
    }

    /**
     * @param Product $product
     * @param string $specification
     */
    private function createSpecificationAttribute(Product $product, string $specification)
    {
        $attribute = $this->attribute->newQuery()->where('name_ru', 'Спецификация')->first();

        if (!$attribute) {
            $attribute = $this->attribute->newQuery()->create([
                'name_ru' => 'Спецификация',
                'name_uk' => 'Специфікація',
                'indexable' => 0,
            ]);
        }

        $attributeValue = $attribute->attributeValues()->where('value_ru', $specification)->first();

        if (!$attributeValue) {
            $attributeValue = $attribute->attributeValues()->create([
                'value_ru' => $specification,
                'value_uk' => $specification,
                'url' => Str::slug($specification),
            ]);
        }

        $product->attributeValues()->attach($attributeValue->id, [
            'attributes_id' => $attribute->id,
        ]);
    }

    /**
     * @param Product $product
     * @param string $temperature
     */
    private function createTemperatureAttribute(Product $product, string $temperature)
    {
        $attribute = $this->attribute->newQuery()->where('name_ru', 'Рабочая температура')->first();

        if (!$attribute) {
            $attribute = $this->attribute->newQuery()->create([
                'name_ru' => 'Рабочая температура',
                'name_uk' => 'Робоча температура',
                'indexable' => 0,
            ]);
        }

        $attributeValue = $attribute->attributeValues()->where('value_ru', $temperature)->first();

        if (!$attributeValue) {
            $attributeValue = $attribute->attributeValues()->create([
                'value_ru' => $temperature,
                'value_uk' => $temperature,
                'url' => Str::slug($temperature),
            ]);
        }

        $product->attributeValues()->attach($attributeValue->id, [
            'attributes_id' => $attribute->id,
        ]);
    }

    /**
     * @param Product $product
     * @param string $capacity
     */
    private function createCapacityAttribute(Product $product, string $capacity)
    {
        $attribute = $this->attribute->newQuery()->where('name_ru', 'Объем памяти')->first();

        if (!$attribute) {
            $attribute = $this->attribute->newQuery()->create([
                'name_ru' => 'Объем памяти',
                'name_uk' => 'Об`єм пам\'яті',
                'indexable' => 1,
            ]);
        }

        $attributeValue = $attribute->attributeValues()->where('value_ru', $capacity)->first();

        if (!$attributeValue) {
            $attributeValue = $attribute->attributeValues()->create([
                'value_ru' => $capacity,
                'value_uk' => $capacity,
                'url' => Str::slug($capacity),
            ]);
        }

        $product->attributeValues()->attach($attributeValue->id, [
            'attributes_id' => $attribute->id,
        ]);
    }

    /**
     * @param Product $product
     * @param string $chipType
     */
    private function createChipTypeAttribute(Product $product, string $chipType)
    {
        $attribute = $this->attribute->newQuery()->where('name_ru', 'Тип чипа')->first();

        if (!$attribute) {
            $attribute = $this->attribute->newQuery()->create([
                'name_ru' => 'Тип чипа',
                'name_uk' => 'Тип чіпа',
                'indexable' => 1,
            ]);
        }

        $attributeValue = $attribute->attributeValues()->where('value_ru', $chipType)->first();

        if (!$attributeValue) {
            $attributeValue = $attribute->attributeValues()->create([
                'value_ru' => $chipType,
                'value_uk' => $chipType,
                'url' => Str::slug($chipType),
            ]);
        }

        $product->attributeValues()->attach($attributeValue->id, [
            'attributes_id' => $attribute->id,
        ]);
    }
}
