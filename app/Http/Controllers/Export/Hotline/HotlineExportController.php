<?php

namespace App\Http\Controllers\Export\Hotline;

use App\Contracts\Order\DeliveryTypesInterface;
use App\Contracts\Shop\AttributesInterface;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Support\ExchangeRates\ExchangeRates;
use App\Support\ProductAvailability\ProductAvailability;
use App\Support\Settings\SettingsRepository;
use Carbon\Carbon;
use Illuminate\Http\Response;
use SimpleXMLElement;

class HotlineExportController extends Controller
{
    /**
     * @var Category
     */
    private $category;
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;
    /**
     * @var ExchangeRates
     */
    private $exchangeRates;
    /**
     * @var Product
     */
    private $product;
    /**
     * @var ProductAvailability
     */
    private $productAvailability;

    /**
     * HotlineExportController constructor.
     * @param SettingsRepository $settingsRepository
     * @param ExchangeRates $exchangeRates
     * @param Category $category
     * @param Product $product
     * @param ProductAvailability $productAvailability
     */
    public function __construct(SettingsRepository $settingsRepository, ExchangeRates $exchangeRates, Category $category, Product $product, ProductAvailability $productAvailability)
    {
        $this->settingsRepository = $settingsRepository;
        $this->exchangeRates = $exchangeRates;
        $this->category = $category;
        $this->product = $product;
        $this->productAvailability = $productAvailability;
    }

    /**
     * Create XML response.
     *
     * @return Response
     */
    public function index()
    {
        $rate = $this->exchangeRates->getRate();

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><price></price>');

        $this->appendCommonElements($xml, $rate);
        $this->appendDeliveriesElements($xml);
        $categoriesIds = $this->appendCategories($xml);
        $this->appendProducts($xml, $categoriesIds, $rate);

        return response($xml->asXML())->header('Content-Type', 'text/xml');
    }

    /**
     * Append common elements.
     *
     * @param SimpleXMLElement $xml
     * @param float $rate
     */
    private function appendCommonElements(SimpleXMLElement $xml, float $rate)
    {
        $commonSettings = $this->settingsRepository->getProperty('export.hotline.common');

        $xml->addChild('date', date('Y-m-d H:i', time()));
        $xml->addChild('firmName', $commonSettings['firm_name']);
        $xml->addChild('firmId', $commonSettings['firm_id']);
        $xml->addChild('rate', $rate);
    }

    /**
     * Append deliveries types.
     *
     * @param SimpleXMLElement $xml
     */
    private function appendDeliveriesElements(SimpleXMLElement $xml)
    {
        $deliverySettings = $this->settingsRepository->getProperty('shop.order.delivery');

        // self delivery
        $delivery = $xml->addChild('delivery');
        $delivery->addAttribute('id', DeliveryTypesInterface::SELF);
        $delivery->addAttribute('type', 'pickup');
        $delivery->addAttribute('time', 1);

        // courier delivery
        $delivery = $xml->addChild('delivery');
        $delivery->addAttribute('id', DeliveryTypesInterface::COURIER);
        $delivery->addAttribute('type', 'address');
        $delivery->addAttribute('cost', $deliverySettings['delivery_uah_price']);
        $delivery->addAttribute('freeFrom', $deliverySettings['free_delivery_from_uah_sum']);
        $delivery->addAttribute('time', 1);
        $delivery->addAttribute('carrier', 'SLF');

        // post delivery
        $delivery = $xml->addChild('delivery');
        $delivery->addAttribute('id', DeliveryTypesInterface::POST);
        $delivery->addAttribute('type', 'address');
        $delivery->addAttribute('cost', 'null');
        $delivery->addAttribute('time', 1);
        $delivery->addAttribute('carrier', 'NP');
    }

    /**
     * @param SimpleXMLElement $xml
     * @return array
     */
    private function appendCategories(SimpleXMLElement $xml): array
    {
        $categories = $this->category->newQuery()
            ->whereHas('localDealerCategories', function ($query) {
                $query->where('published', 1);
            })
            ->with('dealerCategories')
            ->get();

        $categoriesElement = $xml->addChild('categories');

        foreach ($categories as $category) {
            $categoryElement = $categoriesElement->addChild('category');
            $categoryElement->addChild('id', $category->id);
            $categoryElement->addChild('name', $category->dealerCategories->first()->name);
        }

        return $categories->pluck('id')->toArray();
    }

    /**
     * @param SimpleXMLElement $xml
     * @param array $categoriesIds
     * @param float $rate
     */
    private function appendProducts(SimpleXMLElement $xml, array $categoriesIds, float $rate)
    {
        $locale = app()->getLocale();
        $productRouteLocale = $locale === config('app.canonical_locale') ? null : $locale;

        $products = $this->product->newQuery()
            ->where('price1', '>', 0)
            ->where('published', '=', 1)
            ->whereHas('categoryProducts', function ($query) use ($categoriesIds) {
                $query->whereIn('categories_id', $categoriesIds);
            })
            ->whereHas('productAttributes', function ($query) {
                $query->where('attributes_id', AttributesInterface::BRAND)
                    ->whereHas('attributeValue', function ($query) {
                        $query->where('attributes_id', AttributesInterface::BRAND);
                    });
            })
            ->with(['categoryProducts' => function ($query) use ($categoriesIds) {
                $query->whereIn('categories_id', $categoriesIds);
            }])
            ->with(['productAttributes' => function ($query) {
                $query->where('attributes_id', AttributesInterface::BRAND);
                $query->with(['attributeValue' => function ($query){
                    $query->where('attributes_id', AttributesInterface::BRAND);
                }]);
            }])
            ->with('primaryImage', 'availableStorageProducts', 'availableVendorProducts', 'expectingStorageProducts', 'expectingVendorProducts')
            ->get();

        $itemsElement = $xml->addChild('items');

        foreach ($products as $product) {
            $productElement = $itemsElement->addChild('item');
            $productElement->addChild('id', $product->id);
            $productElement->addChild('categoryId', $product->categoryProducts->first()->categories_id);

            if ($product->articul) {
                $productElement->addChild('code', $product->articul);
            } elseif ($product->code) {
                $productElement->addChild('code', $product->code);
            }

            if ($product->productAttributes->count()) {
                $productElement->addChild('vendor', $product->productAttributes->first()->attributeValue->value);
            }

            $productElement->addChild('name', $product->name);

            if ($product->brief_content) {
                $productElement->addChild('description', strip_tags($product->brief_content));
            }

            $productElement->addChild('url', url(route('shop.product.index', [
                'url' => $product->url,
                'locale' => $productRouteLocale,
            ])));

            if ($product->primaryImage) {
                $productElement->addChild('image', url('storage/' . $product->primaryImage->large));
            }

            $productElement->addChild('priceRUAH', $product->price1);
            $productElement->addChild('priceRUSD', $product->price1 / $rate);

            if ($this->productAvailability->isProductAvailable($product)) {
                $productElement->addChild('stock', 'В наличии');
            } else {
                $productExpectedAt = $this->productAvailability->getProductExpectedTime($product);

                if ($productExpectedAt) {
                    $expectedDays = $productExpectedAt->diffInDays(Carbon::now());

                    if ($expectedDays <= 1) {
                        $productElement->addChild('stock', 'В наличии');
                    } else {
                        $productElement->addChild('stock', 'Под заказ')->addAttribute('days', $expectedDays);
                    }
                }
            }

            if ($product->warranty) {
                $productElement->addChild('guarantee', $product->warranty);
            }

            if ($product->manufacturer) {
                $productElement->addChild('param', $product->manufacturer)->addAttribute('name', 'Страна изготовления');
            }

            if (!$product->is_new) {
                $productElement->addChild('condition', 3);
            }
        }
    }
}
