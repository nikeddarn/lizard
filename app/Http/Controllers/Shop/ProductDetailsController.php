<?php

namespace App\Http\Controllers\Shop;

use App\Contracts\Shop\AttributesInterface;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Support\Breadcrumbs\ProductBreadcrumbs;
use App\Support\Headers\CacheControlHeaders;
use App\Support\Seo\MetaTags\ProductMetaTags;
use App\Support\Settings\SettingsRepository;
use App\Support\Shop\Products\SingleProduct;
use App\Support\User\RetrieveUser;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;

class ProductDetailsController extends Controller
{
    use RetrieveUser;
    use CacheControlHeaders;

    /**
     * @var Attribute
     */
    private $attribute;
    /**
     * @var ProductBreadcrumbs
     */
    private $breadcrumbs;
    /**
     * @var SingleProduct
     */
    private $productRetriever;
    /**
     * @var ProductMetaTags
     */
    private $productMetaTags;
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;
    /**
     * @var AttributeValue
     */
    private $attributeValue;

    /**
     * ProductDetailsController constructor.
     * @param Attribute $attribute
     * @param AttributeValue $attributeValue
     * @param ProductBreadcrumbs $breadcrumbs
     * @param SingleProduct $productRetriever
     * @param ProductMetaTags $productMetaTags
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(Attribute $attribute, AttributeValue $attributeValue, ProductBreadcrumbs $breadcrumbs, SingleProduct $productRetriever, ProductMetaTags $productMetaTags, SettingsRepository $settingsRepository)
    {
        $this->attribute = $attribute;
        $this->breadcrumbs = $breadcrumbs;
        $this->productRetriever = $productRetriever;
        $this->productMetaTags = $productMetaTags;
        $this->settingsRepository = $settingsRepository;
        $this->attributeValue = $attributeValue;
    }

    /**
     * Show product card.
     *
     * @param string $url
     * @return Response
     */
    public function index(string $url)
    {
        $user = $this->getUser();

        // retrieve product
        $product = $this->productRetriever->getProduct($url, $user);

        if (!$product) {
            abort(404);
        }

        $category = $product->categories->first();
        $linkedProducts = $this->productRetriever->getLinkedProducts($product, $category, $user);

        $brandAttributeValue = $product->attributeValues->where('attributes_id', AttributesInterface::BRAND)->first();
        $linkedCategories = $this->productRetriever->getLinkedByBrandCategories($brandAttributeValue);

        $response = response()->make();

        $pageLastModified = $product->updated_at;

        $this->checkAndSetLastModifiedHeader($user, $response, $pageLastModified);

        // get product's id
        $productId = $product->id;

        // retrieve product's attributes
        $productAttributes = $this->retrieveProductAttributes($productId)
            ->each(function (Attribute $attribute) use ($productId) {
                $attribute->setRelation('attributeValues', $this->retrieveAttributeValues($attribute->id, $productId));
            });

        $commentsPerPage = $this->settingsRepository->getProperty('shop.show_product_comments_per_page');

        $comments = $product->productComments()->with('user')->paginate($commentsPerPage);

        $breadcrumbs = $this->breadcrumbs->getProductBreadcrumbs($product);

        // title, description, keywords
        $pageTitle = $this->productMetaTags->getProductTitle($product);
        $pageDescription = $this->productMetaTags->getProductDescription($product);
        $pageKeywords = $this->productMetaTags->getProductKeywords($product);

        // add product to user's recent viewed
        $this->addProductToRecentViewed($user, $product->id);

        $response->setContent(view('content.shop.product.details.index')->with(compact('product', 'linkedProducts', 'category', 'linkedCategories', 'brandAttributeValue', 'breadcrumbs', 'comments', 'productAttributes', 'pageTitle', 'pageDescription', 'pageKeywords')));

        $this->checkAndSetEtagHeader($user, $response);

        return $response;
    }

    /**
     * @param int $productId
     * @return Collection
     */
    private function retrieveProductAttributes(int $productId)
    {
        return $this->attribute->newQuery()
            ->whereHas('attributeValues.productAttributes', function ($query) use ($productId) {
                $query->where('products_id', $productId);
            })
            ->whereHas('productAttributes', function ($query) use ($productId) {
                $query->where('products_id', $productId);
            })
            ->get();
    }

    /**
     * Retrieve attribute values.
     *
     * @param int $attributeId
     * @param int $productId
     * @return Collection
     */
    protected function retrieveAttributeValues(int $attributeId, int $productId)
    {
        return $this->attributeValue->newQuery()
            ->whereHas('productAttributes', function ($query) use ($attributeId, $productId) {
                $query->where('attributes_id', $attributeId);
                $query->where('products_id', $productId);
            })
            ->get();
    }

    /**
     * @param $user
     * @param int $productId
     * @return void
     */
    private function addProductToRecentViewed($user, int $productId)
    {
        if (!$user) {
            $user = $this->createUser();
        }

        $user->recentProducts()->syncWithoutDetaching([$productId]);
    }
}
