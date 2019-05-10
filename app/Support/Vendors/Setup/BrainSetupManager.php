<?php
/**
 * Brain setup manager.
 */

namespace App\Support\Vendors\Setup;


use App\Contracts\Shop\AttributesInterface;
use App\Contracts\Vendor\VendorInterface;
use App\Models\Attribute;
use App\Models\City;
use App\Models\VendorStock;
use App\Support\Repositories\AttributeRepository;
use App\Support\Vendors\Providers\BrainSetupProvider;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use stdClass;

class BrainSetupManager
{

    const VENDOR_ID = VendorInterface::BRAIN;

    /**
     * @var BrainSetupProvider
     */
    private $vendorProvider;
    /**
     * @var AttributeRepository
     */
    private $attributeRepository;
    /**
     * @var City
     */
    private $city;

    /**
     * BrainSetupManager constructor.
     * @param BrainSetupProvider $vendorProvider
     * @param AttributeRepository $attributeRepository
     * @param City $city
     */
    public function __construct(BrainSetupProvider $vendorProvider, AttributeRepository $attributeRepository, City $city)
    {
        $this->vendorProvider = $vendorProvider;
        $this->attributeRepository = $attributeRepository;
        $this->city = $city;
    }

    /**
     * Setup vendor data.
     *
     * @throws Exception
     */
    public function setup()
    {
//        $this->insertBrands();
        $this->insertStocks();
    }

    /**
     * Insert brands.
     *
     * @throws Exception
     */
    private function insertBrands()
    {
        // create attribute
        $brandAttribute = Attribute::query()->where('defined_attribute_id', AttributesInterface::BRAND)->first();

        // get vendor brands
        $vendorBrands = $this->vendorProvider->getBrands();

        // prepare data
        $attributeValuesData = $this->prepareBrandsData($vendorBrands);


        // insert attribute values and attach it to vendor
        foreach ($attributeValuesData as $vendorAttributeValueId => $attributeValueData) {

            // retrieve attribute value by vendor brand id
            $attributeValue = $this->attributeRepository->getAttributeValueByVendorId(self::VENDOR_ID, $vendorAttributeValueId);

            // retrieve attribute value by data
            if (!$attributeValue) {
                $attributeValue = $this->attributeRepository->getAttributeValueByModelData($attributeValueData);
            }

            // create attribute value
            if (!$attributeValue) {
                $attributeValue = $brandAttribute->attributeValues()->create($attributeValueData);
            }

            // attach vendor with original attribute value id
            $attributeValue->vendors()->syncWithoutDetaching([
                self::VENDOR_ID => [
                    'vendor_attribute_value_id' => $vendorAttributeValueId,
                ]
            ]);
        }
    }

    /**
     * Prepare brands data.
     *
     * @param array $vendorBrands
     * @return array
     */
    private function prepareBrandsData(array $vendorBrands): array
    {
        // collect unique attribute values data keyed by vendor's original attribute value id
        $attributeValuesData = [];

        foreach ($vendorBrands as $vendorBrand) {
            $attributeValuesData[$vendorBrand->vendorID] = [
                'value_ru' => $vendorBrand->name,
                'value_uk' => $vendorBrand->name,
                'url' => Str::slug($vendorBrand->name),
            ];
        }

        return $attributeValuesData;
    }

    /**
     * Insert stocks.
     *
     * @throws Exception
     */
    private function insertStocks()
    {
        $cities = $this->city->newQuery()->get();

        $vendorStocks = $this->vendorProvider->getStocks();

        foreach ($vendorStocks as $stock) {

            $vendorStockData = [
                'vendors_id' => self::VENDOR_ID,
                'vendor_stock_id' => $stock->stockID,
                'name_ru' => $stock->name,
                'name_uk' => $stock->name,
            ];

            $vendorStock = VendorStock::query()->firstOrCreate($vendorStockData);

            $vendorStock->cities_id = $this->getStockCityId($stock, $cities);
            $vendorStock->save();
        }
    }

    /**
     * Get stock city id
     *
     * @param stdClass $stock
     * @param Collection $cities
     * @return int|null
     */
    private function getStockCityId(stdClass $stock, Collection $cities)
    {
        foreach ($cities as $city) {
            if (stripos($stock->name, $city->name_ru) !== false || stripos($stock->name, $city->name_uk) !== false) {
                return $city->id;
            }
        }

        return null;
    }
}
