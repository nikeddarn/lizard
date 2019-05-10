<?php
/**
 * Append common data for all views.
 */

namespace App\Http\Composers;


use App\Models\Category;
use App\Models\Product;
use App\Support\Seo\Locale\AlternateLinksGenerator;
use App\Support\Settings\SettingsRepository;
use App\Support\Shop\Products\CartProducts;
use App\Support\User\RetrieveUser;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CommonDataComposer
{
    use RetrieveUser;

    /**
     * @var Category
     */
    private $category;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var SettingsRepository
     */
    private $settingsRepository;
    /**
     * @var AlternateLinksGenerator
     */
    private $alternateLinksGenerator;
    /**
     * @var CartProducts
     */
    private $cartProducts;

    /**
     * CommonDataComposer constructor.
     * @param Request $request
     * @param Category $category
     * @param SettingsRepository $settingsRepository
     * @param AlternateLinksGenerator $alternateLinksGenerator
     * @param CartProducts $cartProducts
     */
    public function __construct(Request $request, Category $category, SettingsRepository $settingsRepository, AlternateLinksGenerator $alternateLinksGenerator, CartProducts $cartProducts)
    {
        $this->category = $category;
        $this->request = $request;
        $this->settingsRepository = $settingsRepository;
        $this->alternateLinksGenerator = $alternateLinksGenerator;
        $this->cartProducts = $cartProducts;
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
        // create product categories
        $productCategories = $this->getCategories();

        $user = $this->getUser();

        if ($user) {
            // create user badges
            $userBadges = $this->getUserBadges($user);

            // get cart data
            $cartData = $this->getCartData($user);
        }

        // create contacts data
        $shopContacts = $this->getContactsData();

        // create links to change locale
        $availableLocalesLinksData = $this->createChangeLocaleLinks();

        // create seo alternate languages links
        $alternateLocalesLinks = $this->alternateLinksGenerator->createAlternateLinks();


        $view->with(compact('productCategories', 'userBadges', 'user', 'shopContacts', 'availableLocalesLinksData', 'alternateLocalesLinks', 'cartData'));
    }

    /**
     * Get product categories.
     *
     * @return Collection
     */
    private function getCategories(): Collection
    {
        $routeLocale = $this->request->route()->parameter('locale');

        $categories = $this->category->where('published', 1)
            ->defaultOrder()
            ->get()
            ->each(function (Category $category) use ($routeLocale) {
                if ($category->isLeaf()) {
                    $routeName = 'shop.category.leaf.index';
                } else {
                    $routeName = 'shop.category.index';
                }

                $category->href = route($routeName, [
                    'url' => $category->url,
                    'locale' => $routeLocale,
                ]);
            })
            ->toTree();

        return $categories;
    }

    /**
     * Create user badges.
     *
     * @param $user
     * @return array
     */
    private function getUserBadges($user): array
    {
        return [
            'favourites' => $user->favouriteProducts()->count(),
            'recent' => $user->timeLimitedRecentProducts()->count(),
            'orders' => $user->activeOrders()->count(),
        ];
    }

    /**
     * Get shop contacts data.
     *
     * @return array
     */
    private function getContactsData()
    {
        $phones = $this->settingsRepository->getProperty('content.common.header.phones');

        return compact('phones');
    }

    /**
     * Create possible locales links.
     *
     * @return array
     */
    private function createChangeLocaleLinks(): array
    {
        $links = [];

        $canonicalLocale = config('app.canonical_locale');

        // get query string
        $queryStringParameters = $this->request->query();

        foreach (config('app.available_locales') as $possibleLocale) {

            //get route name
            $routeName = $this->request->route()->getName();

            // get current route parameters
            $routeParameters = $this->request->route()->parameters();

            // set locale params
            if ($possibleLocale === $canonicalLocale) {
                $routeParameters['locale'] = null;
            } else {
                $routeParameters['locale'] = $possibleLocale;
            }

            // url for handling locale
            $possibleLocaleUrl = route($routeName, $routeParameters) . $this->createQueryString($queryStringParameters);

            $links[$possibleLocale] = $possibleLocaleUrl;
        }

        return $links;
    }

    /**
     * Create query string.
     *
     * @param array $parameters
     * @return string
     */
    protected function createQueryString(array $parameters)
    {
        return $parameters ? '?' . urldecode(http_build_query($parameters)) : '';
    }

    /**
     * Get user cart data.
     *
     * @param $user
     * @return array
     */
    private function getCartData($user)
    {
        $products = $this->cartProducts->getProducts($user);

        // calculate product's prices and total amount
        $amount = number_format($products->sum(function (Product $product) {
            return $product->localPrice * $product->pivot->count;
        }));

        return compact('products', 'amount');
    }
}
