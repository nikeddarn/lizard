<?php
/**
 * Composer for all common and user view
 */

namespace App\Http\Composers;


use App\Models\Category;
use App\Models\FavouriteProduct;
use App\Models\RecentProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;

class CommonDataComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     * @throws \Exception
     */
    public function compose(View $view)
    {
        $view->with('megaMenuCategories', Category::defaultOrder()->get()->toTree());

        $view->with('headerActionBadges', $this->getHeaderActionBadges());
    }

    /**
     * Get header action's badges.
     *
     * @return array
     */
    private function getHeaderActionBadges()
    {
        $badges = [];

        if (auth('web')->check()) {

            $user = auth('web')->user();

            $badges['recent'] = $user->timeLimitedRecentProducts()->count();

            $badges['favourite'] = $user->favouriteProducts()->count();

        } elseif (Cookie::has('uuid')) {

            $uuid = Cookie::get('uuid');

            $badges['recent'] = RecentProduct::where([
                    ['uuid', '=', $uuid],
                    ['updated_at', '>=', Carbon::now()->subDays(config('shop.recent_product_ttl'))],
                ])->count();

            $badges['favourite'] = FavouriteProduct::where('uuid', $uuid)->count();
        }

        return $badges;
    }
}