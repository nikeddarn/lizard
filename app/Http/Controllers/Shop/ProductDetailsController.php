<?php

namespace App\Http\Controllers\Shop;

use App\Models\Attribute;
use App\Support\Breadcrumbs\ProductBreadcrumbs;
use App\Support\Headers\CacheControlHeaders;
use App\Support\Seo\MetaTags\ProductMetaTags;
use App\Support\Settings\SettingsRepository;
use App\Support\Shop\Products\SingleProduct;
use App\Support\User\RetrieveUser;
use App\Http\Controllers\Controller;
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
     * ProductDetailsController constructor.
     * @param Attribute $attribute
     * @param ProductBreadcrumbs $breadcrumbs
     * @param SingleProduct $productRetriever
     * @param ProductMetaTags $productMetaTags
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(Attribute $attribute, ProductBreadcrumbs $breadcrumbs, SingleProduct $productRetriever, ProductMetaTags $productMetaTags, SettingsRepository $settingsRepository)
    {
        $this->attribute = $attribute;
        $this->breadcrumbs = $breadcrumbs;
        $this->productRetriever = $productRetriever;
        $this->productMetaTags = $productMetaTags;
        $this->settingsRepository = $settingsRepository;
    }

    /**
     * Show product card.
     *
     * @param string $url
     * @return Response
     */
    public function index(string $url)
    {
        // retrieve product
        $product = $this->productRetriever->getProduct($url);

        if (!$product) {
            abort(404);
        }

        $user = $this->getUser();

        $response = response()->make();

        $pageLastModified = $product->updated_at;

        $this->checkAndSetLastModifiedHeader($user, $response, $pageLastModified);

        // get product's id
        $productId = $product->id;

        // retrieve product's attributes
        $productAttributes = $this->attribute->newQuery()
            ->whereHas('attributeValues.productAttributes', function ($query) use ($productId) {
                $query->where('products_id', $productId);
            })
            ->with(['attributeValues' => function ($query) use ($productId) {
                $query->whereHas('productAttributes', function ($query) use ($productId) {
                    $query->where('products_id', $productId);
                });
            }])
            ->get();

        $commentsPerPage = $this->settingsRepository->getProperty('shop.show_product_comments_per_page');

        $comments = $product->productComments()->with('user')->paginate($commentsPerPage);

        $breadcrumbs = $this->breadcrumbs->getProductBreadcrumbs($product);

        // title, description, keywords
        $pageTitle = $this->productMetaTags->getCategoryTitle($product);
        $pageDescription = $this->productMetaTags->getCategoryDescription($product);
        $pageKeywords = $this->productMetaTags->getCategoryKeywords($product);

        // add product to user's recent viewed
        $this->addProductToRecentViewed($user, $product->id);

        $response->setContent(view('content.shop.product.details.index')->with(compact('product', 'breadcrumbs', 'comments', 'productAttributes', 'pageTitle', 'pageDescription', 'pageKeywords')));

        $this->checkAndSetEtagHeader($user, $response);

        return $response;
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
