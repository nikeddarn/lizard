<?php
/**
 * Append common data for all views.
 */

namespace App\Http\Composers;


use App\Models\Category;
use App\Models\FavouriteProduct;
use App\Models\RecentProduct;
use App\Support\Seo\Locale\AlternateLinksGenerator;
use App\Support\Settings\SettingsRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class CommonDataComposer
{
    /**
     * @var Category
     */
    private $category;
    /**
     * @var FavouriteProduct
     */
    private $favouriteProduct;
    /**
     * @var RecentProduct
     */
    private $recentProduct;
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
     * CommonDataComposer constructor.
     * @param Request $request
     * @param Category $category
     * @param FavouriteProduct $favouriteProduct
     * @param RecentProduct $recentProduct
     * @param SettingsRepository $settingsRepository
     * @param AlternateLinksGenerator $alternateLinksGenerator
     */
    public function __construct(Request $request, Category $category, FavouriteProduct $favouriteProduct, RecentProduct $recentProduct, SettingsRepository $settingsRepository, AlternateLinksGenerator $alternateLinksGenerator)
    {
        $this->category = $category;
        $this->favouriteProduct = $favouriteProduct;
        $this->recentProduct = $recentProduct;
        $this->request = $request;
        $this->settingsRepository = $settingsRepository;
        $this->alternateLinksGenerator = $alternateLinksGenerator;
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

        // create user badges
        $userBadges = $this->getUserBadges();

        // retrieve user
        $user = auth('web')->user();

        // create contacts data
        $shopContacts = $this->getContactsData();

        // create links to change locale
        $availableLocalesLinksData = $this->createChangeLocaleLinks();

        // create seo alternate languages links
        $alternateLocalesLinks = $this->alternateLinksGenerator->createAlternateLinks();

        $view->with(compact('productCategories', 'userBadges', 'user', 'shopContacts', 'availableLocalesLinksData', 'alternateLocalesLinks'));
    }

    /**
     * Get product categories.
     *
     * @return Collection
     */
    private function getCategories(): Collection
    {
        $routeLocale = $this->request->route()->parameter('locale');

        $categories = $this->category->defaultOrder()
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
     * @return array
     */
    private function getUserBadges(): array
    {
        $badges = [];

        if (auth('web')->check() || $this->request->hasCookie('uuid')) {
            $badges['favourites'] = $this->getUserFavouriteProductsCount();
            $badges['recent'] = $this->getUserRecentProductsCount();
        }

        return $badges;
    }

    /**
     * Get user favourite products count.
     *
     * @return int
     */
    private function getUserFavouriteProductsCount(): int
    {
        $query = $this->favouriteProduct->newQuery();

        $query = $this->addUserProductConstraints($query);

        return $query->count();
    }

    /**
     * Get user recent products count.
     *
     * @return int
     */
    private function getUserRecentProductsCount(): int
    {
        $query = $this->recentProduct->newQuery();

        $query = $this->addUserProductConstraints($query);

        $query->where('updated_at', '>=', Carbon::now()->subDays(config('shop.recent_product_ttl')));

        return $query->count();
    }

    /**
     * Add user product constraints.
     *
     * @param Builder $query
     * @return Builder
     */
    private function addUserProductConstraints(Builder $query): Builder
    {
        $query->whereHas('product', function ($query) {
            $query->where('published', 1);
        });

        if (auth('web')->check()) {
            $query->where('users_id', auth('web')->id());
        } else {
            $query->where('uuid', $this->request->cookie('uuid'));
        }

        return $query;
    }

    /**
     * Get shop contacts data.
     *
     * @return array
     */
    private function getContactsData()
    {
        $phones = $this->settingsRepository->getProperty('contacts.phones');

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
}
