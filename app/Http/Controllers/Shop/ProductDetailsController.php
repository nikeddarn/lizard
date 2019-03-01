<?php

namespace App\Http\Controllers\Shop;

use App\Models\Attribute;
use App\Models\User;
use App\Support\Breadcrumbs\ProductBreadcrumbs;
use App\Support\Seo\MetaTags\ProductMetaTags;
use App\Support\Settings\SettingsRepository;
use App\Support\Shop\Products\SingleProduct;
use App\Support\User\RetrieveUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class ProductDetailsController extends Controller
{
    use RetrieveUser;

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
    private $productCreator;
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
     * @param SingleProduct $productCreator
     * @param ProductMetaTags $productMetaTags
     * @param SettingsRepository $settingsRepository
     */
    public function __construct(Attribute $attribute, ProductBreadcrumbs $breadcrumbs, SingleProduct $productCreator, ProductMetaTags $productMetaTags, SettingsRepository $settingsRepository)
    {
        $this->attribute = $attribute;
        $this->breadcrumbs = $breadcrumbs;
        $this->productCreator = $productCreator;
        $this->productMetaTags = $productMetaTags;
        $this->settingsRepository = $settingsRepository;
    }

    public function index(string $url)
    {
        // retrieve product
        $product = $this->productCreator->getProduct($url);

        if (!$product){
            abort(404);
        }

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
        $this->addProductToRecentViewed($product->id);

        return view('content.shop.product.details.index')->with(compact('product', 'breadcrumbs', 'comments', 'productAttributes', 'pageTitle', 'pageDescription', 'pageKeywords'));
    }

    /**
     * @param int $productId
     * @return void
     */
    private function addProductToRecentViewed(int $productId)
    {
        $user = $this->getUser();

        if (!$user){
            $user = $this->createUser();
        }

        $user->recentProducts()->syncWithoutDetaching([$productId]);
    }

    /**
     * Create new user identifying by cookie.
     *
     * @return User
     */
    private function createUser():User
    {
        $uuid = Str::uuid();

        $user = new User();
        $user->remember_token = $uuid;
        $user->save();

        // store user's cookie
        Cookie::queue(Cookie::forever('remember_token', $uuid));

        return $user;
    }
}
